<?php
/**
 * Created by PhpStorm.
 * User: s.kharchenko
 * Date: 25/11/15
 * Time: 01:15
 */

namespace Aot\Sviaz\Processors\Aot;

class OffsetManager
{
    public $offset_by_misot = []; // смещение позиции по слитым элементам в МИСОТе
    public $offset_by_aot = []; // смещение позиции по пропущенной пунктуации в АОТе
    public $nonexistent_aot = [];
    public $nonexistent_misot = [];
    protected $aotOffset = 0; // смещение по аоту
    protected $misotOffset = 0; // смещение по мисоту

    public static function create()
    {
        return new static();
    }

    protected function __construct()
    {
    }

    /**
     * обновляем массив смещения по аоту (пунктуация)
     */
    public function refreshAotOffset()
    {
        $this->offset_by_aot[] = $this->aotOffset;
    }

    /**
     * Увеличение смешения по АОТу
     */
    public function increaseAotOffset()
    {
        $this->aotOffset++;
    }

    /**
     * обновляем массив смещения по мисоту (предлог)
     */
    public function refreshMisotOffset()
    {
        $this->offset_by_misot[] = $this->misotOffset;
    }


    /**
     * Увеличение смешения по МиСОТу
     */
    public function increaseMisotOffset()
    {
        $this->misotOffset++;
    }


    /**
     * Получение ключа элемента с учетом смещения позиции из-за композитных элементов (слово+предлог)
     * @param int $id
     * @return int
     */
    public function getMisotKeyBySentenceWordKey($id)
    {
        assert(is_int($id));
        foreach ($this->offset_by_misot as $misot_key => $offset) {
            if (($misot_key + $offset) === $id) {
                return $misot_key;
            }
        }

        throw new \LogicException("Unknown id in misot: " . $id);
    }

    /**
     * Проверка на существование элемента в МиСОТе по ключу из предложения
     * @param int $id
     * @return bool
     */
    public function isMisotElementExistBySentenceWordKey($id)
    {
        assert(is_int($id));
        if (empty($this->nonexistent_misot[$id])) {
            return true;
        }
        return false;
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
     * Получение ключа элемента модели АОТа по ключу из слова предложения
     * @param int $id
     * @return int
     */
    public function getAotKeyBySentenceWordKey($id)
    {
        assert(is_int($id));

        foreach ($this->offset_by_aot as $aot_key => $offset) {
            if (($aot_key + $offset) === $id) {
                return $aot_key;
            }
        }

        throw new \LogicException("Unknown id in aot: " . $id);
    }

    /**
     * Проверка на существование элемента в АОТе по ключу из предложения
     * @param int $id
     * @return bool
     */
    public function isAotElementExistBySentenceWordKey($id)
    {
        assert(is_int($id));
        if (empty($this->nonexistent_aot[$id])) {
            return true;
        }
        return false;
    }

    /**
     * Получение ключа слова из предложения по ключу из АОТа
     * @param int $id
     * @return int
     */
    public function getSentenceWordKeyByAotKey($id)
    {
        assert(is_int($id));
        if (isset($this->offset_by_aot[$id])) {
            $id += $this->offset_by_aot[$id];
            return $id;
        }

//        print_r([
//            'aot_key' => $id,
//            'offset_by_aot' => $this->offset_by_aot,
//            'offset_by_aot' => $this->offset_by_aot,
//        ]);
//        die();
        throw new \LogicException("Unknown aot key: " . $id);
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