<?php

class I4S_System {

    public function __construct() {

    }

    public function check($action = 'before') {
        $result = array();
        if ($action == 'before') {
            if (!$this->checkCurl()) {
                $result['curl'] = false;
            }
        }
        if ($action == 'after') {
            if (!$this->checkDbTables()) {
                $result['dbTable'] = false;
            }
        }

        return empty($result) ? true : $result;
    }

    private function checkCurl() {
        return function_exists('curl_version');
    }

    private function checkDbTables() {
        global $wpdb;
        $i4sUserCols = $wpdb->get_results('SHOW COLUMNS FROM i4s_user');
        if (is_array($i4sUserCols) && isset($i4sUserCols[0])) {
            $i4sUserColsData = array();
            foreach ($i4sUserCols as $key => $value) {
                if (isset($value->Field) && !empty($value->Field)) {
                    $i4sUserColsData[] = $value->Field;
                }
            }
            return (in_array("state_url", $i4sUserColsData)) ? true : false;
        }
        return false;
    }

    public function getErrorMessage($errors, $removeBreakline = false) {
        $output = '';
        if (is_array($errors) && !empty($errors)) {
            foreach ($errors as $error => $status) {
                if ($error == 'curl' && $status == false) {
                    $output .= __('Iimagine4Social used cURL. cURL is not installed in your PHP installation on your server. Install cURL and activate Iimagine4Social again.', 'iio4social');
                    $output .= (!$removeBreakline) ? '<br>' : ' ';
                    $output .= (!$removeBreakline) ? '<br>' : ' ';
                    $output .= __('Please see <a href="https://www.blog2social.com/en/faq/category/9/troubleshooting-for-error-messages.html" target="_blank">FAQ</a>', 'iio4social') . '</a>';
                }
                if ($error == 'dbTable' && $status == false) {
                    $output .= __('Iimagine4Social seems to have no permission to write in your WordPress database. Please make sure to assign Blog2Social the permission to write in the WordPress database.', 'iio4social');
                    $output .= (!$removeBreakline) ? '<br>' : ' ';
                    $output .= (!$removeBreakline) ? '<br>' : ' ';
                    $output .= __('<a href="https://www.blog2social.com/en/faq/category/9/troubleshooting-for-error-messages.html" target="_blank"> Please find more Information and help in our FAQ</a>', 'iio4social') . '</a>.';
                }
            }
        }
        return $output;
    }

    public function deactivatePlugin() {
        deactivate_plugins(I4S_PLUGIN_BASENAME);
    }

}
