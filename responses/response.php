<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 7/9/16
 * Time: 6:49 PM
 */

namespace josh\api\error;


class response  extends \WP_REST_Response{
	
	
	public function __construct( $data, $status, array $headers ) {
		parent::__construct( $data, $status, $headers );
		if( empty( $data ) ){
			$this->set_status( 404 );
		}
		
	}

}