<?php

namespace app;

 
use app\Session; 
 
/**
 * Class Kernel
 *
 * @package app
 */
class Kernel 
{

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Session
     */
    private $session;

    /**
     *
     */
    public function __construct()
    {
        
         
        define('ROOT_DIR', dirname(__DIR__));
        Router::init();
         $this->session = new Session();
        $this->session->start();
        
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return Router::execute($this);
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }


    /**
     * @param $name
     *
     * @return mixed
     */
    public function getModel($name)
    {
        $className = sprintf("Tchat\\Models\\%sModel", ucfirst(strtolower($name)));
     //var_dump($className);die;  
        return new $className($this);
    }
}
