<?php

/**
 * Plugin Name:       WorkChat
 * Plugin URI:        https://workchat.cloodo.com/
 * Description:       Chat system for your website
 * Version:           2.3.3
 * Requires at least: 5.2
 * Requires PHP:      7.3
 * Author:            Cloodo
 * Author URI:        https://cloodo.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       workchat
 * Domain Path:       /languages
 */



if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define('CLME_BASE_API', "https://erp.cloodo.com/api/v1/");

/* add core cloodo active */
require_once __DIR__ . '/includes/cloodo_core.php';
require_once __DIR__ . '/includes/class-cloodo-workchat.php';
require "app/view/clme_alert_message.php";
require "app/view/clme_icon_box.php";


add_action('wp_footer', 'clme_icon_box');

function clme_add_front_css_js()
{
    wp_enqueue_style('workchat-style', plugins_url('/public/css/frontend.css', __FILE__));
    wp_enqueue_script('clme_js_boxChat.js', plugins_url('./public/js/clme_js_boxChat.js', __FILE__));
}
add_action('wp_enqueue_scripts', 'clme_add_front_css_js');



$token = get_option('cloodo_token', '');

$admin_menus = [
    'work-chat' => [
        'parent_slug' => null,
        'target_url' => null,
        'token_require' => false,
        'page_title' => 'WhatsApp CRM & LiveChat',
        'menu_title' => 'WhatsApp CRM & LiveChat',
        'capability' => 'manage_options',
        'include' => __DIR__ . "/includes/clme_dashboard.php",
        'icon_url' => plugins_url('admin/images/icons8-messaging-20.png', __FILE__),
        'position' => 2,
        'enqueue_style' => null,
        'hide_menu' => true, 
    ],
    'work-chat-dash-board' => [
        'parent_slug' => 'work-chat',
        'target_url' => null,
        'token_require' => true,
        'page_title' => 'Dashboards',
        'menu_title' => 'Dashboards',
        'capability' => 'manage_options',
        'include' => __DIR__ . "/includes/clme_dashboard.php",
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
    'work-chat-messages' => [
        'parent_slug' => 'work-chat',
        'target_url' => 'https://worksuite.cloodo.com/apps/message?tokenws=' . $token,
        'token_require' => true,
        'page_title' => 'Chat Messages',
        'menu_title' => 'Chat Messages',
        'capability' => 'manage_options',
        'include' => null,
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
    'work-chat-client' => [
        'parent_slug' => 'work-chat',
        'target_url' => 'https://worksuite.cloodo.com/apps/clients/all-clients?tokenws=' . $token,
        'token_require' => true,
        'page_title' => 'Client Hub',
        'menu_title' => 'Client Hub',
        'capability' => 'manage_options',
        'include' => null,
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
    'work-chat-agent-setting' => [
        'parent_slug' => 'work-chat',
        'target_url' => 'https://worksuite.cloodo.com/apps/chat-team?tokenws=' . $token,
        'token_require' => true,
        'page_title' => 'Agent Team',
        'menu_title' => 'Agent Team',
        'capability' => 'manage_options',
        'include' => null,
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
    'work-chat-file-storage' => [
        'parent_slug' => 'work-chat',
        'target_url' => 'https://worksuite.cloodo.com/apps/chat-file-storage?tokenws=' . $token,
        'token_require' => true,
        'page_title' => 'File Storage',
        'menu_title' => 'File Storage',
        'capability' => 'manage_options',
        'include' => null,
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
    'work-chat-contact' => [
        'parent_slug' => 'work-chat',
        'target_url' => 'https://worksuite.cloodo.com/apps/all-contact?tokenws=' . $token,
        'token_require' => true,
        'page_title' => 'Contact',
        'menu_title' => 'Contact',
        'capability' => 'manage_options',
        'include' => null,
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
    // disable
    // 'work-chat-theme-setting' => [
    //     'parent_slug' => 'work-chat',
    //     'target_url' => 'https://worksuite.cloodo.com/apps/settings/form-setting-chat?tokenws=' . $token,
    //     'token_require' => true,
    //     'page_title' => 'Theme Setting',
    //     'menu_title' => 'Theme Setting',
    //     'capability' => 'manage_options',
    //     'include' => null,
    //     'icon_url' => null,
    //     'position' => null,
    //     'enqueue_style' => null,
    // ],
    'work-chat-whatsapp-wizard' => [
        'parent_slug' => 'work-chat',
        'target_url' => 'https://worksuite.cloodo.com/apps/settings/setup-workchat?tokenws=' . $token,
        'token_require' => true,
        'page_title' => 'Setup Wizard',
        'menu_title' => 'Setup Wizard',
        'capability' => 'manage_options',
        'include' => null,
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
    'work-chat-setting' => [
        'parent_slug' => 'work-chat',
        'target_url' => null,
        'token_require' => true,
        'page_title' => 'Setting',
        'menu_title' => 'Setting',
        'capability' => 'manage_options',
        'include' => __DIR__ . "/includes/clme_setting.php",
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
    'clmessenger-chat-support' => [
        'parent_slug' => 'work-chat',
        'target_url' => null,
        'token_require' => true,
        'page_title' => 'Support',
        'menu_title' => 'Support',
        'capability' => 'manage_options',
        'include' => __DIR__ . "/includes/clme_support.php",
        'icon_url' => null,
        'position' => null,
        'enqueue_style' => null,
    ],
];

add_action('init', function () use ($admin_menus) {
    /* add admin menu */
    cloodo_admin_page($admin_menus);
    add_action('cloodo_page_content', function () {
        require __DIR__ . "/includes/clme_dashboard.php";
    });

    $token = get_option('cloodo_token');
});

// 	code lay token  cua admin
// $token_admin = Cloodo_Workchat::get_token($token);
// var_dump($token_admin);

// $emp = Cloodo_Workchat::get_employees();
// var_dump($emp);

// Redirect after plugin activation
register_activation_hook(__FILE__, 'clme_activate');
function clme_activate() {
    add_option('clme_plugin_activated', true);
}

add_action('admin_init', 'clme_redirect_to_settings_page');
function clme_redirect_to_settings_page() {
    if (get_option('clme_plugin_activated', false)) {
        delete_option('clme_plugin_activated');
        if (!isset($_GET['activate-multi'])) {
            wp_redirect(admin_url('admin.php?page=work-chat'));
            exit;
        }
    }
}
add_action('admin_menu', function() {
    remove_submenu_page('work-chat', 'work-chat');
}, 999);