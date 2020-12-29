<?php
/**
 * Plugin Name: Disable Users
 * Plugin URI:  http://wordpress.org/extend/disable-users
 * Description: This plugin provides the ability to disable specific user accounts.
 * Version:     1.0.5
 * Author:      Jared Atchison
 * Author URI:  http://jaredatchison.com 
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

final class disable_apartment {
    function __construct() {

        add_filter( 'bulk_actions-apartment',         array( $this, 'bulk_action_disable_apartment '   )        );
		add_filter( 'handle_bulk_actions-apartment',  array( $this, 'handle_bulk_actions_disable_apartment'   ), 10, 3 );
    }



public function bulk_action_disable_apartment($bulk_actions) {
    $bulk_actions['enable_apartment']  = __( 'Enable',  'disable-apartment' );
    $bulk_actions['disable_apartment'] = __( 'Disable', 'disable_apartment' );
    return $bulk_actions;
    }


    
/**
	 * Handle the bulk action to enable/disable users
	 * @since 1.0.6
	 */
	public function handle_bulk_actions_disable_apartment($redirect_to, $doaction, $ids) {
		if ($doaction !== 'disable_apartment' && $doaction !== 'enable_apartment'){
			return $redirect_to;
		}

		$disabled = ($doaction === 'disable_apartment') ? 1 : 0;

		foreach ( $ids as $id ){
			update_user_meta( $id, 'disable_apartment', $disabled );
		}

		if ($disabled){
			$redirect_to = add_query_arg( 'disabled', count($ids), $redirect_to );
			$redirect_to = remove_query_arg( 'enabled', $redirect_to );
		} else {
			$redirect_to = add_query_arg( 'enabled',  count($ids), $redirect_to );
			$redirect_to = remove_query_arg( 'disabled', $redirect_to );
		}
		return $redirect_to;
    }

    /**
	 * Add admin notices after enabling/disabling users
	 * @since 1.0.6
	 */
	public function bulk_disable_apartment_notices() {
		if (! empty( $_REQUEST['enabled'] ) ){
			$updated = intval( $_REQUEST['enabled'] );
			printf( '<div id="message" class="updated">' .
				_n( 'Enabled %s apartment.',
					'Enabled %s apartment.',
					$updated,
					'disable-apartment'
				) . '</div>', $updated );
        }
        if (! empty( $_REQUEST['disabled'] ) ){
			$updated = intval( $_REQUEST['disabled'] );
			printf( '<div id="message" class="updated">' .
				_n( 'Disabled %s apartment.',
					'Disabled %s apartment.',
					$updated,
					'disable-apartment'
				) . '</div>', $updated );
        }
    }


}

new disable_apartment();





