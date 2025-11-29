<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dox2ta_Activator {
    public static function activate(){
        global $wpdb;
        $table = self::table_name();
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            join_number BIGINT UNSIGNED DEFAULT NULL,
            joined_at DATETIME NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY user_id (user_id),
            KEY joined_at (joined_at)
        ) {$charset_collate};";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    public static function table_name(){
        global $wpdb;
        return $wpdb->prefix . 'dox2ta_members';
    }
}
