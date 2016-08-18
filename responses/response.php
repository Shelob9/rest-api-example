<?php

namespace josh\api\responses;

class response  extends \WP_REST_Response{
	
	
	public function __construct( $data, $status, array $headers ) {
		parent::__construct( $data, $status, $headers );
		if( empty( $data ) ){
			$this->set_status( 404 );
		}
		
	}

}
