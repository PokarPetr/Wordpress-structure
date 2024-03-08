<?php

namespace src;
class PROJECT_Templates {
    public function __construct() {
        add_filter('template_include', [$this, 'profile_page_template'], 99);
    }   

    private function profile_page_template($template) {
        // Check if the current page is the profile page
        if (is_page('profile')) {
            // Path to your profile page template
            $new_template = locate_template(['templates/profile.php']);
    
            // If a suitable template is found, use it
            if ('' !== $new_template) {
                return $new_template;
            }
        }    
        // Otherwise, return the original template
        return $template;
    }
}