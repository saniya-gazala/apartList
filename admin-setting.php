<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb,$table_prefix;

$plugin_dir_url =  plugin_dir_url( __FILE__ );

wp_enqueue_script('wp-color-picker'); //for color picker scripts

wp_enqueue_style( 'wp-color-picker' );

wp_enqueue_media();  //for upload media scripts

/* Form Post Data */

if( isset( $_POST['submit'] ) ) {
		
	$submit =sanitize_text_field( $_POST['submit'] );
	
}else
{
	$submit = '';
}

if( sanitize_text_field( $submit ) == 'Save'  && current_user_can( 'manage_options' ) ) {
	
	$nonce_check = sanitize_text_field( $_POST['_wpnonce_check_apartment_setting'] );
	
	if ( ! wp_verify_nonce( $nonce_check, 'check_apartment_setting' ) ) 
	{
		
		die( 'Security check failed' ); 
		
	}
	else 
	{
		
		$del_help_text = sanitize_text_field( $_POST['del_help_text'] );

		$del_date = sanitize_text_field( $_POST['del_date'] );

		$bgcolor = sanitize_text_field( $_POST['bgcolor'] );

		$textcolor = sanitize_text_field( $_POST['textcolor'] );

		$buttoncolor = sanitize_text_field( $_POST['buttoncolor'] );

		$buttontcolor = sanitize_text_field( $_POST['buttontcolor'] );

		/* Database Queries */
		
		$adddate = date('Y-m-d H:i:s');
		
		//echo "SELECT COUNT(*) FROM `".$table_prefix."pincode_setting_p`";
		
		$num_rows = $wpdb->get_var( "SELECT COUNT(*) FROM `".$table_prefix."apart_setting_p`" );

		//echo $num_rows;
		
		if($num_rows == 0)

		{
		
			$result = $wpdb->query( $wpdb->prepare( "INSERT INTO `".$table_prefix."apart_setting_p` SET `del_help_text` = %s, `del_date` = %s, `bgcolor` = %s, `textcolor` = %s, `buttoncolor` = %s, `buttontcolor` = %s,`date_time` = %s" , $del_help_text, $del_date, $bgcolor, $textcolor, $buttoncolor, $buttontcolor,$adddate ) );
		
		}
		
		else
		{
			$result = $wpdb->query( $wpdb->prepare( "UPDATE `".$table_prefix."apart_setting_p` SET `del_help_text` = %s, `del_date` = %s, `bgcolor` = %s, `textcolor` = %s, `buttoncolor` = %s, `buttontcolor` = %s,`date_time` = %s" , $del_help_text, $del_date, $bgcolor, $textcolor, $buttoncolor, $buttontcolor,$adddate ) );
		
		}
			
		if( $result > 0 )
		{
		?>

			<div class="updated" id="message">

				<p><strong><?php esc_html_e('Setting updated.','pho-apart-cod'); ?></strong></p>

			</div>

		<?php
		}
		else
		{
			?>
				<div class="error below-h2" id="message"><p> <?php esc_html_e('Something Went Wrong Please Try Again With Valid Data.','pho-apart-cod'); ?></p></div>
			<?php
		}
		
	}

}


/* Fetching Data From DB */

$qry22 = $wpdb->get_results( "SELECT * FROM `".$table_prefix."apart_setting_p` ORDER BY `id` ASC  limit 1",ARRAY_A );	

foreach($qry22 as $qry) {

}

?>

?>


<?php

if( isset( $_GET['tab'] ) ) {
	
	$tab = sanitize_text_field( $_GET['tab'] );
	
}
else
{
	$tab = '';
}

?>
<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
		<a class="nav-tab <?php if($tab == 'set' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=apartment_setting&amp;tab=set"><?php esc_html_e('Settings','pho-apart-cod'); ?></a>
		
		
</h2>
<?php
if($tab == 'set' || $tab == '')
{
?>
<h2><?php esc_html_e('WooCommerce Pincode Check - Plugin Options','pho-apart-cod'); ?></h2>

<form novalidate="novalidate" method="post" action="" >

<h3><?php esc_html_e('Manual Settings','pho-apart-cod'); ?></h3>

<?php $nonce = wp_create_nonce( 'check_apartment_setting' ); ?>
							
<input type="hidden" value="<?php echo $nonce; ?>" name="_wpnonce_check_apartment_setting" id="_wpnonce_check_apartment_setting" />


<table class="form-table">

	<tbody>

		<tr class="user-user-login-wrap">

			<th><label for="del_help_text"><?php esc_html_e('Delivery Date Help Text','pho-apart-cod'); ?></label></th>
			
			<td><textarea class="regular-text" id="del_help_text" name="del_help_text"><?php echo $qry['del_help_text']; ?></textarea></td>

		</tr>

		

	</tbody>

</table>

<table class="form-table">

	<tbody>

		<h3><?php esc_html_e('Enable Help Text','pho-apart-cod'); ?></h3>

		<tr class="user-nickname-wrap">

			<th><label for="del_date"><?php esc_html_e('Delivery Date','pho-apart-cod'); ?></label></th>

			<td><label for="del_date"><input type="radio" <?php if($qry['del_date'] == 1) { ?> checked <?php } ?> name="del_date" value="1"><?php esc_html_e('ON','pho-apart-cod'); ?></label>

			<label for="del_date"><input type="radio" <?php if($qry['del_date'] == 0) { ?> checked <?php } ?> name="del_date" value="0"><?php esc_html_e('OFF','pho-apart-cod'); ?></label></td>

		</tr>

	</tbody>

</table>

<table class="form-table">

<tbody>

<h3><?php esc_html_e('Styling of Check Pincode Functionality on Product Page','pho-apart-cod'); ?></h3>


	<tr class="user-user-login-wrap">

			<th><label for="bgcolor"><?php esc_html_e('Box Background color','pho-apart-cod'); ?></label></th>

			<td><input type="text" class="regular-text" value="<?php echo $qry['bgcolor']; ?>" id="bgcolor" name="bgcolor"></td>

		</tr>


		<tr class="user-first-name-wrap">

			<th><label for="textcolor"><?php esc_html_e('Check Pincode Label Text Color','pho-apart-cod'); ?></label></th>

			<td><input type="text" class="regular-text" value="<?php echo $qry['textcolor']; ?>" id="textcolor" name="textcolor"></td>

		</tr>


		<tr class="user-last-name-wrap">

			<th><label for="buttoncolor"><?php esc_html_e('"Check" Button Color','pho-apart-cod'); ?></label></th>

			<td><input type="text" class="regular-text" value="<?php echo $qry['buttoncolor']; ?>" id="buttoncolor" name="buttoncolor"></td>

		</tr>
		
		
		<tr class="user-last-name-wrap">

			<th><label for="buttontcolor"><?php esc_html_e('"Check" Button Text Color','pho-apart-cod'); ?></label></th>

			<td><input type="text" class="regular-text" value="<?php echo $qry['buttontcolor']; ?>" id="buttontcolor" name="buttontcolor"></td>

		</tr>
		

</tbody>

</table>		

<p class="submit"><input type="submit" value="Save" class="button button-primary" id="submit" name="submit"></p>

</form>



<?php
} //else if shld be written 
?>			
</div>

<script>

jQuery(document).ready(function($) {

	jQuery("#bgcolor").wpColorPicker();

	jQuery("#textcolor").wpColorPicker();

	jQuery("#buttoncolor").wpColorPicker();
	
	jQuery("#buttontcolor").wpColorPicker();
	
});

</script>


<style>
.form-table th {
    width: 270px;
	padding: 25px;
}
.form-table td {
	
    padding: 20px 10px;
}
.form-table {
	background-color: #fff;
}
h3 {
    padding: 10px;
}

.pho-upgrade-btn > a:focus {
							box-shadow: none !important;
						}
</style>