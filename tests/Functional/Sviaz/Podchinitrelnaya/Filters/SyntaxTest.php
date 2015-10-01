<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 30.07.2015
 * Time: 14:03
 */

namespace AotTest\Functional\Sviaz\Podchinitrelnaya\Filters;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\Glagol\Base as Glagol;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;

use Aot\RussianMorphology\ChastiRechi\Predlog\Base as Predlog;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base as Prilagatelnoe;
use Aot\RussianMorphology\ChastiRechi\Soyuz\Base;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base as SuschestvitelnoePadeszhBase;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq;
use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use MivarTest\PHPUnitHelper;

use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;


class SyntaxTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $filter_syntax=\Aot\Sviaz\Podchinitrelnaya\Filters\Syntax::create();
        //$filter_syntax->run("test");
        /*
        $word1='эра';
        $api = \SemanticPersistence\API\SemanticAPI::getAPI("host=192.168.10.51 dbname=mivar_semantic_new user=postgres password=@Mivar123User@");
        $qb = $api->createQueryBuilder();
        $qb->add('select', 'w')
            ->add('from', 'words w')
            ->add('where', 'w.name = :word')
            ->setParameter('word', $word1);
        $query = $qb->getQuery();
        $result = $query->getResult();
        */


        $processor = \Aot\Sviaz\Processor\Base::create();

        $rule = $this->getRule1();
        $rule = $this->getRule2();

        $sequences = $processor->go(
            $this->getNormalizedMatrix1(),
            [$rule]
        );


        $sviazi_container = [];
        foreach ($sequences as $index => $sequence) {
            $sviazi_container[$index] = $sequence->getSviazi();
        }

        $result = array_filter($sviazi_container);

        $pretty = $this->pretty(
            $result
        );

        //echo join("\n", $pretty);
    }


}