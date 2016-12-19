<?php

namespace Tchat\Models;


use Tchat\Models\Model;
/**
 * Class UserModel
 *
 * @package Tchat\Models
 */
class UserModel extends Model
{

    /**
     * @var string
     */
    protected $table = 'user';


    /**
     * @param array $data
     *
     * @return null
     */
    public function insert(array $data = [])
    {
       
        $data['last_login'] = date('Y-m-d H:i:s');
        $data['online']     = 1;

        return parent::insert($data);
    }

    /**
     * @param array $data
     * @param array $criteria
     *
     * @return bool|null
     */
    public function update(array $data, array $criteria)
    {
        $data['last_login'] = date('Y-m-d H:i:s');
        $data['online']     = 1;

        return parent::update($data, $criteria);
    }
}


