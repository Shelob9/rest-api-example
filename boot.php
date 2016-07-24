<?php


namespace josh\api;


use josh\api\interfaces\route;

class boot {

	/**
	 * @var array
	 */
	protected $routes;

	/**
	 * @var bool
	 */
	protected $booted = false;

	/**
	 * @var string
	 */
	protected $namespace;

	/**
	 * boot constructor.
	 *
	 * @param string $namespace Namespace for all routes
	 */
	public function __construct( $namespace ) {
		$this->namespace = $namespace;
	}

	/**
	 * Add a route to this API
	 * 
	 * @param route $route
	 */
	public function add_route( route $route ){
		$this->routes[] = $route;
	}

	/**
	 * Create endpoints
	 */
	public function add_routes(){
		if( ! $this->booted && ! empty( $this->routes ) ){
			/** @var route $route */
			foreach ( $this->routes as $route ){
				$route->add_routes( $this->namespace );
			}
			
			$this->booted = true;
			
		}
		
	}

}