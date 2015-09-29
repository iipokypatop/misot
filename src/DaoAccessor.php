<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 13/08/15
 * Time: 18:42
 */

namespace Aot;

trait DaoAccessor
{
    protected $dao;
    protected $conn_db = "host=test-db.mivar.pro dbname=mivar_semantic_1890 user=postgres password=@Mivar123User@";

    /**
     * @param $name
     * @return int[] | null
     */
    protected function get($name)
    {
        if (empty($this->getDao())) {
            throw new \RuntimeException("No dao");
        }

        $callback = [$this->getDao(), 'get' . $name];
        if (!is_callable($callback)) {
            throw new \RuntimeException("No method " . $callback[1] . " in class " . get_class($callback[0]));
        }

        $value = call_user_func($callback);

        if ($value === null) {
            return null;
        } else if (is_scalar($value)) {
            return $value;
        } else if (is_object($value)) {

            if (is_a($value, \Doctrine\Common\Collections\Collection::class)) {
                $ids = [];
                foreach ($value as $item) {
                    $ids[] = $item->getId();
                }
                return $ids;
            } else {
                return $value->getId();
            }

        }

        throw new \RuntimeException("Unsupported value type or resource");
    }

    protected function set($name)
    {
        throw new \RuntimeException("not supported");
    }

    /**
     * @return object
     */
    public function getDao()
    {
        return $this->dao;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return \SemanticPersistence\API\SemanticAPI::getAPI($this->conn_db)->getEntityManager();
    }

    /**
     * @return \SemanticPersistence\API\SemanticAPI
     */
    public function getAPI()
    {
        return \SemanticPersistence\API\SemanticAPI::getAPI($this->conn_db);
    }
}