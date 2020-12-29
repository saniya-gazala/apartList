<?php
/*
Plugin Name: Wolv Plugin
Plugin URI: 
Description: Wolve Plugin for wordpress
Author: Wolv
Author URI: http://youtube.com/microtechtutorials
Version: 0.1
*/

//to secure a plugin when wordpress is not initialized  basically used for safety 

//default method addeding Abosulte Path (ABSPATH)

if(!defined('ABSPATH')){   //if absoulte path is not define kill the plugin activit or it doesnt get installed .
        die;
}


/**

** Check if WooCommerce is active
 
**/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
	include(dirname(__FILE__).'/libs/execute-libs.php');

	function apartment_settings_link($links) {
	
		  $settings_link = '<a href="admin.php?page=apartment_setting">Settings</a>'; 
		  
		  array_unshift($links, $settings_link); 
		  
		  return $links; 
		  
    }
    $plugin = plugin_basename(__FILE__);

    add_filter("plugin_action_links_$plugin", 'apartment_settings_link' ); //for plugin setting link

    function apartment_setting() {

		require_once(dirname(__FILE__).'/admin-setting.php');
		
    } 
    
    function phoen_adpanel_style3() {

		?>
			<script>
				var blog_title = '<?php echo plugin_dir_url(__FILE__); ?>';
				var usejs = 0;
			</script>
		<?php
	
		wp_enqueue_style( 'apartcheck-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
		
		// embed the javascript file that makes the AJAX request
		
		wp_enqueue_script( 'apartcheck-ajax-request', plugin_dir_url( __FILE__ ) . '/assets/js/custom.js', array( 'jquery' ) );

		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		
		wp_localize_script( 'apartcheck-ajax-request', 'apart_check', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

    }
    
    add_action('wp_head', 'phoen_adpanel_style3'); //for adding assets/js/css in wp head
	
	function phoen_adpanel_style4() {
	
		wp_enqueue_style( 'apartcheck-css', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css' );
		
		wp_enqueue_script( 'apartcheck-ajax-request', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js', array( 'jquery' ) );
		
		?>
		
			<script>
			
				var usejs = 0;
				
			</script>
			
		<?php
		
    }
    


    add_action('admin_head', 'phoen_adpanel_style4'); //for adding assets/js/css in wp head

	//Activation Code of table in wordpress

	register_activation_hook(__FILE__, 'apart_plugin_activation');

	function apart_plugin_activation() {

		global $table_prefix, $wpdb;

		$tblname = 'check_apart_p';

        $wp_track_members_table = $table_prefix . "$tblname";
        

        #Check to see if the table exists already, if not, then create it

		if($wpdb->get_var( "show tables like '$wp_track_members_table'" ) != $wp_track_members_table) 
		{

			$sql0  = "CREATE TABLE `". $wp_track_members_table . "` ( ";

			$sql0 .= "  `id`  int(11)   NOT NULL auto_increment, ";

			

			$sql0 .= "  `apartment`  varchar(250)   NOT NULL, ";

			$sql0 .= "  `city`  varchar(250)   NOT NULL, ";

			$sql0 .= "  `state`  varchar(250)   NOT NULL, ";

			$sql0 .= "  `dod`  int(11)   NOT NULL, ";

			$sql0 .= "  `cod`  varchar(250)   NOT NULL DEFAULT 'no', ";

			$sql0 .= "  `disable`  tinyint(1) NOT  NULL, ";

			$sql0 .= "  PRIMARY KEY `order_id` (`id`) "; 

			$sql0 .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";

			#We need to include this file so we have access to the dbDelta function below (which is used to create the table)

			require_once(ABSPATH . '/wp-admin/upgrade-functions.php');

			dbDelta($sql0);

        }
        
        $table_name = $wpdb->prefix . 'apart_setting_p';
            #Check to see if the table exists already, if not, then create it

		if($wpdb->get_var( "show tables like '$table_name'" ) != $table_name) 
		{
			$sql = "CREATE TABLE $table_name (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`del_help_text` text NOT NULL,
			`cod_help_text` text NOT NULL, 
			`cod_msg1` text NOT NULL, 
			`cod_msg2` text NOT NULL, 
			`error_msg` text NOT NULL,
			`del_date` int(11) NOT NULL, 
			`cod` int(11) NOT NULL,
			`s_s` int(11) NOT NULL, 
			`s_s1` int(11) NOT NULL, 
			`cod_p` int(11) NOT NULL,
			`delv_by_cart` int(11) NOT NULL,
			`val_checkout` int(11) NOT NULL,
			`bgcolor` varchar(250) NOT NULL, 
			`textcolor` varchar(250) NOT NULL, 
			`bordercolor` varchar(250) NOT NULL, 
			`buttoncolor` varchar(250) NOT NULL, 
			`buttontcolor` varchar(250) NOT NULL, 
			`ttbordercolor` varchar(250) NOT NULL, 
			`ttbagcolor` varchar(250) NOT NULL, 
			`tttextcolor` varchar(250) NOT NULL, 
			`devbytcolor` varchar(250) NOT NULL, 
			`codtcolor` varchar(250) NOT NULL, 
			`datecolor` varchar(250) NOT NULL, 
			`codmsgcolor` varchar(250) NOT NULL, 
			`errormsgcolor` varchar(250) NOT NULL, 
			`image_size` varchar(250) NOT NULL, 
			`image_size1` varchar(250) NOT NULL, 
			`tt_c_image_size` varchar(250) NOT NULL, 
			`tt_c_image_size1` varchar(250) NOT NULL, 
			`help_image` text NOT NULL, 
			`tt_c_image` text NOT NULL, 
			`date_time` DATETIME NULL,
			PRIMARY KEY id (id));";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			
			dbDelta( $sql );
			
			$rows_affected = $wpdb->insert( $table_name, array('del_help_text' => 'Delivery Date Help Text', 'bgcolor' => '#f4f2f2', 'textcolor' => '#737070', 'buttoncolor' => '#a46497', 'buttontcolor' => '#ffffff'));

			dbDelta( $rows_affected );
		}
    }
    

    require_once(dirname(__FILE__).'/list_apart.php');

	require_once(dirname(__FILE__).'/add_apart.php');
	
	add_action( 'admin_menu', 'register_my_custom_menu_page' ); //for admin menu

	function register_my_custom_menu_page() {
        
        $plugin_dir_url =  plugin_dir_url( __FILE__ );

		add_menu_page(__('Apartment','disp-test'), __('Apartment','disp-test'), 'manage_options' , 'add_apartment' , '' , "$plugin_dir_url/assets/img/page_white_zip.png" , '6');

		add_submenu_page('add_apartment', __('Add Apartment','displ-test'), __('Add Apartment','displ-test'), 'manage_options', 'add_apartment', 'add_apartment_f');

		add_submenu_page('add_apartment', __('Apartment List','displ-test'), __('Apartment List','displ-test'), 'manage_options', 'list_apartment', 'list_apartment_f');

		add_submenu_page('add_apartment', __('Setting','displ-test'), __('Settings','displ-test'), 'manage_options', 'apartment_setting', 'apartment_setting');
		
	}



	


	add_action( 'woocommerce_after_order_notes', 'checkout_page_function' ); //for checkout page functionality

	function checkout_page_function() {
		
		global $table_prefix, $wpdb, $woocommerce;
		
		$blog_title = site_url();
		
		if( isset( $_COOKIE['valid_apartment'] ) ) {
			$cookie_pin = isset($_COOKIE['valid_apartment'])?sanitize_text_field( $_COOKIE['valid_apartment'] ):'';
			// $cookie_pin = $_COOKIE['valid_pincode'];
			
		}
		else
		{
			$cookie_pin = '';
		}

		if(isset($cookie_pin))
		{		
	
			$customer = new WC_Customer();
			//give the meta key here for apartment 

			$customer->set_shipping_postcode($cookie_pin);
			
			$user_ID = get_current_user_id();
			
			$current_pcode = get_user_meta($user_ID, 'shipping_postcode');
			
			$customer = new WC_Customer();
			
			if(isset($user_ID) && $user_ID != 0)
			{
				update_user_meta($user_ID, 'shipping_postcode', $cookie_pin);
				
				if($current_pcode[0] != $cookie_pin)
				{
					
					header("Refresh:0");
				}
			}
			
			
		}
		
	
	}
	//end of checkout page function 

	// if both logged in and not logged in users can send this AJAX request,
	// add both of these actions, otherwise add only the appropriate one
	add_action( 'wp_ajax_nopriv_apartcheck_ajax_submit', 'apartcheck_ajax_submit' );
	add_action( 'wp_ajax_apartcheck_ajax_submit', 'apartcheck_ajax_submit' );

	function apartcheck_ajax_submit() {
		// get the submitted parameters
		global $table_prefix, $wpdb;
		
		$apartcode = sanitize_text_field( $_POST['apart_code'] );
		
		$safe_zipcode =  $apartcode ;
		
		if($safe_zipcode)
		{
			$table_apart_codes = $table_prefix."check_apart_p";
			
			$count = $wpdb->get_var( $wpdb->prepare( "select COUNT(*) from $table_apart_codes where `apartment` = %s" , $apartcode ) );
			
			if($count==0)
			{

			   echo "0";  

			}
			else
			{
				setcookie("valid_apartment",$apartcode,time() + (10 * 365 * 24 * 60 * 60),"/");
				
				echo "1";
			}
		}
		else
		{
			echo "0";
		}

		// IMPORTANT: don't forget to "exit"
		exit;
	}


	add_action('wp_head','hook_css'); //for adding dynamic css in wp head
    
    function hook_css() {
		
		global $table_prefix, $wpdb, $woocommerce;
		
		$blog_title = site_url();
		
		$qry22 = $wpdb->get_results( "SELECT * FROM `".$table_prefix."apart_setting_p` ORDER BY `id` ASC  limit 1" ,ARRAY_A);	
		
		$bgcolor =  $qry22[0]['bgcolor'];
		
		$textcolor =  $qry22[0]['textcolor'];
				
		$buttoncolor = $qry22[0]['buttoncolor'];
		
		$buttontcolor = $qry22[0]['buttontcolor'];

	}
    


}