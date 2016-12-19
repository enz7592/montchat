<?php

namespace Tchat\Models;

use app\Kernel;

/**
 * Class Model
 *
 * @package Core\Repository
 */
class Model 
{
    /**
     * @var
     */
    protected $table;

    /**
     * @var kernel
     */
    protected $kernel;

    private static $handler;

    private $dbhost = "127.0.0.1";
    private $dbname = "user";
    private $dbuser = "root";
    private $dbpswd = "toor";
    private $port = 3306;


    /**
     *
     */
    public function __construct(Kernel $kernel)
    {
        
        $this->kernel = $kernel;
    
 
    }


    /**
     * @return \PDO
     */
    public function getConnection()
    {
        
        if (!self::$handler) {
            try {
                self::$handler = new \PDO(
                    "mysql:host=$this->dbhost;port=$this->port;dbname=$this->dbname",
                    $this->dbuser,
                    $this->dbpswd,
                    [\PDO::ATTR_PERSISTENT => false]
                );

            } catch (\Exception $e) {
                echo $e->getMessage();
                die;
            }
        }
       
        return self::$handler;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $handler = $this->getConnection();


        $query = "SELECT * FROM $this->table;";

        $query = $handler->prepare($query);

        $query->execute();

        //$result = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        $result = $query->fetchAll(\PDO::FETCH_OBJ);


        return $result;
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function findByCriteria(array $criteria = [])
    {
        $handler = $this->getConnection();

        $wheres = [];
        foreach ($criteria as $key => $value) {
            $wheres[] = sprintf("$key = :$key");
        }

        $query = "SELECT * FROM $this->table ";

        if (count($wheres)) {
            $query .= sprintf("WHERE %s", implode("and", $wheres));
        }

        $query = $handler->prepare($query);

        $query->execute($criteria);

        //$result = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        $result = $query->fetchAll(\PDO::FETCH_OBJ);


        return $result;
    }

    /**
     * @param array $criteria
     *
     * @return null
     */
    public function findOneByCriteria(array $criteria = [])
    {
        $handler = $this->getConnection();

        $wheres = [];
        foreach ($criteria as $key => $value) {
            $wheres[] = sprintf("$key = :$key");
        }

        $query = "SELECT * FROM $this->table ";

        if (count($wheres)) {
            $query .= sprintf("WHERE %s", implode("and", $wheres));
        }

        $query .= " limit 1";

        $query = $handler->prepare($query);

        $query->execute($criteria);

        //$result = $query->fetchAll(\PDO::FETCH_CLASS, User::class);
        $results = $query->fetchAll(\PDO::FETCH_OBJ);

        if (count($results)) {
            return $results[0];
        }

        return null;
    }

    /**
     * @param array $data
     *
     * @return null
     */
    public function insert(array $data = [])
    {
        
        $handler = $this->getConnection();

        $query = sprintf(
            "INSERT INTO $this->table (`%s`) VALUES (%s)",
            implode('`, `', array_keys($data)),
            implode(', ', array_pad([], count($data), '?'))
        );
//var_dump($query);die;
        $query = $handler->prepare($query);

        $query->execute(array_values($data));
        //var_dump($query);die;

        $lastId = $handler->lastInsertId();

        return $this->findOneByCriteria(['id' => $lastId]);
    }

    /**
     * @param array $data
     * @param array $criteria
     *
     * @return bool|null
     */
    public function update(array $data, array $criteria)
    {
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            if (!isset($criteria['id'])) {
                $criteria['id'] = $id;
            }
        }

        $handler = $this->getConnection();

        $updateFields   = [];
        $criteriaFields = [];


        foreach ($data as $key => $value) {
            if (trim($value)) {
                $updateFields[] = sprintf("`%s`='%s'", $key, trim($value));
            }
        }

        foreach ($criteria as $key => $value) {
            if (trim($value)) {
                $criteriaFields[] = sprintf("`%s`='%s'", $key, trim($value));
            }
        }
        if (count($updateFields) && count($criteriaFields)) {

            $query = sprintf(
                "UPDATE $this->table SET %s WHERE %s",
                implode(', ', $updateFields),
                implode('and', $criteriaFields)
            );

            //var_dump($query);die;
            $query = $handler->prepare($query);


            $query->execute();

            return $this->findOneByCriteria($criteria);
        }

        return false;
    }

}
