<?php
namespace josh\api\routes;


final class shoes extends product {

	/**
	 * @inheritdoc
	 */
	public function request_args(){
		$args = parent::request_args();
		unset( $args[ 'type' ] );
		return $args;
	}

	/**
	 * @inheritdoc
	 */
	protected function query_args( $type = '', $page = 1 ) {
		return parent::query_args( 'shoes', $page );
	}
}