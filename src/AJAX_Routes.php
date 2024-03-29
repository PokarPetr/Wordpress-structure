<?php
namespace src;
class Routes{
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'project_routes' ] );
    }
    public function project_routes() {
        register_rest_route('university/v1', 'search', [
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'SearchResults'
        ]);
    }
    public function SearchResults () {

    }
}
