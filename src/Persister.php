<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 07/08/15
 * Time: 17:23
 */

namespace Aot;

use \AotPersistence\API\AotAPI;


trait Persister
{
    protected $dao;
    protected $conn_db = "host=192.168.10.51 dbname=misot user=postgres password=@Mivar123User@";

    /**
     * @return object
     */
    public function getDao()
    {
        return $this->dao;
    }

    public function persist()
    {
        $this->getEntityManager()->persist($this->dao);
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }


    /**
     * @param int $id
     */
    public function findById($id)
    {
        $em = $this->getEntityManager();
        $this->dao = $em->find($this->getEntityClass(), $id);
    }

    /**
     * @return string
     */
    abstract protected function getEntityClass();

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return AotAPI::getAPI($this->conn_db);
    }
}