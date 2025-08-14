<?php

if (!defined('ABSPATH')) exit;

class CCF_Form_Handler
{
    private array $errors = [];
    private bool $success = false;

    public function __construct()
    {
        add_shortcode('ccf_form', [$this, 'render_shortcode']);
    }

    /**
     * @param string $email
     * @return bool
     */
    private function valid_email(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;

        $parts = explode('@', $email);

        if (count($parts) !== 2) return false;

        $domain = $parts[1];

        if (!str_contains($domain, '.')) return false;

        return true;
    }

    /**
     * @param $tpl
     * @param array $data
     * @return string
     */
    private function replace_placeholders($tpl, array $data): string
    {
        $map = [
            '{first_name}' => $data['first_name'] ?? '',
            '{last_name}'  => $data['last_name']  ?? '',
            '{email}'      => $data['email']      ?? '',
            '{subject}'    => $data['subject']    ?? '',
            '{message}'    => $data['message']    ?? '',
        ];

        return strtr($tpl, $map);
    }

    /**
     * @param string $email
     * @return void
     */
    private function log_success(string $email): void
    {
        $dir = CCF_PATH . 'logs';
        if (!file_exists($dir)) wp_mkdir_p($dir);

        $file = trailingslashit($dir) . 'form.log';
        $line = sprintf("[%s] Mail sent to recipient from: %s\n", current_time('mysql'), $email);

        $res = file_put_contents($file, $line, FILE_APPEND);

        if ($res === false)
        {
            error_log('CCF: failed to write form.log to ' . $file);
        }
    }


    /**
     * @return void
     */
    private function handle_post(): void
    {
        if (!isset($_POST['ccf_nonce']) || !wp_verify_nonce($_POST['ccf_nonce'], 'ccf_form'))
        {
            $this->errors[] = 'Invalid request, please refresh the page and try again.';

            return;
        }

        $first_name = isset($_POST['ccf_first_name']) ? sanitize_text_field($_POST['ccf_first_name']) : '';
        $last_name  = isset($_POST['ccf_last_name'])  ? sanitize_text_field($_POST['ccf_last_name'])  : '';
        $subject    = isset($_POST['ccf_subject'])    ? sanitize_text_field($_POST['ccf_subject'])    : '';
        $message    = isset($_POST['ccf_message'])    ? wp_kses_post($_POST['ccf_message'])           : '';
        $email      = isset($_POST['ccf_email'])      ? sanitize_email($_POST['ccf_email'])           : '';
        $hp         = isset($_POST['ccf_hp'])         ? trim($_POST['ccf_hp'])                         : '';

        if ($hp !== '') return;

        if (!$this->valid_email($email))
        {
            $this->errors[] = 'The email is entered incorrectly.';

            return;
        }

        $opts = get_option('ccf_options', []);

        $recipient   = !empty($opts['recipient']) ? sanitize_email($opts['recipient']) : get_option('admin_email');
        $subject_tpl = !empty($opts['subject_tpl']) ? $opts['subject_tpl'] : 'New message from {first_name} {last_name}';
        $message_tpl = !empty($opts['message_tpl']) ? $opts['message_tpl'] : "Name: {first_name} {last_name}\nE-mail: {email}\nSubject: {subject}\n\nMessage:\n{message}";

        $data = compact('first_name','last_name','subject','message','email');

        $final_subject = $this->replace_placeholders($subject_tpl, $data);
        $final_message = $this->replace_placeholders($message_tpl, $data);

        $headers = ['Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $email];

        $sent = wp_mail($recipient, $final_subject, wpautop($final_message), $headers);

        if ($sent)
        {
            $this->log_success($email);

            CCF_HubSpot::create_contact($email, $first_name, $last_name);

            $this->success = true;
        }
        else
        {
            $this->errors[] = 'Failed to send the email. Please try again later.';
        }
    }

    /**
     * @return false|string
     */
    public function render_shortcode(): false|string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ccf_submitted']))
        {
            $this->handle_post();
        }

        $first_name = isset($_POST['ccf_first_name']) ? esc_attr($_POST['ccf_first_name']) : '';
        $last_name  = isset($_POST['ccf_last_name'])  ? esc_attr($_POST['ccf_last_name'])  : '';
        $subject    = isset($_POST['ccf_subject'])    ? esc_attr($_POST['ccf_subject'])    : '';
        $message    = isset($_POST['ccf_message'])    ? esc_textarea($_POST['ccf_message']) : '';
        $email      = isset($_POST['ccf_email'])      ? esc_attr($_POST['ccf_email'])      : '';

        ob_start();

        if (!empty($this->errors))
        {
            echo '<div class="ccf-alert ccf-alert-error">';
            foreach ($this->errors as $e) echo '<p>' . esc_html($e) . '</p>';
            echo '</div>';
        }

        if ($this->success)
        {
            echo '<div class="ccf-alert ccf-alert-success"><p>Email sent. Thank you!</p></div>';
            $first_name = $last_name = $subject = $message = $email = '';
        }
        ?>

        <form class="ccf-form" method="post">
            <?php wp_nonce_field('ccf_form', 'ccf_nonce'); ?>
            <input type="hidden" name="ccf_submitted" value="1">

            <input type="text" name="ccf_hp" value="" style="display:none" tabindex="-1" autocomplete="off">

            <div class="ccf-row">
                <label>First Name</label>
                <input type="text" name="ccf_first_name" value="<?php echo $first_name; ?>">
            </div>

            <div class="ccf-row">
                <label>Last Name</label>
                <input type="text" name="ccf_last_name" value="<?php echo $last_name; ?>">
            </div>

            <div class="ccf-row">
                <label>Subject</label>
                <input type="text" name="ccf_subject" value="<?php echo $subject; ?>">
            </div>

            <div class="ccf-row">
                <label>Message</label>
                <textarea name="ccf_message" rows="7" placeholder="Your message..."><?php echo $message; ?></textarea>
                <small>Simple HTML is supported (it will be safely sanitized).</small>
            </div>

            <div class="ccf-row">
                <label>E-mail</label>
                <input type="email" name="ccf_email" value="<?php echo $email; ?>">
                <small>Example: example@site.com. The format <code>example@test</code> is considered invalid.</small>
            </div>

            <div class="ccf-actions">
                <button type="submit">Send</button>
            </div>
        </form>
        <?php

        return ob_get_clean();
    }
}
