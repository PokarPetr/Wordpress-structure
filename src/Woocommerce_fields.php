<?php

namespace src;
class PROJECT_Woocommerce_Fields {
    private static $project_checkout_billing_fields_array = [
        'billing_last_name' => [
            'label' => 'Last Name',
            'required' => true,
            'class' => ['form-row-first'],
            'placeholder' => '',
            'priority' => 10
        ],
        'billing_first_name' => [
            'label' => 'Given Name',
            'required' => true,
            'class' => ['form-row-last'],
            'placeholder' => '',
            'priority' => 20
        ]
        ];

    private static $project_checkout_additional_fields_array = [
        
        'profile_is_ccfr_member' => [
            'type'      => 'radio',
            'label'     => 'Are you a member of the CCFR with another club?',
            'required'  => false,
            'class'     => ['form-row-wide'],
            'input_class' => ['csc_radio_button'],
            'label_class' => ['csc_radio_label'],
            'priority'  => 45,
            'options'   => [
                'yes' => 'Yes',
                'no'  => 'No',
            ],
        ]
    ];
    public function __construct() {
        add_action( 'woocommerce_checkout_before_customer_details', [$this, 'project_open_wrapper'] );
        add_action( 'woocommerce_checkout_after_order_review', [$this, 'project_close_wrapper'] );
        add_filter( 'woocommerce_checkout_fields', [$this, 'project_checkout_fields_customize']);
        add_action( 'woocommerce_before_order_notes', [$this, 'project_add_checkout_field'] );
        add_action( 'woocommerce_checkout_update_order_meta', [$this, 'project_save_custom_checkout_field'] );
        add_action( 'woocommerce_admin_order_data_after_billing_address', [$this, 'project_show_custom_checkout_field_order'] );
        add_filter( 'woocommerce_form_field', [$this, 'project_field_filter'], 10, 4);
        remove_action( 'woocommerce_after_checkout_billing_form', 'DSCFW_add_signature_field' );
        add_action( 'woocommerce_before_order_notes', [ $this, 'add_signature_block' ] );

    }

    public static function get_additional_fields() {
        return self::$project_checkout_additional_fields_array;
    }

    /**
     * Add the custom wrapper div
     */
    public function project_open_wrapper() {
        echo '<div class="project_checkout_wrapper">';
    }
    public function project_close_wrapper() {
        echo '</div>';
    }

    /**
     * Modify billing fields
     * @param $checkout_fields
     * @return array
     */

    public function project_checkout_fields_customize($checkout_fields) {

        $unset_array = ['billing', 'shipping', 'account', 'order'];
        foreach ($unset_array as $unset_group) {
            unset($checkout_fields[$unset_group]);
        }
        foreach (self::project_checkout_billing_fields_array as $project_field => $project_values) {
            $checkout_fields['billing'][$project_field] = $project_values;
        }
        return $checkout_fields;
    }

    /**
     * Add custom fields
     * @param $checkout
     * @return void
     */
    public function project_add_checkout_field($checkout) {
//      $current_user = wp_get_current_user();
        foreach (self::$project_checkout_additional_fields_array as $project_additional_field => $project_values){
            woocommerce_form_field($project_additional_field, $project_values, $checkout->get_value( $project_additional_field ));
        }
    }

    /**
     * Save custom fields
     * @param $order_id
     * @return void
     */
    public function project_save_custom_checkout_field($order_id) {
        foreach(self::$project_checkout_additional_fields_array as $field_name => $values) {
            if ( ! empty($_POST[$field_name]) ) {
                $order = wc_get_order( $order_id );
                $order->update_meta_data( $field_name, sanitize_text_field( $_POST[$field_name] ) );
                $order->save_meta_data();
            };
        }
    }

    /**
     * Show custom fields
     * @param $order
     * @return void
     */
    public function project_show_custom_checkout_field_order($order) {
        foreach(self::$project_checkout_additional_fields_array as $field_name => $values) {
            $order_id = $order->get_id();
            if ( get_post_meta( $order_id, '_' . $field_name, true ) ) {
                echo '<p><strong>' . $values['label'] . ':</strong> ' . get_post_meta( $order_id, '_' . $field_name, true ) . '</p>';
            }
        }
    }

    public function project_field_filter($field, $key, $args, $value) {
        if ($key === 'profile_membership_request_date') {
            $msg = 'Members must sign that they will read the member’s handbook and agree to be bound by the policies, rules and regulations of the Colby Shooting Club. I understand that my membership may be cancelled, without refund, should I breach the rules and by-laws of the club.';
            $field = '<div class="project_before_date checkout__info form-row-wide">'. $msg . '</div>' . $field;
        }
        if ($key === 'profile_att_club_name') {
            $msg = 'Family memberships must complete this section.';
            $field .= '<div class="project_after_if_yes_club checkout__info form-row-wide">'. $msg . '</div>';
        }
        return $field;
    }

    public function add_signature_block( $post ) {
        $signature_width  = get_option( 'signature_width', 300 );
        $signature_height = get_option( 'signature_height', 200 );

        ?>
        </div>
        <div class="woocommerce-additional-fields">
        <p class="form-row form-row-first validate-required validate-signature">
            <label><?php echo esc_html('Draw Signature','digital-signature-checkout-for-woocommerce'); ?></label>

            <canvas id="dscfw_sign" name="signaturefield" width="<?php echo esc_attr($signature_width); ?>" height="<?php echo esc_attr($signature_height); ?>" validate-required></canvas>

            <input class="no-update-fields clearButton" type="button" style="display: block; position: relative; transform: none; width: auto; top: auto; right: auto; height: auto" value="&times; Clear">
            <input type="hidden" name="signpad" value="">
        </p>
        <div class="project_before_signature checkout__info form-row-wide">Membership fees are due in April. Cheques should be made payable to Colby Shooting Club. Keys will be cancelled for membership fees not paid by May 15th and members will have to pay an additional $35.00 initiation fee.</div>

        <?php
    }
}

