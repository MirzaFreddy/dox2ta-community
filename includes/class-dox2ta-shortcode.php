<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dox2ta_Shortcode {
    public function register(){
        add_shortcode( 'dox2ta_community', [ $this, 'render' ] );
        add_action( 'wp_ajax_dox2ta_join', [ $this, 'ajax_join' ] );
        add_action( 'wp_ajax_nopriv_dox2ta_join', [ $this, 'ajax_join' ] );
    }

    private function ensure_assets(){
        if ( ! wp_script_is( 'dox2ta-community', 'registered' ) ) return;
        wp_enqueue_style( 'dox2ta-community' );
        wp_enqueue_script( 'dox2ta-community' );
        wp_localize_script( 'dox2ta-community', 'Dox2taCommunity', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'dox2ta_join_nonce' ),
            'loggedIn'=> is_user_logged_in(),
            'texts'   => [
                'joined' => __( 'شما عضو شماره %s کامیونیتی دودوتا هستید!', 'dox2ta-community' ),
                'loginRequired' => __( 'برای عضویت در کامیونیتی لطفاً وارد شوید.', 'dox2ta-community' ),
            ]
        ] );
    }

    public function render( $atts = [], $content = '' ){
        $this->ensure_assets();
        ob_start();
        $template = DOX2TA_COMMUNITY_PATH . 'templates/shortcode.php';
        if ( file_exists( $template ) ) {
            include $template;
        } else {
            echo '<div class="dox2ta-community">قالب شورت‌کد یافت نشد.</div>';
        }
        return ob_get_clean();
    }

    public function ajax_join(){
        if ( ! check_ajax_referer( 'dox2ta_join_nonce', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => __( 'اعتبارسنجی امنیتی ناموفق بود.', 'dox2ta-community' ) ], 403 );
        }

        if ( ! is_user_logged_in() ) {
            wp_send_json_error( [ 'message' => __( 'برای ادامه لازم است وارد شوید.', 'dox2ta-community' ) ], 401 );
        }

        $user_id = get_current_user_id();
        global $wpdb;
        $table = Dox2ta_Activator::table_name();

        // Try to insert if not exists
        $existing = $wpdb->get_row( $wpdb->prepare( "SELECT id, join_number FROM {$table} WHERE user_id = %d", $user_id ) );
        if ( $existing ) {
            wp_send_json_success( [ 'join_number' => intval( $existing->join_number ?: $existing->id ) ] );
        }

        $now = current_time( 'mysql', 1 );
        $inserted = $wpdb->insert( $table, [
            'user_id'   => $user_id,
            'joined_at' => get_date_from_gmt( $now ),
        ], [ '%d', '%s' ] );

        if ( ! $inserted ) {
            // In case of race condition unique user, fetch again
            $existing = $wpdb->get_row( $wpdb->prepare( "SELECT id, join_number FROM {$table} WHERE user_id = %d", $user_id ) );
            if ( $existing ) {
                wp_send_json_success( [ 'join_number' => intval( $existing->join_number ?: $existing->id ) ] );
            }
            wp_send_json_error( [ 'message' => __( 'در حال حاضر امکان عضویت وجود ندارد.', 'dox2ta-community' ) ], 500 );
        }

        $id = intval( $wpdb->insert_id );
        // Use auto-increment ID as stable join number
        $wpdb->update( $table, [ 'join_number' => $id ], [ 'id' => $id ], [ '%d' ], [ '%d' ] );

        wp_send_json_success( [ 'join_number' => $id ] );
    }
}
