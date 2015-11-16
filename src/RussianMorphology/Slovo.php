<?php

namespace Aot\RussianMorphology;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.06.2015
 * Time: 22:05
 */
abstract class Slovo implements \Aot\Unit
{
    const RENDER_NULL = 1;
    const RENDER_HTML = 2;
    const RENDER_SHORT = 3;

    protected $text;
    protected $initial_form;

    /**
     * @return string
     */
    public function getInitialForm()
    {
        return $this->initial_form;
    }

    /**
     * @param string $initial_form
     */
    public function setInitialForm($initial_form)
    {
        assert(is_string($initial_form));
        assert('' !== ($initial_form));
        $this->initial_form = $initial_form;
    }

    private $deadRows = [];

    /** @var  \SemanticPersistence\Entities\SemanticEntities\Word */
    protected $dao;

    /**
     * @return \SemanticPersistence\Entities\SemanticEntities\Word
     */
    public function getDao()
    {
        return $this->dao;
    }

    protected $storage = [];

    protected function init()
    {
        foreach ($this->getMorphology() as $name => $class) {
            $this->storage[$name] = null;
        }
        //$this->pre_hash = spl_object_hash($this);
    }
    //public $pre_hash;

    /**
     * @return string[]
     */
    public function getMorphology()
    {
        return [];
    }

    public function __get($name)
    {
        if (array_key_exists($name, static::getMorphology())) {
            return $this->storage[$name];
        }

        throw new \RuntimeException("unsupported field name exception");
    }

    public function __set($name, $value)
    {
        if (!array_key_exists($name, static::getMorphology())) {
            throw new \RuntimeException("unsupported field exception");
        }

        if (!is_subclass_of($value, static::getMorphology()[$name])) {
            throw new \RuntimeException("incorrect field type");
        }

        $this->storage[$name] = $value;
    }


    /**
     * @param string $text
     */
    protected function __construct($text)
    {
        assert(!empty($text));

        $this->text = $text;

        $this->init();
    }

    /**
     * @param $classname
     * @return \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Base |  null
     */
    public function getMorphologyByClass_TEMPORARY($classname)
    {

        $values = [];
        foreach ($this->storage as $name => $value) {
            if ($value instanceof $classname) {
                $values[] = $value;
            }
        }


        /// TEMPORARY start
        foreach ($this as $name => $value) {

            if ($value instanceof $classname) {
                $values[] = $value;
            }
        }
        /// TEMPORARY end

        if (count($values) > 1) {
            throw new \RuntimeException("more than one subclass of $classname");
        }

        if (count($values) === 1) {
            return $values[0];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return \Aot\RussianMorphology\ChastiRechi\MorphologyBase[]
     */
    public function getMorphologyStorage()
    {
        return $this->storage;
    }

    /**
     * @return array
     */
    public function getMorphologyShort()
    {
        return $this->getMorphologyFull(self::RENDER_SHORT);
    }


    /**
     * @return array
     */
    public function getDeadRows()
    {
        return $this->deadRows;
    }

    /**
     * @param int $render_as
     * @return array
     */
    public function getMorphologyFull($render_as = self::RENDER_HTML)
    {
        $tds = [];
        $morphology_tmp = [];
        foreach ($this->getMorphologyStorage() as $morphology) {

            $group_id = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getGroupIdByPriznakClass(get_class($morphology));
            $variant_id = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getVariantIdByPriznakClass(get_class($morphology));

            if (null !== $group_id && null !== $variant_id) {
                $morphology_tmp[$group_id] = $variant_id;
            }
        }


        foreach (\Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getClasses() as $group_id => $variants) {

            if (empty($morphology_tmp[$group_id])) {
                if ($render_as === self::RENDER_NULL) {
                } elseif ($render_as === self::RENDER_HTML
                ) {
                    $tds[] = "<td>-</td>";
                } else {
                    if ($render_as === self::RENDER_SHORT) {
                        // none
                    }
                }

                if (!isset($this->deadRows[$group_id])) {
                    $this->deadRows[$group_id] = true;
                }
                continue;
            }


            $name_long = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::getNames()[$morphology_tmp[$group_id]];

            $name_short = [];
            foreach (preg_split("/\\s+/u", $name_long) as $word) {
                $name_short [] = mb_substr($word, 0, 3) . ".";
            }
            if ($render_as === self::RENDER_NULL) {

            } else {
                if ($render_as === self::RENDER_HTML) {
                    $color = "rgb(" . join(',', [
                            255 - floor($morphology_tmp[$group_id] % 10 * 30),
                            255 - floor($morphology_tmp[$group_id] % 10 * 30),
                            255 - floor($morphology_tmp[$group_id] % 10 * 30),
                        ]) . ")";
                    $tds[] = "<td title='{$name_long}' style='background-color: {$color}'>" . join(";",
                            $name_short) . ";" . "</td>";
                } else {
                    if ($render_as === self::RENDER_SHORT) {
                        $tds[] = join(";", $name_short) . ";";
                    }
                }
            }

            $this->deadRows[$group_id] = false;
        }


        return $tds;
    }
}

