<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 25/11/15
 * Time: 01:15
 */

namespace Aot\Sviaz\Processors;

class OffsetManager
{
    public $offset_by_misot = []; // смещение позиции по слитым элементам в МИСОТе
    public $offset_by_aot = []; // смещение позиции по пропущенной пунктуации в АОТе
    public $nonexistent_aot = [];
    public $nonexistent_misot = [];

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
    }

    /**
     * обновляем массив смещения по аоту (пунктуация)
     * @param int $id
     * @param int $offset
     */
    public function refreshAotOffset($id, $offset = 0)
    {
        assert(is_int($id));
        assert(is_int($offset));
        if (!empty($this->offset_by_aot)) {
            $value = end($this->offset_by_aot);
            $this->offset_by_aot[$id] = $value + $offset;
        } else {
            $this->offset_by_aot[$id] = $offset;
        }
    }

    /**
     * обновляем массив смещения по мисоту (предлог)
     * @param int $id
     * @param int $offset
     */
    public function refreshMisotOffset($id, $offset = 0)
    {
        assert(is_int($id));
        assert(is_int($offset));
        if (!empty($this->offset_by_misot)) {
            $value = end($this->offset_by_misot);
            $this->offset_by_misot[$id] = $value + $offset;
        } else {
            $this->offset_by_misot[$id] = $offset;
        }
    }

    /**
     * Получение ключа элемента с учетом смещения позиции из-за композитных элементов (слово+предлог)
     * @param int $id
     * @return int
     */
    public function getMisotKeyBySentenceWordKey($id)
    {
        assert(is_int($id));
        if (!empty($this->offset_by_misot[$id])) {
            $id -= $this->offset_by_misot[$id];
            return $id;
        }
        return $id;
    }

    /**
     * Получение ключа слова из предложения по ключу из МИСОТа
     * @param int $id
     * @return int
     */
    public function getSentenceWordKeyByMisotKey($id)
    {
        assert(is_int($id));
        if (!empty($this->offset_by_misot[$id])) {
            $id += $this->offset_by_misot[$id];
            return $id;
        }
        return $id;
    }

    /**
     * Получение ключа элемента с учетом смещения позиции по аоту
     * @param int $id
     * @return int
     */
    public function getAotKeyBySentenceWordKey($id)
    {
        assert(is_int($id));
        if (!empty($this->offset_by_aot[$id])) {
            $id -= $this->offset_by_aot[$id];
            return $id;
        }
        return $id;
    }

    /**
     * Получение ключа слова из предложения по ключу из АОТа
     * @param int $id
     * @return int
     */
    public function getSentenceWordKeyByAotKey($id)
    {
        assert(is_int($id));
        if (!empty($this->offset_by_aot[$id])) {
            $id += $this->offset_by_aot[$id];
            return $id;
        }
        return $id;
    }


    /**
     * Добавить элемент к списку несуществующих значений АОТа
     * @param int $id
     */
    public function addToNonexistentAot($id)
    {
        assert(is_int($id));
        $this->nonexistent_aot[$id] = 1;
    }

    /**
     * Добавить элемент к списку несуществующих значений МИСОТа
     * @param int $id
     */
    public function addToNonexistentMisot($id)
    {
        assert(is_int($id));
        $this->nonexistent_misot[$id] = 1;
    }
}