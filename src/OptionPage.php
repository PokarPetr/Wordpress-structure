<?php

class Option_Page {

    private $options;

    private $fields = [
        [
            'name'  => 'name_of_field',
            'label' => 'Label Of Field',
            'type'  => 'text',  // text, number ...
        ]
    ];

    public function __construct() {
        add_action( 'admin_menu', [$this, 'option_page_menu'] );
        add_action( 'admin_init', array( $this, 'option_page_init' ) );
        $this->options = get_option( 'options' );
    }

    public function option_page_menu() {
        add_options_page( 'Options', 'Options', 'manage_options', 'project-options', [$this, 'option_page'] );
    }

    public function option_page() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        ?>
        <div class="wrap">
            <h1>Option Page</h1>

            <form method="post" action="options.php">

                <?php
                // This prints out all hidden setting fields
                settings_fields( 'option_group' );
                do_settings_sections( 'goya-setting-admin' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    /**
     * Register and add settings
     */
    public function option_page_init()
    {
        register_setting(
            'option_group',
            'options',
            [ $this, 'sanitize' ]
        );

        add_settings_section(
            'section_id', // ID
            'options', // Title
            [ $this, 'print_section_info' ], // Callback
            'setting-admin' // Page
        );

        foreach ( $this->fields as $field ) {
            add_settings_field(
                $field[ 'name' ],
                $field[ 'label' ],
                [ $this, 'callback' ],
                'setting-admin',
                'section_id',
                [ 'field' => $field ]
            );
        }
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */


    public function sanitize( $input )
    {
        $new_input = array();
        foreach ( $input as $key => $value ) {
            $new_input[$key] = sanitize_text_field($value);
        }
        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter constants below:';
    }

    public function callback($args)
    {
        $field_name = esc_attr($args['field']['name']);
        $field_type = esc_attr($args['field']['type']);

        printf(
            '<input type="%1$s" id="%2$s" name="options[%2$s]" value="%3$s" />',
            $field_type,
            $field_name,
            isset( $this->options[$field_name] ) ? esc_attr( $this->options[$field_name]) : ''
        );
    }
}

