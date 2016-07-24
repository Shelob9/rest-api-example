<?php

namespace josh\api\routes;


use josh\api\error\response;

class product extends public_route {

	/**
	 * @var string Post type slug
	 */
	protected $post_type = 'my-products';

	/**
	 * @inheritdoc
	 */
	public function request_args(){
		return [
			'type' => [
				'required' => true,
				'type' => 'string',
				'validation_callback' => [ $this, 'is_type' ]
			],
			'page' => [
				'required' => false,
				'type' => 'integer',
				'default' => 1
			],
			'name' => [
				'required' => false,
				'type' => 'string',
				'sanitize_callback'  => 'sanitize_text_field',
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function get_item( \WP_REST_Request $request ) {
		
		$url_params = $request->get_url_params();
		$post = get_post(  $url_params[ 'id' ] );
		if( ! empty( $post ) && $this->post_type == get_post_type( $post ) ){
			return new response( $post, 200, [] );
		}

		return new response( [], 404, [] );
	}

	/**
	 * @inheritdoc
	 */
	public function get_items( \WP_REST_Request $request ) {
		$args = $this->query_args( $request[ 'type' ], $request[ 'page' ] );
		$query = $this->query( $args );
		$items = $this->prepare_items_for_response( $query );

		return new response( $items );

	}

	/**
	 * Turn post object into just the fields we need for response
	 *
	 * @param \WP_Post $item
	 *
	 * @return array
	 */
	protected function prepare_item_for_response( \WP_Post $item ){
		return [
			'name' => $item->post_title,
			'description' => $item->post_excerpt,
			'price' => get_post_meta( $item->ID, 'price', true )
		];
	}

	/**
	 * Loop through WP_Query object
	 *
	 * @param \WP_Query $query
	 *
	 * @return array
	 */
	protected function prepare_items_for_response( \WP_Query $query ){
		$items = [];
		if( ! empty( $query->posts ) ){
			foreach ( $query->posts as $post ){
				$items[ $post->ID ] = $this->prepare_item_for_response( $post );
			}
		}
		
		return $items;
		
	}

	/**
	 * Create WP_Query object
	 *
	 * @param $args
	 *
	 * @return \WP_Query
	 */
	protected function query( $args ){
		return new \WP_Query( $args );
	}

	/**
	 * Create WP_Query args
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	protected function query_args( $type = '', $page = 1 ){
		$args = [
			'post_type' => $this->post_type,
			'paged' => $page,
		];

		if( ! empty( $page ) ){
			$args ['tax_query' ] =[
				[
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => $type,
				],
			];
		}

		return $args;
	}

}