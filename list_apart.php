<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $table_prefix, $wpdb;

if(!empty( $_GET['action'] ) && sanitize_text_field( $_GET['action'] ) == 'delete')

{
	
	$id =  isset($_GET['id'])?sanitize_text_field($_GET['id'] ):'';
	
	if( isset($id) )

	{
		
		$wpdb->query( $wpdb->prepare( "DELETE FROM `".$table_prefix."check_apart_p` WHERE `id` = %s", $id ) );

	}

	$ids = isset($_GET['apartment'])?$_GET['apartment']:'';


	if( isset($ids) && count($ids) >0)
	{

		$count = count($ids);

		for($i=0;$i<$count;$i++)

		{

			$_id = isset($ids[$i])?$ids[$i]:'';

			$wpdb->query( $wpdb->prepare( "DELETE FROM `".$table_prefix."check_apart_p` WHERE `id` = %s ", $_id ) );

		}

	}

}





if(!class_exists('WP_List_Table')){

    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

}


class TT_Example_List_Tablee extends WP_List_Table {

    function __construct(){

        global $status, $page;

        //Set parent defaults

        parent::__construct( array(

            'singular'  => 'Apartment',     //singular name of the listed records

            'plural'    => 'Apartment',    //plural name of the listed records

            'ajax'      => false        //does this table support ajax?

        ) );

    }

    function column_default($item, $column_name){


    }

    function column_title($item){

        //Build row actions

        $actions = array(

            'edit'      => sprintf('<a href="?page=%s&action=%s&p=%s">Edit</a>',sanitize_text_field( $_REQUEST['page'] ),'edit',$item['id']),

			'delete'    => sprintf('<a href="?page=%s&action=%s&p=%s">Delete</a>',sanitize_text_field( $_REQUEST['page'] ),'delete',$item['id']),

			
        );

        //Return the title contents

		return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
		

            /*$1%s*/ $item['apartment'],

			/*$2%s*/ $item['id'],
			
			

            /*$3%s*/ $this->row_actions($actions)

		);

	}
	
	
	


    function column_cb($item){

        return sprintf(

            '<input type="checkbox" name="%1$s[]" value="%2$s " />',

            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")

			/*$2%s*/ $item['ID'],       //The value of the checkbox should be the record's id

		//	/*$3%s*/ $item['disable'],  

			      

        );

	}
	
	

    function get_columns(){

        $columns = array(

            'id'        => '<label for="id-select-all-1" class="screen-reader-text">Select All</label><input class="id-select-all-1" type="checkbox" />', //Render a checkbox instead of text

            'apartment'     => 'Apartment',

            'city'    => 'City',

            'state'  => 'State',

			'dod'  => 'Delivery within days',

			'disable' => 'Disable',

        );

        return $columns;

    }

    function get_sortable_columns() {

        $sortable_columns = array(

            'apartment'     => array('apartment',false),  //true means it's already sorted

            'city'    => array('city',false),

            'state'  => array('state',false),

			'dod'  => array('dod',false),

			'disable' => array('disable',false),

				

        );

        return $sortable_columns;

    }

    function get_bulk_actions() {

        $actions = array(

			'delete'    => 'Delete',
			

        );

        return $actions;

    }

    function process_bulk_action($redirect_to, $doaction, $ids) {

        //Detect when a bulk action is being triggered...

        if( 'delete'===$this->current_action() ) {

            wp_die('Items deleted (or they would be if we had items to delete)!');

        }


			
		

    }

    function prepare_items() {

	   global $wpdb, $_wp_column_headers,$table_prefix;

		/* -- Preparing your query -- */

        $query = "SELECT * FROM `".$table_prefix."check_apart_p`";

		/* -- Ordering parameters -- */

       //Parameters that are going to be used to order the result

       $orderby = !empty($_GET["orderby"]) ? $_GET["orderby"] : 'ASC';

       $order = !empty($_GET["order"]) ? $_GET["order"] : '';
	 

       if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

		/* -- Pagination parameters -- */
	
		//Number of elements in your table?

		$totalitems = $wpdb->query($query); //return the total number of affected rows

        //How many to display per page?

        $perpage = 15;

        //Which page is this?

        $paged = !empty($_GET["paged"]) ? $_GET["paged"] : '';

        //Page Number

        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }

        //How many pages do we have in total?

        $totalpages = ceil($totalitems/$perpage);

        //adjust the query to take pagination into account

		if(!empty($paged) && !empty($perpage)){

			$offset=($paged-1)*$perpage;

			$query.=' LIMIT '.(int)$offset.','.(int)$perpage;
	
		}

		/* -- Register the pagination -- */

		$this->set_pagination_args( 
			
			array(
	
				"total_items" => $totalitems,

				"total_pages" => $totalpages,
		
				"per_page" => $perpage,

			) 
		);

      //The pagination links are automatically built according to those parameters

	  /* -- Register the Columns -- */

		$columns = $this->get_columns();

		$hidden = array();

		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);

	/* -- Fetch the items -- */

		$this->items = $wpdb->get_results($query);

    }

	function display_rows() 
	{

		//Get the records registered in the prepare_items method

		$records = $this->items;

		//Get the columns registered in the get_columns and get_sortable_columns methods

		list( $columns, $hidden ) = $this->get_column_info();

		//Loop for each record

		if(!empty($records)){
			
			foreach($records as $rec){

				//Open the line

				echo '<tr class="alternate" id="record_'.$rec->id.'">';

				foreach ( $columns as $column_name => $column_display_name ) {

					//Style attributes for each col

					$class = "class='$column_name column-$column_name'";

					$style = "";

					if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';

					$attributes = $class . $style;

					//edit link

					$editlink  = '/wp-admin/link.php?action=edit&id='.stripslashes($rec->id);

					//Display the cell

					switch ( $column_name ) {

						case "id":     echo '<th '.$attributes.'><input name="apartment[]" type="checkbox" value="'.stripslashes($rec->id).'" /></th>';break;

						case "apartment": echo '<td '.$attributes.'>'.stripslashes($rec->apartment).'<div class="row-actions"><span class="edit"><a href="?page=list_apartments&amp;action=edit&amp;id='.stripslashes($rec->id).'">Edit</a> | </span><span class="delete"><a href="?page=list_apartments&amp;action=delete&amp;id='.stripslashes($rec->id).'">Delete</a></span></div></td>'; break;

						case "city": echo '<td '.$attributes.'>'.stripslashes($rec->city).'</td>'; break;

						case "state": echo '<td '.$attributes.'>'.stripslashes($rec->state).'</td>'; break;

						case "dod": echo '<td '.$attributes.'>'.stripslashes($rec->dod).'</td>'; break;

						
						

						
						

					}

				}


				//Close the line

				echo'</tr>';

			}
		}

	}

}

function list_apartment_f()
{

	global $table_prefix, $wpdb;

	//Create an instance of our package class...

	$testListTable = new TT_Example_List_Tablee();

    //Fetch, prepare, sort, and filter our data...

	$testListTable->prepare_items();

	if( isset( $_GET['tab'] ) ) {
		
		$tab = sanitize_text_field( $_GET['tab'] );
		
	}
	else
	{
		
		$tab = '';
		
	}
?>

	<div class="wrap">

		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
				<a class="nav-tab <?php if($tab == 'list' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=list_apartments&amp;tab=list"><?php esc_html_e('Apartment List','pho-apart-cod'); ?></a></h2>

	<?php

	
			
       

	if($tab == 'list' || $tab == '')
	{
		if( !empty( $_GET['id'] ) )
		{
			
			$id = sanitize_text_field( $_GET['id'] );
			
		}
		

		if( isset( $_GET['action'] ) ) {
			
			$action = sanitize_text_field( $_GET['action'] );
			
		}
		else
		{
			
			$action = '';
			
		}
		
		$delval=0;
		$ids = isset($_GET['apartment'])?$_GET['apartment']:'';
		
		$id =  isset($_GET['id'])?sanitize_text_field($_GET['id'] ):'';
		if(isset($_GET['id']) || isset($_GET['apartment'])){
			$delval=1;
		}
		
		
		/* echo '<pre>';
		print_r($_GET);
		echo '</pre>'; */
		// die();
			
		if( !empty( $_GET['action'] ) && sanitize_text_field( $_GET['action'] ) == 'delete' && $delval == 1)
		{
			?>

				<div class="updated below-h2" id="message"><p><?php esc_html_e('Deleted Successfully.','pho-apartment-cod'); ?></p></div>

			<?php
		}



		if(sanitize_text_field( $action ) == 'edit' && isset($id))

		{



			if(isset($_POST['submit']) && sanitize_text_field( $_POST['submit'] ) == 'Update')

			{



				$apartment = isset($_POST['apartment'])? sanitize_text_field( $_POST['apartment'] ):'';



				$city = isset($_POST['city'])? sanitize_text_field( $_POST['city'] ):'';



				$state = isset($_POST['state'])? sanitize_text_field( $_POST['state'] ):'';



				$dod = isset($_POST['dod'])? sanitize_text_field( $_POST['dod'] ):'';



				$cod = isset($_POST['cod'])? sanitize_text_field( $_POST['cod'] ):'';


				$diable= isset($_POST['disable'])? sanitize_checkbox_field( $_POST['disable'] ):'';

				
				

				$safe_apart =  $apartment ;
				
		
				$safe_dod = intval( $dod );
				
			
					if (  $safe_apart && $safe_dod )
					{
						$wpdb->query( $wpdb->prepare( "UPDATE `".$table_prefix."check_apart_p` SET `apartment`='%s', `city`='%s', `state`='%s', `dod`='%d' where `id` = %d", $apartment,$city,$state,$dod,$id,) );

						?>


							<div class="updated below-h2" id="message"><p><?php esc_html_e('Updated Successfully.','pho-apart-cod'); ?></p></div>


						<?php



					}
					else
					{
						?>

							<div class="error below-h2" id="message"><p> <?php esc_html_e('Please Fill Valid Data.','pho-apart-cod'); ?></p></div>

						<?php
					}
			

			}
			
			$qry22 = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `".$table_prefix."check_apart_p` where `id` = %d" ,$id) ,ARRAY_A);	

			foreach($qry22 as $qry)

			{

			}



			?>



			<div id="icon-users" class="icon32"><br/></div>



			<h2><?php esc_html_e('Update Apartment ','pho-apart-cod'); ?></h2>



				<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->



			<form action="" method="post" id="uzip_form" name="uzip_form">


				<table class="form-table">



				<tbody>



					<tr class="user-user-login-wrap">



						<th><label for="user_login"><?php esc_html_e('Apartment','pho-apart-cod'); ?></label></th>



						<td><input required="required" type="text"  pattern="[a-zA-Z0-9\s]+" class="regular-text"  value="<?php echo $qry['apartment'];?>" id="apartment" name="apartment"></td>



					</tr>


					<tr class="user-first-name-wrap">



						<th><label for="first_name"><?php esc_html_e('City','pho-apart-cod'); ?></label></th>



						<td><input required="required" type="text" class="regular-text"  value="<?php echo $qry['city'];?>" id="city" name="city"></td>



					</tr>


					<tr class="user-last-name-wrap">



						<th><label for="last_name"><?php esc_html_e('State','pho-apart-cod'); ?></label></th>



						<td><input required="required" type="text" class="regular-text"  value="<?php echo $qry['state'];?>" id="state" name="state"></td>



					</tr>


					<tr class="user-nickname-wrap">



						<th><label for="nickname"><?php esc_html_e('Delivery within days','pho-apart-cod'); ?></label></th>



						<td><input required="required" type="number" min="1" max="365" step="1" class="regular-text" value="<?php echo $qry['dod'];?>" id="dod" name="dod"></td>

					</tr>


						
					
					
					

				</tbody>


			</table>



				<p class="submit"><a class="button" href="?page=list_apartments"><?php esc_html_e('Back','pho-apart-cod'); ?></a>&nbsp;&nbsp;<input type="submit" value="Update" class="button button-primary" id="submit" name="submit"></p>



		</form>


			<?php



		}

		else

		{


			?>



			<div id="icon-users" class="icon32"><br/></div>



			<h2><?php esc_html_e('Apartment List ','pho-pho-apart-cod'); ?><a class="add-new-h2" href="?page=add_apartments"><?php esc_html_e('Add New','pho-apart-cod'); ?></a></h2>



			<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->



			<form id="apartment-filter" method="get">



				<!-- For plugins, we also need to ensure that the form posts back to our current page -->



				<input type="hidden" name="page" value="<?php echo sanitize_text_field( $_REQUEST['page'] ); ?>" />



				<!-- Now we can render the completed list table -->



				<?php $testListTable->display(); ?>



			</form>



			<?php



		}


	}


	/*function get_bulk_action_disable_apartment($bulk_actions) {
		$bulk_actions['ja_enable_apartment']  = __( 'Enable',  'ja-disable-apartment' );
		$bulk_actions['ja_disable_apartment'] = __( 'Disable', 'ja-disable_apartment' );
		return $bulk_actions;
	}

	function handle_bulk_actions_disable_apartment($redirect_to, $doaction, $ids) {
		if ($doaction !== 'ja_disable_apartment' && $doaction !== 'ja_enable_apartment'){
			return $redirect_to;
		}

		$disabled = ($doaction === 'ja_disable_apartment') ? 1 : 0;

		foreach ( $ids as $id ){
			update_user_meta( $id, 'ja_disable_apartment', $disabled );
		}

		if ($disabled){
			$redirect_to = add_query_arg( 'ja_disabled', count($ids), $redirect_to );
			$redirect_to = remove_query_arg( 'ja_enabled', $redirect_to );
			
		} else {
			$redirect_to = add_query_arg( 'ja_enabled',  count($ids), $redirect_to );
			$redirect_to = remove_query_arg( 'ja_disabled', $redirect_to );
		}
		return $redirect_to;

	}*/

	/*if($tab == 'premium')
	{
		require_once(dirname(__FILE__).'/premium-setting.php');
	}*/
	?>
 </div>
 
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

	</script>






			
		




    <?php

}

?>

