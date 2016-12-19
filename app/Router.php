<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Router
 *
 * @author Patrick Ngali
 */
namespace app;
 
class Router {
    
    	private static $routes = array();
	private static $routeUri = array();

	private function __construct() {}
	private function __clone() {}
	
        public static function init()
        {
             
            self::route('blog','/blog', 'Tchat\Controllers\FrontController', 'indexAction');
            self::route('login','/login', 'Tchat\Controllers\UserController', 'loginAction');
            self::route('logout','/logout', 'Tchat\Controllers\UserController', 'logoutAction');
            self::route('tchat','/tchat', 'Tchat\Controllers\TchatController', 'tchatAction');
        }
	public static function route($name, $pattern, $controller, $action) {
                            
            self::$routeUri[$name]=$pattern;

		$pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';             
		self::$routes[$pattern] = [
                        'controller' => $controller,
                        'action' => $action
                    ];
                	}
	
	public static function execute($kernel) {            
            $url = $_SERVER['REQUEST_URI'];           
		foreach (self::$routes as $pattern => $callback) {
			if (preg_match($pattern, $url, $params)) {
				array_shift($params);                                
                                $controller = new self::$routes[$pattern]['controller']($kernel);
                               
                                return $controller->{self::$routes[$pattern]['action']}(array_values($params));
			} 
		}
	}
        
        
        public static function routeUri($name)
        {
            return self::$routeUri[$name];
        }
}
