<?php

namespace src;

class PROJECT_Checkout {
    public function __construct()
    {        
        add_filter('body_class', [$this, 'set_account_body_class'], 199, 1);
    }

    public function set_account_body_class($classes) {
        if (is_account_page()) {
            return array_merge( $classes, [ 'project_account' ] );
        }
        return $classes;
    }
}   

