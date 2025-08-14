<?php
/**
 * Plugin Name: Custom Contact Form (HubSpot)
 * Description: Custom contact form with HubSpot integration
 * Version: 1.0.0
 * Author: ArmDevStack
 * Text Domain: Custom Contact Form
 */

if (!defined('ABSPATH')) exit;

define('CCF_PATH', plugin_dir_path(__FILE__));
define('CCF_URL', plugin_dir_url(__FILE__));

const CCF_VERSION = '1.0.0';

require_once CCF_PATH . 'partial/class-ccf-admin.php';
require_once CCF_PATH . 'partial/class-ccf-hubspot.php';
require_once CCF_PATH . 'partial/class-ccf-form-handler.php';

register_activation_hook(__FILE__, function (): void
{
    $dir = CCF_PATH . 'logs';

    if (!file_exists($dir)) wp_mkdir_p($dir);
});

add_action('plugins_loaded', function () {
    new CCF_Admin();
    new CCF_Form_Handler();
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('ccf-style', CCF_URL . 'assets/style.css', [], CCF_VERSION);
});

