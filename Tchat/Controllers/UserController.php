<?php
namespace Tchat\Controllers;


use Tchat\Controllers\Controller;
use Tchat\Models\UserModel;
use app\Router;
        
/**
 * Class UserController
 *
 * @package Tchat\Controllers
 */
class UserController extends Controller
{
      
    /**
     * @param array $params
     *
     * @throws \Exception
     */
    public function loginAction(array $params = [])
    {
        
      $data =  [];

        $userModel = $this->kernel->getModel('user');
        
       // var_dump($userModel);die;

        if ($this->session->has('user')) { 
            header("Location: ".Router::routeUri('tchat'));
        }
        
        if (isset($_POST['user'])) {       
            $isValide   = false;
            $postedUser = $_POST['user'];
            $postedUser['password'] = sha1($postedUser['password']);

            $bddUser = $userModel->findOneByCriteria(['username' => $postedUser['username']]);

            if ($bddUser) { 
                if ($bddUser->password === $postedUser['password']) {
                    $isValide = true; 
                    unset($postedUser['password']);
                    unset($postedUser['username']);                    
                    $userModel->update($postedUser, ['id' => $bddUser->id]);
                }else{
                    $data['error'] = 'Invalid password';
                    $data['loginUser'] = $postedUser;
                    
                }
            } else { 
                $bddUser  = $userModel->insert($postedUser);
                $isValide = true;
            }

            if ($isValide) {
                $this->session->set('user', $bddUser);
            }
        }

        if ($this->session->has('user')) { 
            header("Location: ".Router::routeUri('tchat'));
        }


        $data['action'] = Router::routeUri('login');

        $this->renderTwig('login.html.twig', $data);
    }
    

    /**
     * @param array $params
     *
     * @throws \Exception
     */
    public function logoutAction(array $params = [])
    {
      
        /**
         * @var UserRepository $userRepository
         */
        $userModel = $this->kernel->getModel('user');
        $criteria['id'] = $this->kernel->getSession()->get('user')->id;
       
        $data['online'] = 0;
        $userModel->update($data, $criteria);
        $this->session->close();
        
        
        
      header("Location: ".Router::routeUri('login'));
    }
    
    

}