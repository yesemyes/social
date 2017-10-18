<?php

class I4S_Notice {

    public static function sytemNotice() {
        $i4sSytem = new I4S_System();
        $i4sCheck = $i4sSytem->check();
        if (is_array($i4sCheck) && !empty($i4sCheck)) {
            $output = '<div id="message" class="notice inline notice-warning notice-alt"><p>';
            $output .= $i4sSytem->getErrorMessage($i4sCheck, true);
            $output .= '</p></div>';
            echo $output;
        }
    }

}
