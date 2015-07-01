<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 24.06.2015
 * Time: 16:16
 */

namespace Sviaz\Rule;

use Sviaz\Rule\AssertedWord\Depended;
use Sviaz\Rule\AssertedWord\Main;
use Symfony\Component\Yaml\Exception\RuntimeException;

class Base
{
    const MAIN_VALUE = 1;
    const MAIN_ANY = 2;
    const MAIN_EQ_DEPENDED = 3;

    const DEPENDED_VALUE = 11;
    const DEPENDED_ANY = 12;
    const DEPENDED_EQ_MAIN = 13;

    const DEPENDED_POSITION_AFTER_MAIN = 101;
    const DEPENDED_POSITION_BEFORE_MAIN = 102;
    const DEPENDED_POSITION_ANY = 103;

    protected $main;
    protected $depended;

    protected $main_morph = [];
    protected $depended_morph = [];
    protected $asserted_depended_position = self::DEPENDED_ANY;

    public function __construct()
    {
        $this->main = Main::create();
        $this->depended = Depended::create();
    }

    public function addMorphologyMain($morph, $type)
    {
        if ($type === static::MAIN_VALUE) {

            if (!$morph instanceof \RussianMorphology\ChastiRechi\MorphologyBase) {
                throw new RuntimeException(' $morph must be instanceof \RussianMorphology\ChastiRechi\MorphologyBase');
            }

            $this->main->assertMorphology($morph);

        } elseif ($type === static::MAIN_ANY) {

            // тут  ничего делать и не надо,но возможно эта ветка все же

        } elseif ($type === static::MAIN_EQ_DEPENDED) {

            if (in_array($morph, $this->getAllowedMorphologyEq(), true)) {
                throw new RuntimeException(' $morph is not allowed php class');
            }

            $this->main_morph[get_class($morph)] = $morph;

        } else {

            throw new RuntimeException();
        }

        return $this;
    }

    public function addMorphologyDepended($morph, $type)
    {
        if ($type === static::DEPENDED_VALUE) {

            if (!$morph instanceof \RussianMorphology\ChastiRechi\MorphologyBase) {
                throw new RuntimeException(' $morph must be instanceof \RussianMorphology\ChastiRechi\MorphologyBase');
            }

            $this->depended->assertMorphology($morph);

        } elseif ($type === static::DEPENDED_ANY) {

            // тут  ничего делать и не надо,но возможно эта ветка все же

        } elseif ($type === static::DEPENDED_EQ_MAIN) {

            if (in_array($morph, $this->getAllowedMorphologyEq(), true)) {
                throw new RuntimeException(' $morph is not allowed php class');
            }

            $this->depended_morph[get_class($morph)] = $morph;

        } else {

            throw new RuntimeException();
        }

        return $this;
    }

    protected function getAllowedMorphologyEq()
    {
        return [
            \RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class,
        ];
    }


    protected function resolve()
    {
        return false;
    }

    /**
     * @param $asserted_depended_position
     */
    public function assertDependedPosition($asserted_depended_position)
    {
        if (
            $asserted_depended_position !== static::DEPENDED_POSITION_AFTER_MAIN
            && $asserted_depended_position !== static::DEPENDED_POSITION_BEFORE_MAIN
            && $asserted_depended_position !== static::DEPENDED_POSITION_ANY
        ) {
            throw new RuntimeException("not allowed position");
        }

        $this->asserted_depended_position = $asserted_depended_position;
    }
}

