<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Api_Security
 * @subpackage Wp_Api_Security/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Api_Security
 * @subpackage Wp_Api_Security/includes
 * @author     Manash Kumar Chakrobortty <manash.pstu@gmail.com>
 */
class Wp_Api_Security_Activator {
    /**
     * Database table version for bloggers
     * @var string
     */
    protected static $wp_api_security_table_version = '1.0';

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $arr_sqls = array();

        $table_name = $wpdb->prefix . "wp_api_security";

        if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
            $arr_sqls[] = "CREATE TABLE $table_name (
				  id int(11) NOT NULL AUTO_INCREMENT,
                  api varchar(300) NOT NULL,
                  
                  api_description varchar(300) NOT NULL,
                  api_key varchar(300) NOT NULL,
                  ip varchar(300) NOT NULL,
				  PRIMARY KEY  (id)
			) $charset_collate;";
        }

        if( !empty($arr_sqls) ) {
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $arr_sqls );
        }

        add_option( 'wp_api_security_table_version', self::$wp_api_security_table_version );
	}

}
