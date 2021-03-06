<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 03.07.2015
 * Time: 13:14
 */

namespace AotTest\Functional\Sviaz\Rule\AssertedMember\Checker;


class PredlogPeredSlovomTest extends \AotTest\AotDataStorage
{
    public function testExecuteReturns_TRUE()
    {
        /** @var  $Suschestvitelnoe \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base */
        $Suschestvitelnoe = $this->getSafeMock(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class);

        /** @var  $predlog \Aot\RussianMorphology\ChastiRechi\Predlog\Base */
        $predlog = $this->getSafeMock(\Aot\RussianMorphology\ChastiRechi\Predlog\Base::class);


        $member[] = \Aot\Sviaz\SequenceMember\Word\Base::create($predlog);
        $member[] = \Aot\Sviaz\SequenceMember\Word\Base::create($Suschestvitelnoe);

        $seq = \Aot\Sviaz\Sequence::create();

        $seq->append($member[0]);
        $seq->append($member[1]);

        $checker = new \Aot\Sviaz\Rule\AssertedMember\Checker\PredlogPeredSlovom;

        $asserted_member =   \Aot\Sviaz\Rule\AssertedMember\Main::create();

        $result = $checker->check($seq, $asserted_member, $member[1]);

        $this->assertTrue($result);

    }

    public function testExecuteReturns_FALSE()
    {
        /** @var  $Suschestvitelnoe \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base */
        $Suschestvitelnoe = $this->getSafeMock(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class);

        /** @var  $Soyuz \Aot\RussianMorphology\ChastiRechi\Soyuz\Base */
        $Soyuz = $this->getSafeMock(\Aot\RussianMorphology\ChastiRechi\Soyuz\Base::class);


        $member[] = \Aot\Sviaz\SequenceMember\Word\Base::create($Soyuz);
        $member[] = \Aot\Sviaz\SequenceMember\Word\Base::create($Suschestvitelnoe);

        $seq = \Aot\Sviaz\Sequence::create();

        $seq->append($member[0]);
        $seq->append($member[1]);

        $checker = new \Aot\Sviaz\Rule\AssertedMember\Checker\PredlogPeredSlovom;

        $asserted_member = \Aot\Sviaz\Rule\AssertedMember\Main::create();

        $result = $checker->check($seq, $asserted_member, $member[1]);

        $this->assertFalse($result);
    }
}