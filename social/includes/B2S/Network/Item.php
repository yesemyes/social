<?php

class I4S_Network_Item {

    public function __construct($load = true) {

    }
    /* Set static providers (must be changed in future and get it via standalone api) */
    public function staticProviders(){
        return array(
            array(
                'id' => 1,
                'title' => 'facebook',
                'icon' => 'facebook',
            ),
            array(
                'id' => 2,
                'title' => 'twitter',
                'icon' => 'twitter',
            ),
            array(
                'id' => 3,
                'title' => 'google+',
                'icon' => 'google-plus',
            ),
            array(
                'id' => 4,
                'title' => 'linkedin',
                'icon' => 'linkedin',
            ),
            array(
                'id' => 5,
                'title' => 'instagram',
                'icon' => 'instagram',
            ),
            array(
                'id' => 6,
                'title' => 'reddit',
                'icon' => 'reddit',
            ),
            array(
                'id' => 7,
                'title' => 'pinterest',
                'icon' => 'pinterest',
            ),
        );
    }

    public function getData() {
        $sprovs = $this->staticProviders();
        $obj = array();
        foreach ($sprovs as $index => $provider){
            $obj[$index] = new stdClass();
            $obj[$index]->id = $provider['id'];
            $obj[$index]->title = $provider['title'];
            $obj[$index]->icon = $provider['icon'];
        }
        return $obj;
    }

    public function getNetworkItemHtml($networks) {
        $html = '<ul class="networkList">';
        foreach ($networks as $k => $network) {
            $html .= $this->_genNetworkHtml($network->id, $network->title,$network->icon);
        }
        $html .= '</ul>';

        return $html;
    }

    public function _genNetworkHtml($networkId,$networkName,$networkIcon){
        $html = '<li class="list-group-item">';
        $html .= '<div class="itm clearfix">';
        $html .= '<div class="ic"><i class="fa fa-'.$networkIcon.'"></i>'.$networkName.'</div>';
        $html .= '<a href="#" class="addItm"><i class="fa fa-plus"></i>&nbsp;Add profile</a>';
        $html .= '</div>';
        $html .= '</li>';
        return $html;
    }

}
