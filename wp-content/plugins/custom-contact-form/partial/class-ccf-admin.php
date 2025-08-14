<?php

if (!defined('ABSPATH')) exit;

class CCF_Admin
{
    private string $option_group = 'ccf_options_group';
    private string $option_name  = 'ccf_options';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_init', [$this, 'settings']);
    }

    /**
     * @return void
     */
    public function menu(): void
    {
        add_options_page(
            'Custom Contact Form',
            'Custom Contact Form',
            'manage_options',
            'ccf-settings',
            [$this, 'render']
        );
    }

    /**
     * @return void
     */
    public function settings(): void
    {
        register_setting($this->option_group, $this->option_name, [$this, 'sanitize']);

        add_settings_section('ccf_main', '', '__return_false', 'ccf-settings');

        add_settings_field('recipient', 'Recipient email.', [$this, 'field_recipient'], 'ccf-settings', 'ccf_main');
        add_settings_field('subject_tpl', 'Email subject line template', [$this, 'field_subject_tpl'], 'ccf-settings', 'ccf_main');
        add_settings_field('message_tpl', 'Email body template', [$this, 'field_message_tpl'], 'ccf-settings', 'ccf_main');
        add_settings_field('hubspot_token', 'HubSpot Private App Token', [$this, 'field_hubspot_token'], 'ccf-settings', 'ccf_main');
    }

    /**
     * @param array $input
     * @return array
     */
    public function sanitize(array $input): array
    {
        $out = [];

        $out['recipient'] = isset($input['recipient']) ? sanitize_email($input['recipient']) : '';
        $out['subject_tpl']   = isset($input['subject_tpl']) ? sanitize_text_field($input['subject_tpl']) : '';
        $out['message_tpl']   = isset($input['message_tpl']) ? wp_kses_post($input['message_tpl']) : '';
        $out['hubspot_token'] = isset($input['hubspot_token']) ? sanitize_text_field($input['hubspot_token']) : '';

        return $out;
    }

    /**
     * @param string $key
     * @param string $default
     * @return mixed|string
     */
    private function get_option(string $key, string $default = ''): mixed
    {
        $opts = get_option($this->option_name, []);

        return $opts[$key] ?? $default;
    }

    /**
     * @return void
     */
    public function field_recipient(): void
    {
        printf('<input type="email" name="%1$s[recipient]" value="%2$s" class="regular-text" placeholder="admin@example.com"/>',
            esc_attr($this->option_name),
            esc_attr($this->get_option('recipient', get_option('admin_email')))
        );

        echo '<p class="description">Where to send form emails</p>';
    }

    /**
     * @return void
     */
    public function field_subject_tpl(): void
    {
        printf('<input type="text" name="%1$s[subject_tpl]" value="%2$s" class="regular-text" placeholder="New message from {first_name} {last_name}"/>',
            esc_attr($this->option_name),
            esc_attr($this->get_option('subject_tpl', 'New message from {first_name} {last_name}'))
        );

        echo '<p class="description">Available placeholders: {first_name} {last_name} {email} {subject} {message}</p>';
    }

    /**
     * @return void
     */
    public function field_message_tpl(): void
    {
        $content = $this->get_option('message_tpl', "Name: {first_name} {last_name}\nE-mail: {email}\nSubject: {subject}\n\nMessage:\n{message}");

        wp_editor($content, 'ccf_message_tpl', [
            'textarea_name' => $this->option_name . '[message_tpl]',
            'textarea_rows' => 8,
            'media_buttons' => false,
        ]);

        echo '<p class="description">HTML can be used. The placeholders are the same as above.</p>';
    }

    /**
     * @return void
     */
    public function field_hubspot_token(): void
    {
        printf('<input type="text" name="%1$s[hubspot_token]" value="%2$s" class="regular-text" placeholder="pat-..."/>',
            esc_attr($this->option_name),
            esc_attr($this->get_option('hubspot_token', ''))
        );

        echo '<p class="description">Private App Token with contact permissions (crm.objects.contacts.write/read). Authorization via header <code>Authorization: Bearer &lt;token&gt;</code>.</p>';
    }

    /**
     * @return void
     */
    public function render(): void
    {
        echo '<div class="wrap"><h1>Custom Contact Form</h1>';
        echo '<form method="post" action="options.php">';

        settings_fields($this->option_group);
        do_settings_sections('ccf-settings');

        submit_button();

        echo '</form>';
        echo '<hr/><p><strong>Shortcode:</strong> <code>[ccf_form]</code></p>';
        echo '</div>';
    }
}
