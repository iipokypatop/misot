<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\Podchinitrelnaya\Filters;


use Aot\RussianMorphology\ChastiRechi\Glagol\Base as Glagol;
use Aot\RussianMorphology\ChastiRechi\Predlog\Base as Predlog;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base as Prilagatelnoe;
use Aot\RussianMorphology\ChastiRechi\Soyuz\Base;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base as SuschestvitelnoePadeszhBase;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;
use Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq;
use MivarTest\PHPUnitHelper;
use \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use Aot\Sviaz\Rule\AssertedMember\PresenceRegistry;
use Aot\Text\GroupIdRegistry;

class HomogeneityTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        //\Aot\Sviaz\Homogeneity\Homogeneity::create([],);
        //\Aot\Sviaz\Homogeneity\HomogeneitySupposed::create([],);
    }

    public function testHomogeneitySupposed()
    {
        $this->markTestSkipped("очень медленно выполняется. пропускаем..");
        $creator = \Aot\Sviaz\CreateSequenceFromText::create();
        //$creator->convert("По засыпанной красными и жёлтыми листьями дороге в новую деревенскую школу я встретил Сашу, Мишу и тебя.");
        //$creator->convert("Деревья и травы летом и в самом начале осени сочны и свежи.");
        $creator->convert("Пушкин собирал песни и сказки и в Одессе, и в Кишинёве, и в Псковской губернии.");
        $sequences = $creator->getSequence();

        $members = [];
        foreach ($sequences[0] as $member) {
            $members[] = $member;
        }

        $homogeneities = [];
        foreach ($sequences as $sequence) {
            $homogeneities[] = $sequence->getHomogeneitySupposed();
        }


    }


}