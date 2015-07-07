<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 06.07.2015
 * Time: 19:06
 */

namespace AotTest\Functional\Sviaz\Rule\AssertedLink;

use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;

class MorphologyMatchingTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $this->getMorphologyMatching();

        $this->assertTrue(true);
    }

    public function testConstruct_will_throw_RuntimeException_incorrect_argument_type()
    {
        try {
            \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
                [],
                \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
                \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base::class
            );
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals(\RuntimeException::class, get_class($e));
            $this->assertEquals("incorrect argument type", $e->getMessage());
        }

        try {
            \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
                \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base::class,
                \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
                132
            );
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals(\RuntimeException::class, get_class($e));
            $this->assertEquals("incorrect argument type", $e->getMessage());
        }

    }

    public function testConstruct_will_throw_RuntimeException_class_not_found()
    {
        $invalid_object_class = "1_" . \stdClass::class;

        $expected_message = "class $invalid_object_class not found";


        try {
            \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
                $invalid_object_class,
                \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
                \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base::class
            );
            $this->fail();
        } catch
        (\RuntimeException $e) {
            $this->assertEquals(\RuntimeException::class, get_class($e));
            $this->assertEquals($expected_message, $e->getMessage());
        }

        try {
            \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
                \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Base::class,
                \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
                $invalid_object_class
            );
            $this->fail();
        } catch (\RuntimeException $e) {
            $this->assertEquals(\RuntimeException::class, get_class($e));
            $this->assertEquals($expected_message, $e->getMessage());
        }
    }


    public function testAttemptSuccess()
    {
        $MorphologyMatching = \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base::class,
            \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base::class
        );

        /** @var Suschestvitelnoe $esli */
        $esli = $this->getSafeMockLocal(Suschestvitelnoe::class, ['__get', '__set', 'getMorphology', 'getMorphologyByClass_TEMPORARY']);
        $esli->padeszh = new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij;
        //$esli->padeszh = new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij();


        /** @var Suschestvitelnoe $povishenie */
        $povishenie = $this->getSafeMockLocal(Suschestvitelnoe::class, ['__get', '__set', 'getMorphology', 'getMorphologyByClass_TEMPORARY']);
        $povishenie->padeszh = new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij;


        $result = $MorphologyMatching->attempt(
            \Aot\Sviaz\SequenceMember\Word\Base::create($esli),
            \Aot\Sviaz\SequenceMember\Word\Base::create($povishenie)
        );


        $this->assertTrue($result);
    }

    public function testAttemptFail()
    {
        $MorphologyMatching = \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base::class,
            \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base::class
        );

        /** @var Suschestvitelnoe $esli */
        $esli = $this->getSafeMockLocal(Suschestvitelnoe::class, ['__get', '__set', 'getMorphology', 'getMorphologyByClass_TEMPORARY']);
        //$esli->padeszh = new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij;
        $esli->padeszh = new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij();


        /** @var Suschestvitelnoe $povishenie */
        $povishenie = $this->getSafeMockLocal(Suschestvitelnoe::class, ['__get', '__set', 'getMorphology', 'getMorphologyByClass_TEMPORARY']);
        $povishenie->padeszh = new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij;


        $result = $MorphologyMatching->attempt(
            \Aot\Sviaz\SequenceMember\Word\Base::create($esli),
            \Aot\Sviaz\SequenceMember\Word\Base::create($povishenie)
        );


        $this->assertFalse($result);
    }


}