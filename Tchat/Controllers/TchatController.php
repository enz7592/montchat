<?php

namespace Tchat\Controllers;

use Tchat\Controllers\Controller;
use app\Router;


Class TchatController extends Controller
{
    public function tchatAction(){
        
        $this->needAuthenticated();
         
        $data=[];
        $userModel = $this->kernel->getModel('user');
      /* @var $messageModel \app\Kernel */
        $messageModel = $this->kernel->getModel('message');

        $isValide = TRUE;
        
        $criteria['id'] = $this->kernel->getSession()->get('user')->id;
        $data['online'] = 1;
        $userModel->update($data, $criteria);
        
   
        
        if(isset($_POST['tchat'])){
            
            $newmessage = $_POST['tchat'];
            $messageModel->insert($newmessage);
      
        }

        $lastId   = 0;
        $orderBy  = ['created_at' => 'DESC'];
        $limite   = 25;
        $tchats = $messageModel->getMessages($lastId, $orderBy, $limite);
        
        $sortByCreationDate =  function ($a, $b) {
              if ($a == $b) {
                  return 0;
              }
              return ($a->created_at < $b->created_at) ? -1 : 1;
          };
        
 
        uasort($tchats,  $sortByCreationDate);
        
         
        $data = [
            'tchats' => $tchats,
            'user'   => $this->kernel->getSession()->get('user'),
            'action' => Router::routeUri('tchat'),
            'logout' => Router::routeUri('logout'),
        ];
         
 

        $this->renderTwig('tchat.html.twig', $data);
    }
       
    public function getMessage(){
        
    }
}
