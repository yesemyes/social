<?php

class I4S_Init {

    public function __construct(){

    }

    public static function showNotice() {
        return (defined("B2S_PLUGIN_NOTICE")) ? true : false;
    }

    public function init(){

        define('I4S_PLUGIN_NETWORK', serialize(array(1 => 'Facebook', 2 => 'Twitter', 3 => 'Linkedin', 4 => 'Tumblr', 5 => 'Storify', 6 => 'Pinterest', 7 => 'Flickr', 8 => 'Xing', 9 => 'Diigo', 10 => 'Google+', 11 => 'Medium', 12 => 'Instagram', 13 => 'Delicious', 14 => 'Torial', 15 => 'Reddit')));
        add_action('admin_menu', array($this, 'createMenu'));
        add_action('admin_menu', array($this, 'registerAssets'));
    }



    public function createMenu() {

        $subPages = array();
        add_menu_page('IIO4Social', 'IIO4Social', 'read', 'iio4social', null, plugins_url('/assets/img/rsz_icon.png', I4S_PLUGIN_FILE));

        $subPages[] = add_submenu_page('iio4social', 'iio4social', __('Dashboard', 'iio4social'), 'read', 'iio4social', array($this, 'i4sstart'));

        $subPages[] = add_submenu_page('iio4social', __('Posts & Sharing', 'iio4social'), __('Posts & Sharing', 'iio4social'), 'read', 'iio4social-post', array($this, 'i4sPost'));

        $subPages[] = add_submenu_page(null, 'B2S Ship', 'B2S Ship', 'read', 'blog2social-ship', array($this, 'b2sShip'));

        $subPages[] = add_submenu_page(null, 'B2S Ship', 'B2S Ship', 'read', 'logout', array($this, 'logout'));

        $subPages[] = add_submenu_page('iio4social', __('Networks', 'iio4social'), __('Networks', 'iio4social'), 'read', 'iio4social-network', array($this, 'i4sNetwork'));
        $subPages[] = add_submenu_page('iio4social', __('Settings', 'iio4social'), __('Settings', 'iio4social'), 'read', 'iio4social-settings', array($this, 'i4sSettings'));

        foreach ($subPages as $var) {
            add_action($var, array($this, 'addAssets'));
        }
    }

    public function registerAssets() {
        wp_register_style('I4SMAINCSS', plugins_url('assets/css/main.css', I4S_PLUGIN_FILE), array(), I4S_PLUGIN_VERSION);
        wp_register_style('I4SCSS', plugins_url('assets/css/start.css', I4S_PLUGIN_FILE), array(), I4S_PLUGIN_VERSION);
        wp_register_style('FAWESOME', plugins_url('assets/css/font-awesome/font-awesome.min.css', I4S_PLUGIN_FILE), array(), I4S_PLUGIN_VERSION);
        wp_register_style('I4SNETWORKCSS', plugins_url('assets/css/network.css', I4S_PLUGIN_FILE), array(), I4S_PLUGIN_VERSION);
        wp_register_style('I4SPOSTCSS', plugins_url('assets/css/posts.css', I4S_PLUGIN_FILE), array(), I4S_PLUGIN_VERSION);
    }

    public function addAssets() {
        wp_enqueue_style('I4SMAINCSS');
    }

    /* IIO 4 Social start (includes dashboard or notices)*/
    public function i4sstart() {
        if (self::showNotice() == false) {
            wp_enqueue_style('I4SCSS');

            require_once( I4S_PLUGIN_DIR . 'views/i4s/dashboard.php');
        } else {
            require_once( I4S_PLUGIN_DIR . 'views/notice.php');
        }
    }

    /* IIO 4 Social settings page */
    public function i4sSettings(){
        if (self::showNotice() == false) {
            wp_enqueue_style('I4SSETTINGCSS');
            wp_enqueue_script('I4SSETTINGJS');
            wp_enqueue_style('FAWESOME');
            require_once( I4S_PLUGIN_DIR . 'views/i4s/settings.php');
        } else {
            require_once( I4S_PLUGIN_DIR . 'views/notice.php');
        }
    }

    /* IIO 4 Social networks page */
    public function i4sNetwork(){
        if (self::showNotice() == false) {
            wp_enqueue_style('I4SNETWORKCSS');
            wp_enqueue_script('I4SNETWORKJS');
            wp_enqueue_style('FAWESOME');
            require_once( I4S_PLUGIN_DIR . 'views/i4s/network.php');
        } else {
            require_once( I4S_PLUGIN_DIR . 'views/notice.php');
        }
    }

    /* IIO 4 Social posts page */
    public function i4sPost() {
        if (self::showNotice() == false) {
            wp_enqueue_style('I4SPOSTCSS');
            wp_enqueue_style('FAWESOME');
            wp_enqueue_script('I4SPOSTJS');
            require_once( I4S_PLUGIN_DIR . 'views/i4s/post.php');
        } else {
            require_once( I4S_PLUGIN_DIR . 'views/notice.php');
        }
    }

    /* IIO 4 Social post item page */
    public function b2sShip() {
        if (self::showNotice() == false) {
            wp_enqueue_style('I4SPOSTCSS');
            wp_enqueue_style('FAWESOME');
            wp_enqueue_script('I4SPOSTJS');
            require_once( I4S_PLUGIN_DIR . 'views/i4s/postItem.php');
        } else {
            require_once( I4S_PLUGIN_DIR . 'views/notice.php');
        }
    }

    /* IIO 4 Social Logout */
    public function logout() {
        if (self::showNotice() == false) {
            wp_enqueue_style('I4SPOSTCSS');
            wp_enqueue_style('FAWESOME');
            wp_enqueue_script('I4SPOSTJS');
            require_once( I4S_PLUGIN_DIR . 'views/i4s/logout.php');
        } else {
            require_once( I4S_PLUGIN_DIR . 'views/notice.php');
        }
    }

    /* Activate plugin */
    public function activatePlugin(){
        require_once (I4S_PLUGIN_DIR . 'includes/system.php');
        $i4sSystem = new I4S_System();
        $i4sCheckBefore = $i4sSystem->check('before');
        if (is_array($i4sCheckBefore)) {
            $i4sSystem->deactivatePlugin();
            wp_die($i4sSystem->getErrorMessage($i4sCheckBefore) . ' ' . __('or', 'iio4social') . '  <a href="' . admin_url("/plugins.php", "http") . '/">' . __('back to install plugins', 'iio4social') . '</a>');
        }
    }

}

