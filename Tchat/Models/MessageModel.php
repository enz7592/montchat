<?php
 

namespace Tchat\Models;

/**
 * Class MessageModel
 *
 * @package Tchat\Models
 */
class MessageModel  extends Model
{
    /**
     * @var string
     */
    protected $table='message';


    /**
     * @param array $data
     *
     * @return null
     */
    public function insert(array $data = [])
    {
      
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->kernel->getSession()->get('user')->id;
      
        return parent::insert($data);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        $handler = $this->getConnection();


        $query = "SELECT * FROM $this->table INNER JOIN user ON message.created_by = user.id ORDER BY  created_at DESC limit 15;";

        $query = $handler->prepare($query);

        $query->execute();

        //$result = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        $results = $query->fetchAll(\PDO::FETCH_OBJ);

        foreach($results as $key=> $result)
        {
            $now = new \DateTime();
            $lastLogin = new \DateTime($result->last_login);

            $diff = $lastLogin->diff($now);
            
            $result->enLigne = $result->online && ($diff->y ==0 && $diff->m ==0 && $diff->d ==0 && $diff->h ==0 && $diff->m <=5);
        }
      
        return $results;
    }
}
