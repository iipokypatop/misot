<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 07/08/15
 * Time: 19:52
 */

namespace Aot\Registry;

use Aot\Tools\Config;

use MivarTest\PHPUnitHelper;

trait Uploader
{
//    protected $conn_db = "host=test-db.mivar.pro dbname=mivar_semantic_new user=postgres password=@Mivar123User@";

    /**
     * @return string
     */
    abstract protected function getEntityClass();

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        $config = Config::getConfig();

        return \SemanticPersistence\API\SemanticAPI::getAPI($config[$config['MODE']]['semantic']['db']);
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
        foreach ($this->getIds() as $id) {
            $entity = $this->getEntityManager()->find($this->getEntityClass(), $id);
            if ($entity === null) {
                $class = $this->getEntityClass();
                $entity = new $class;
            }
            $changes = $this->charge($entity, $id);
            if (!empty($changes)) {
                $this->getEntityManager()->persist($entity);
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