<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 07/08/15
 * Time: 19:52
 */

namespace Aot\Registry;


use AotPersistence\API\AotAPI;
use MivarTest\PHPUnitHelper;

trait Uploader
{
    protected $conn_db = "host=test-db.miva.pro dbname=misot user=postgres password=@Mivar123User@";

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

    /**
     * @return int[]
     */
    abstract protected function getIds();

    /**
     * @return string[]
     */
    abstract protected function getFields();

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function save()
    {
        $entity = [];
        foreach ($this->getIds() as $id) {
            $result = $this->getEntityManager()->find($this->getEntityClass(), $id);
            if ($result === null) {
                $class = $this->getEntityClass();
                $entity[$id] = new $class;
            }
            $changes = $this->charge($entity[$id], $id);
            if (!empty($changes)) {
                $this->getEntityManager()->persist($entity[$id]);
            }
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param object $ob
     * @param int $id
     * @return array
     */
    private function charge($ob, $id)
    {
        $changes = [];
        PHPUnitHelper::setProtectedProperty($ob, 'id', $id);
        foreach ($this->getFields() as $field => $callback) {
            $new_value = forward_static_call($callback)[$id];
            $entity_field = PHPUnitHelper::getProtectedProperty($ob, $field);
            if (empty($entity_field) || $entity_field !== $new_value) {
                $changes[$field] = $new_value;
                PHPUnitHelper::setProtectedProperty($ob, $field, $new_value);
            }

        }
        return $changes;
    }
}