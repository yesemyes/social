<?php session_start();

/*
 * Plugin Name: IIO Social Sharing
 * Plugin URI: https://www.iimagine.org
 * Description:Auto publish, schedule & share posts on social media: Facebook, Twitter, Google+, XING, LinkedIn, Instagram, ... crosspost to pages & groups
 * Author: Freelancer
 * Text Domain: iio4social
 * Version: 1.0.0
 * Author URI: https://www.iimagine.org
 * License: GPL2+
 */

//I4SDefine
define('I4S_PLUGIN_VERSION', '100');
define('I4S_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('I4S_PLUGIN_URL', plugin_dir_url(__FILE__));
define('I4S_PLUGIN_HOOK', basename(dirname(__FILE__)) . '/' . basename(__FILE__));
define('I4S_PLUGIN_FILE', __FILE__);
define('I4S_PLUGIN_BASENAME', plugin_basename(__FILE__));

// I4SLoad
require_once(I4S_PLUGIN_DIR . 'includes/init.php');
$i4sInit = new I4S_Init();
register_activation_hook(I4S_PLUGIN_FILE, array($i4sInit, 'activatePlugin'));
register_deactivation_hook(I4S_PLUGIN_FILE, array($i4sInit, 'deactivatePlugin'));


// Include system
require_once (I4S_PLUGIN_DIR . 'includes/system.php');
$i4sCheck = new I4S_System();
if ($i4sCheck->check() === true) {
    add_action('init', array($i4sInit, 'init'));
} else {
    require_once(I4S_PLUGIN_DIR . 'includes/notice.php');
    add_action('admin_notices', array('I4S_Notice', 'sytemNotice'));
}