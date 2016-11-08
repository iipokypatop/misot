<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16/07/15
 * Time: 17:44
 */

namespace Aot\RussianMorphology\ChastiRechi\ChislitelnoeSostavnoe;


use Aot\RussianMorphology\Slovo;
use Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology;

/**
 * Class Base
 * @package Aot\RussianMorphology\ChastiRechi\Chislitelnoe
 * @property Morphology\Vid\Base $vid
 * @property Morphology\Tip\Base $tip
 * @property Morphology\Podvid\Base $podvid
 * @property Morphology\Chislo\Base $chislo
 * @property Morphology\Rod\Base $rod
 * @property Morphology\Padeszh\Base $padeszh
 */
class Base extends \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base
{
    /** @var \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base[] */
    protected $parts = [];


    /**
     * @param double $value
     */
    public function setDigitalView($value)
    {
        assert(is_double($value));
        $this->digital_view = $value;
    }

    public static function getMorphology()
    {
        return [
            'vid' => Morphology\Vid\Base::class,
            'tip' => Morphology\Tip\Base::class,
            'podvid' => Morphology\Podvid\Base::class,
            'chislo' => Morphology\Chislo\Base::class,
            'rod' => Morphology\Rod\Base::class,
            'padeszh' => Morphology\Padeszh\Base::class
        ];
    }

    /**
     * Chislitelnoe constructor.
     * @param $text
     * @param Morphology\Vid\Base $vid
     * @param Morphology\Tip\Base $tip
     * @param Morphology\Podvid\Base $podvid
     * @param Morphology\Chislo\Base $chislo
     * @param Morphology\Rod\Base $rod
     * @param Morphology\Padeszh\Base $padeszh
     * @return static
     * @throws \Aot\Exception
     */
    public static function create(
        $text,
        Morphology\Vid\Base $vid,
        Morphology\Tip\Base $tip,
        Morphology\Podvid\Base $podvid,
        Morphology\Chislo\Base $chislo,
        Morphology\Rod\Base $rod,
        Morphology\Padeszh\Base $padeszh
    ) {
        throw new \Aot\Exception("Используйте метод createNew() класса " . \Aot\RussianMorphology\ChastiRechi\ChislitelnoeSostavnoe\Base::class);
    }

    /**
     * @param string $text
     * @param \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base[] $parts
     * @return Base
     * @throws \Aot\Exception
     */
    public static function createNew(
        $text,
        array $parts
    ) {
        assert(is_string($text));
        foreach ($parts as $part) {
            // TODO: http://redmine.mivar.ru/issues/3654
//            assert(is_a($part, \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class, true));
        }
        if (count($parts) < 2) {
            throw new \Aot\Exception("Составное числительное должно содержать как минимум два числительных.");
        }

        $ob = new static($text);
        $ob->parts = $parts;

        $last_part = $parts[count($parts) - 1];

        // TODO: http://redmine.mivar.ru/issues/3654
        if (is_a($last_part, \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base::class, true)) {
            $ob->vid = $last_part->vid;
            $ob->tip = $last_part->tip;
            $ob->podvid = $last_part->podvid;
            $ob->chislo = $last_part->chislo;
            $ob->rod = $last_part->rod;
            $ob->padeszh = $last_part->padeszh;
        } else {
            $ob->vid = isset($morphology_last['vid']) ? $last_part->vid : \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Vid\ClassNull::create();
            $ob->tip = isset($morphology_last['tip']) ? $last_part->tip : \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Tip\ClassNull::create();
            $ob->podvid = isset($morphology_last['podvid']) ? $last_part->podvid : \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Podvid\ClassNull::create();
            $ob->chislo = isset($morphology_last['chislo']) ? $last_part->chislo : \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Chislo\ClassNull::create();
            $ob->rod = isset($morphology_last['rod']) ? $last_part->rod : \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Rod\ClassNull::create();
            $ob->padeszh = isset($morphology_last['padeszh']) ? $last_part->padeszh : \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Morphology\Padeszh\ClassNull::create();
        }

        $ob->digital_view = \Aot\Tools\ConverterOfNumeral\Base::convertToDigital($text);
        $ob->initial_form = 'не забыть задать начальную форму';
        return $ob;
    }

    /**
     * @return double
     */
    public function getDigitalView()
    {
        if ($this->digital_view === null) {
            $this->digital_view = \Aot\Tools\ConverterOfNumeral\Base::convertToDigital($this->initial_form);
        }
        return $this->digital_view;
    }

}