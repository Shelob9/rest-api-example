<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 7/9/16
 * Time: 6:37 PM
 */

namespace josh\api\routes;


use josh\api\error\error;
use josh\api\error\response;
use josh\api\interfaces\route;

abstract class crud implements route {

	/**
	 * @inheritdoc
	 */
	public function add_routes( $namespace ) {
		$base = $this->route_base();
		register_rest_route( $namespace, '/' . $base, [
				[
					'methods'         => \WP_REST_Server::READABLE,
					'callback'        => [ $this, 'get_items' ],
					'permission_callback' => [ $this, 'get_items_permissions_check' ],
					'args'            => [
						'page' => [
							'default' => 1,
							'sanitize_callback'  => 'absint',
						],
						'limit' => [
							'default' => 10,
							'sanitize_callback'  => 'absint',
						]
					],
				],
				[
					'methods'         => \WP_REST_Server::CREATABLE,
					'callback'        => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'create_item_permissions_check' ],
					'args'            => $this->request_args()
				],
			] 
		);
		register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+]', [
				[
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_item_permissions_check' ],
					'args'                => [
						'context' => [
							'default' => 'view',
						]
					],
				],
				[
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'update_item_permissions_check' ],
					'args'                => $this->request_args(  )
				],
				[
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'delete_item_permissions_check' ],
					'args'                => [
						'force' => [
							'default'  => false,
							'required' => false,
						],
						'all'   => [
							'default'  => false,
							'required' => false,
						],
						'id'    => [
							'default'               => 0,
							'sanatization_callback' => 'absint'
						]
					],
				],
			]
		);

		
	}


	/**
	 * Define query arguments
	 * 
	 * @return array
	 */
	abstract public function request_args();

	/**
	 * Check if a given request has access to get items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	abstract public function get_items_permissions_check( \WP_REST_Request $request );


	/**
	 * Check if a given request has access to get a specific item
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	public function get_item_permissions_check( \WP_REST_Request $request ) {
		return $this->get_items_permissions_check(  $request );

	}
	/**
	 * Check if a given request has access to create items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	abstract public function create_item_permissions_check( \WP_REST_Request $request );

	/**
	 * Check if a given request has access to update a specific item
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	public function update_item_permissions_check( \WP_REST_Request $request ) {
		return $this->create_item_permissions_check( $request );
	}
	/**
	 * Check if a given request has access to delete a specific item
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|bool
	 */
	public function delete_item_permissions_check( \WP_REST_Request $request ) {
		return $this->create_item_permissions_check( $request );
	}

	/**
	 * Get a collection of items
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_items( \WP_REST_Request $request ) {
		return $this->not_yet_response();
	}

	/**
	 * Get one item from the collection
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return \WP_Error|\WP_REST_Response
	 */
	public function get_item( \WP_REST_Request $request ) {
		return $this->not_yet_response();
	}
	/**
	 * Create one item from the collection
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return response|error
	 */
	public function create_item( \WP_REST_Request $request ) {
		return $this->not_yet_response();
	}
	/**
	 * Update one item from the collection
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return response|error
	 */
	public function update_item( \WP_REST_Request $request ) {
		return $this->not_yet_response();
	}
	/**
	 * Delete one item from the collection
	 *
	 * @param \WP_REST_Request $request Full data about the request.
	 * @return response|error
	 */
	public function delete_item( \WP_REST_Request $request ) {
		return $this->not_yet_response();
	}


	/**
	 * Return a 501 error for non-existant route
	 *
	 * @return response
	 */
	protected function not_yet_response() {
		$error =  new error( 'not-implemented-yet', __( 'Route Not Yet Implemented :(', 'your-domain' )  );
		return new response( $error, 501, [] );
	}

	/**
	 * Get class shortname and use as base
	 *
	 * Probably better to ovveride in subclass with a hardcoded string.
	 */
	protected function route_base() {
		return substr( strrchr( get_class( $this ), '\\' ), 1 );
	}
}