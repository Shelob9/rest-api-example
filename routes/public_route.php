<?php


namespace josh\api\routes;


abstract class public_route extends crud {

	/**
	 * @inheritdoc
	 */
	public function get_items_permissions_check( \WP_REST_Request $request ){
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function create_item_permissions_check( \WP_REST_Request $request ){
		return current_user_can( 'manage_options' );
	}

}