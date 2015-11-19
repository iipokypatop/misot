<?php
namespace AotTest\Functional\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator;
/**
 * Created by PhpStorm.
 * User: saraev
 * Date: 19.11.2015
 * Time: 13:00
 */
class EqTest extends \AotTest\AotDataStorage
{
    public function testMatch_Throws_Exeption()
    {
        $operator_eq = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create();
        $left = \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Base::create();
        $right = $left;
        try {
            $result = $operator_eq->match($left, $right);
            $this->fail("Не должно быть");
        } catch (\LogicException $e) {
            $this->assertEquals("Must not be equal", $e->getMessage());
        }
    }

    public function testMatch_Returns_True_Left_And_Right_Params_Has_Equal_Classes()
    {
        $operator_eq = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create();
        $left = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::create();
        $right = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Muzhskoi::create();

        $this->assertEquals(true, $operator_eq->match($left, $right));
    }

    public function testMatch_Returns_True_Left_Param_Is_Null()
    {
        $operator_eq = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create();
        $left = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::create();
        $right = \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Base::create();
        $this->assertEquals(true, $operator_eq->match($left, $right));
    }

    public function testMatch_Returns_True_Right_Param_Is_Null()
    {
        $operator_eq = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create();
        $right = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::create();
        $left = \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Morphology\Base::create();

        $this->assertEquals(true, $operator_eq->match($left, $right));
    }

    public function testMatch_Returns_True_In_Foreach()
    {
        $left = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $right = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $operator_eq = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create();
        $this->assertEquals(true, $operator_eq->match($left, $right));
    }

    public function testMatch_Returns_False()
    {
        $left = \Aot\RussianMorphology\ChastiRechi\Prichastie\Morphology\Vremya\Buduschee::create();
        $right = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $operator_eq = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create();
        $this->assertEquals(false, $operator_eq->match($left, $right));
    }
}