<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function add_apartment_f()
{


	
	?>
	
	<div class="wrap">
	
	<?php
	
	global $table_prefix, $wpdb;
	
	$plugin_dir_url =  plugin_dir_url( __FILE__ );
	
	if( !empty( $_POST['submit'] ) && sanitize_text_field( $_POST['submit'] ) && current_user_can( 'manage_options' ) )
	{
		
		$nonce_check = sanitize_text_field( $_POST['_wpnonce_add_apart_form'] );
	
		if ( ! wp_verify_nonce( $nonce_check, 'add_apart_form' ) ) 
		{
			
			die(  'Security check failed'  ); 
			
		}
		
		$apartment = sanitize_text_field( $_POST['apartment'] );
		
		$city = sanitize_text_field( $_POST['city'] );
		
		$state = sanitize_text_field( $_POST['state'] );
		
		$dod = sanitize_text_field( $_POST['dod'] );

		

		$safe_apart=  sanitize_text_field($apartment);
		
		$safe_dod = intval( $dod );
		
		if (  $safe_apart && $safe_dod )
		{	
	
			$num_rows = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM `".$table_prefix."check_apart_p` where `apartment` = %s", $apartment ) );

			if($num_rows == 0)

			{

				$result = $wpdb->query( $wpdb->prepare( "INSERT INTO `".$table_prefix."check_apart_p` SET `apartment` = %s , `city` = %s , `state` = %s , `dod` = %d  ,`disable` = %s" , $apartment, $city, $state, $dod, $disable) );
				
				if($result == 1)
				{
				?>

					<div class="updated below-h2" id="message"><p><?php esc_html_e('Added Successfully.','pho-apart-cod'); ?></p></div>

				<?php
				}
				else
				{
					?>
						<div class="error below-h2" id="message"><p> <?php esc_html_e('Something Went Wrong Please Try Again With Valid Data.','pho-apart-cod'); ?></p></div>
					<?php
					
				}
			}
			else
			{
				?>

					<div class="error below-h2" id="message"><p> <?php esc_html_e('This Appartment Already Exists.','pho-apart-cod'); ?></p></div>

				<?php
			}
		}
		else
		{
			?>

				<div class="error below-h2" id="message"><p> <?php esc_html_e('Please Fill Valid Data.','pho-apart-cod'); ?></p></div>

			<?php
		}
	}
	?>
			<div id="icon-users" class="icon32"><br/></div>
<?php
if( isset( $_GET['tab'] ) ) {
	
	$tab = sanitize_text_field( $_GET['tab'] );
	
}
else
{
	$tab = '';
}
?>
			<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
			<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
					<a class="nav-tab <?php if($tab == 'add' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=add_apartment&amp;tab=add"><?php esc_html_e('Add Appartment','pho-apart-cod'); ?></a></h2>	
            <?php
if($tab == 'add' || $tab == '')
{
?>	

<h2><?php esc_html_e('Add Apartment','pho-apart-cod'); ?></h2>
			
				<form action="" method="post" id="azip_form" name="azip_form">
				
				<?php $nonce = wp_create_nonce( 'add_apart_form' ); ?>
							
				<input type="hidden" value="<?php echo $nonce; ?>" name="_wpnonce_add_apart_form" id="_wpnonce_add_apart_form" />

					<table class="form-table">

					<tbody>

						<tr class="user-user-login-wrap">

							<th><label for="user_login"><?php esc_html_e('Apartment','pho-apart-cod'); ?></label></th>

							<td><input type="text"  pattern="[a-zA-Z0-9\s]+" required="required" class="regular-text" id="apartment" name="apartment"></td>

						</tr>

						<tr class="user-first-name-wrap">

							<th><label for="first_name"><?php esc_html_e('City','pho-apart-cod'); ?></label></th>

							<td><input type="text" required="required" class="regular-text" id="city" name="city"></td>

						</tr>

						<tr class="user-last-name-wrap">

							<th><label for="last_name"><?php esc_html_e('State','pho-apart-cod'); ?></label></th>

							<td><input type="text" required="required" class="regular-text" id="state" name="state"></td>

						</tr>

						<tr class="user-nickname-wrap">

							<th><label for="nickname"><?php esc_html_e('Delivery within days','pho-apart-cod'); ?></label></th>

							<td><input type="number" min="1" max="365" step="1" value="1" class="regular-text" id="dod" name="dod"></td>

						</tr>

						<tr>
						<td><input type="checkbox" name="check[0]" value="0"  />Disable</td>
    					<td><input type="checkbox" name="check[1]" value="1" />Enable</td>



					</tbody>

				</table>

					<p class="submit"><input type="submit" value="Add" class="button button-primary" id="submit" name="submit"></p>

			</form>
			
			<style>
						.pho-upgrade-btn > a:focus {
							box-shadow: none !important;
						}
			</style>


<?php
}
?>

<?php
    // if data is posted, set value to 1, else to 0
    $check_0 = isset($_POST['check'][0]) ? 1 : 0;
    $check_1 = isset($_POST['check'][1]) ? 1 : 0;
?>

<script type="text/javascript">
    // when page is ready
    $(document).ready(function() {
         // on form submit
        $("#form").on('submit', function() {
            // to each unchecked checkbox
            $(this).find('input[type=checkbox]:not(:checked)').prop('checked', true).val(0);
        })
    })
</script>

<script>

function alphaOnly(event) {
  var key = event.keyCode;
  // alert(key);
  return ((key >= 65 && key <= 90) || key == 8 || key==32);
};

jQuery('.id-select-all-1').click(function() {

    if (jQuery(this).is(':checked')) {

        jQuery('div input').attr('checked', true);

    } else {

        jQuery('div input').attr('checked', false);

    }

});



<?php
}
?>