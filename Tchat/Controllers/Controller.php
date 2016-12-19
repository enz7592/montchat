<?php


namespace Tchat\Controllers;
 
use app\Kernel;
use app\Router;
use app\Session;

//use Tchat\Session\Session;

/**
 * Class Controller
 *
 * @package Core\Controllers
 */
class Controller 
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var $kernel
     */
    protected $kernel;

    /**
     * Constructor
     */
    public function __construct(Kernel $kernel)
    { 
      
        $this->kernel = $kernel;
        $this->session = $kernel->getSession();
   
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        return strtolower(str_replace('Controller', '', @array_pop(explode("\\", get_called_class()))));
    }

    /**
     * @throws \Exception
     */
    protected function needAuthenticated()
    {
        if (!$this->session->has('user')) {
      
            $homepage = Router::routeUri('login');
            header("Location: $homepage");
            
        }

    }
    
    /**
     * @param       $filename
     * @param array $data
     */
    public function renderTwig($filename, array $data = [])
    {    
        //  var_dump($filename,$data);
        $calledClassFile = str_replace('Controller', '', array_pop(explode("\\", get_called_class())));
        
        $viewFolder = implode(DIRECTORY_SEPARATOR,[ROOT_DIR, 'Tchat', 'Views']);
        
        $paths = [
            $viewFolder,
            implode(DIRECTORY_SEPARATOR,[$viewFolder, $calledClassFile])
        ];
        //var_dump($paths);
        $loader = new \Twig_Loader_Filesystem($paths);
        $twig   = new \Twig_Environment(
            $loader, [
                'cache' => false,
            ]
        );
        
        echo $twig->render(implode(DIRECTORY_SEPARATOR,[$calledClassFile, $filename]), $data);
        
        exit;
    }
}
