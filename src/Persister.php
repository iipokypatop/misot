<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 07/08/15
 * Time: 17:23
 */

namespace Aot;



trait Persister
{
    use DaoAccessor;

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

}