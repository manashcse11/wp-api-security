<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Wp_Api_Security
 * @subpackage Wp_Api_Security/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Api_Security
 * @subpackage Wp_Api_Security/admin
 * @author     Manash Kumar Chakrobortty <manash.pstu@gmail.com>
 */
class Wp_Api_Security_Admin {

    protected $table_name;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $wp_api_security    The ID of this plugin.
	 */
	private $wp_api_security;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $wp_api_security       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $wp_api_security, $version ) {

        global $wpdb;
		$this->wp_api_security = $wp_api_security;
		$this->version = $version;

        $this->table_name = $wpdb->prefix . "wp_api_security";

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Api_Security_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Api_Security_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->wp_api_security, plugin_dir_url( __FILE__ ) . 'css/wp-api-security-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Api_Security_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Api_Security_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->wp_api_security, plugin_dir_url( __FILE__ ) . 'js/wp-api-security-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function wp_api_security_admin_menu() {
        add_menu_page( 'WP API Security', 'WP API Security', 'wp_api_security', 'wp-api-security', array($this, 'wp_api_security_home'));
    }

    public function message_formatter($class, $message){
        echo "<div class='$class'>
            <p>$message</p>
        </div>";
    }

    public function show_message()
    {
        if ("success" == $_GET['message']) {
            $this->message_formatter("notice notice-success is-dismissible", "API Configuration saved successfully!");
        }

        if ("fail" == $_GET['message']) {
            $this->message_formatter("notice notice-error is-dismissible", "Already Exist!");
        }

        if ("fail_delete" == $_GET['message']) {
            $this->message_formatter("notice notice-error is-dismissible", "Unable to delete!");
        }

        if ("success_delete" == $_GET['message']) {
            $this->message_formatter("notice notice-success is-dismissible", "Deleted Successfully!");
        }
    }

    public function wp_api_security_home() {
        ?>
        <div class="wrap">
	       	<h1>WP API Security&nbsp</h1>
       	</div>
        <hr/>
        <div class="wrap">
            <div class="delete_form">
                <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
                    <input type="hidden" name="action" value="wp_api_security_delete_action">
                    <input type="hidden" name="delete_id" id="delete_id"/>
                    <input id="delete_submit" type="submit" name="submit" hidden>
                </form>
            </div>
            <?php $this->show_message(); ?>
            <h3>Add New API Configuration</h3>
            <form id="restricted_api" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
                <input type="hidden" name="action" value="wp_api_security_configuration_form">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="api">API</label></th>
                        <td>
                            <input type="hidden" name="id" id="id"/>
                            <input type="text" name="api" id="api" class="regular-text" required/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="api_description">API Description</label></th>
                        <td>
                            <input type="text" name="api_description" id="api_description" class="regular-text" required/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="api_key">API Key</label></th>
                        <td>
                            <input type="text" name="api_key" id="api_key" class="regular-text" required/>
                            <a href="javascript:;" class="generate_key">Generate New Key</a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="ip">IP</label></th>
                        <td>
                            <input type="text" name="ip" id="ip" class="regular-text"/>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <h3 class="handle">Restricted API List</h3>
            <table id="restricted-api" class="widefat">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="font-weight: bold;">API</th>
                        <th style="font-weight: bold;">API Description</th>
                        <th style="font-weight: bold;">API Key</th>
                        <th style="font-weight: bold;">IP</th>
                        <th style="font-weight: bold;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $restricted_apis = $this->get_restricted_api_list();
                    $i = 1;
                    foreach( $restricted_apis as $ra ) {
                        echo "<tr>
                                    <td>$i</td>
                                    <td>$ra->api</td>
                                    <td>$ra->api_description</td>
                                    <td>$ra->api_key</td>
                                    <td>$ra->ip</td>
                                    <td data-id='$ra->id'>
                                        <a href='javascript:;' class='edit'>Edit</a> | <a href='javascript:;' class='delete'>Delete</a> 
                                    </td>
                                </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

    <?php
    }

    public function get_restricted_api_list(){
	    global $wpdb;
        return $wpdb->get_results( "SELECT * FROM `$this->table_name`" );
    }

    public function wp_api_security_configuration_form(){
        $id = isset($_POST['id']) ? $_POST['id'] : "";
        $api = isset($_POST['api']) ? $_POST['api'] : "";
        $api_description = isset($_POST['api_description']) ? $_POST['api_description'] : "";
        $api_key = isset($_POST['api_key']) ? $_POST['api_key'] : "";
        $ip = isset($_POST['ip']) ? $_POST['ip'] : "";
        $message = "fail";
        if($api && $api_key){
            global $wpdb;
            $sql = "INSERT INTO $this->table_name SET api = '$api', api_description = '$api_description', api_key = '$api_key', ip = '$ip'"; // Insert
            if($id){ // Edit
                $sql = "UPDATE $this->table_name SET api = '$api', api_description = '$api_description', api_key = '$api_key', ip = '$ip' WHERE id = $id";
            }
            $wpdb->query( $sql );
            $message = "success";
        }
        wp_redirect( 'network/admin.php?page=wp-api-security&message=' . $message );
        exit;
    }

    public function wp_api_security_delete_action(){
        $delete_id = isset($_POST['delete_id']) ? $_POST['delete_id'] : "";
        $message = "fail_delete";
        if($delete_id){
            global $wpdb;
            $wpdb->query( "DELETE FROM `$this->table_name` WHERE id = $delete_id" );
            $message = "success_delete";
        }
        wp_redirect( 'network/admin.php?page=wp-api-security&message=' . $message );
        exit;
    }

    /** Authenticate API  */
    public function wp_api_auth_user_has_capability() {
        $found = false;
        $user = wp_get_current_user();
        $user_roles = array('author','editor','contributor','subscriber','administrator');
        foreach($user->caps as $caps)
            $found[$caps] = in_array($caps, $user_roles) ? true : false;
        return $found;
    }

    public function respond_unauthorized_message() {
        $response = array(
            'status' => "error",
            'error' => 'unauthorized',
            'error_description' => 'Invalid Request.'
        );
        wp_send_json( $response, 401 );
    }

    public function is_restrict_api($url, $api_key = '', $ip = ''){
        global $wpdb;
        $sql = "SELECT * FROM `$this->table_name` WHERE api LIKE '$url'";
        if($api_key){
            $sql .= " AND api_key = '$api_key'";
        }
        if($ip){
            $sql .= " AND ip LIKE '%$ip%'";
        }
        return $wpdb->get_row( $sql );
    }

    public function is_request_authorized(){
        $headers = getallheaders();
        if( isset( $headers['Authorization'] ) && $headers['Authorization'] !== "" ) {
            $authorization_header = explode( " ", $headers['Authorization'] );
            if( isset( $authorization_header[0] ) && (strcasecmp( $authorization_header[0], 'Bearer' ) == 0 ) && isset( $authorization_header[1] ) && $authorization_header[1] !== "" ) {
                $api_key = $authorization_header[1];
                $authorized = $this->is_restrict_api(strtok($_SERVER["REQUEST_URI"],'?'), $api_key, $_SERVER['REMOTE_ADDR']);
                if( $authorized ) {
                    return true;
                } else {
                    // echo 'Your token has been expired or you are using invalid Token.';
                    return false;
                }
            } else {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function wp_authenticate_api(){
        if ( !$this->wp_api_auth_user_has_capability() ) {
            $restrict = $this->is_restrict_api(strtok($_SERVER["REQUEST_URI"],'?'));
            if($restrict) {
                if(!$this->is_request_authorized()){
                    $this->respond_unauthorized_message();
                }
            }
        }
    }

    /** END */
}
