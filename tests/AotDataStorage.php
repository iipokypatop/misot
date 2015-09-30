<?php
namespace AotTest;

use Aot\RussianMorphology\ChastiRechi\Chastica\Base as Chastica;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base as Deeprichastie;
use Aot\RussianMorphology\ChastiRechi\Glagol\Base as Glagol;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use MivarTest\PHPUnitHelper;


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Text\GroupIdRegistry as GroupIdRegistry;
use Aot\RussianMorphology\ChastiRechi\Predlog\Base as Predlog;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 23:53
 */
class AotDataStorage extends \MivarTest\Base
{
    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base[][] $sviazi
     * @return string[]
     */
    public static function pretty(array $sviazi)
    {
        $result = [];

        foreach ($sviazi as $index => $links) {
            $data = [];
            $data [] = $index;

            foreach ($links as $link) {
                /** @var \Aot\Sviaz\Podchinitrelnaya\Base $link */

                $main = $link->getMainSequenceMember();
                $depended = $link->getDependedSequenceMember();

                if (
                    $main instanceof \Aot\Sviaz\SequenceMember\Word\Base &&
                    $depended instanceof \Aot\Sviaz\SequenceMember\Word\Base
                ) {

                    $data[] =
                        $main->getSlovo()->getText() . "(" . ChastiRechiRegistry::getIdByMockClass(get_class($main->getSlovo())) . ")"
                        . "->" .
                        $depended->getSlovo()->getText() . "(" . ChastiRechiRegistry::getIdByMockClass(get_class($depended->getSlovo())) . ")";
                }
            }

            $result[] = join(" ", $data);
        }

        return $result;
    }

    protected function getWordsAndPunctuation()
    {
        <<<TEXT
 Если повышение тарифов на электроэнергию не будет отменено, с понедельника баррикады из мусорных контейнеров на
 проспекте Маршала Баграмяна будут продвигаться вперед по направлению к президентскому дворцу.
TEXT;

        $esli[0] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($esli[0], 'text', 'если');
        $esli[1] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($esli[1], 'text', 'если');
        $esli[2] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($esli[2], 'text', 'если');
        $esli[3] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($esli[3], 'text', 'если');

        $povishenie[0] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($povishenie[0], 'text', 'повышение');
        $povishenie[1] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($povishenie[1], 'text', 'повышение');

        $tarifov[0] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($tarifov[0], 'text', 'тарифов');
        $tarifov[1] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($tarifov[1], 'text', 'тарифов');
        $tarifov[2] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($tarifov[2], 'text', 'тарифов');

        $zapiztaya = $this->getSafeMockLocal(Zapiataya::class, ['getText']);

        $na[0] = $this->getSafeMockLocal(Predlog::class, ['getText'], ['getText']);
        PHPUnitHelper::setProtectedProperty($na[0], 'text', 'на');

        $elektoenergiu[0] = $this->getSafeMockLocal(Suschestvitelnoe::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($elektoenergiu[0], 'text', 'электроэнергию');

        $ne[0] = $this->getSafeMockLocal(Chastica::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($ne[0], 'text', 'не');

        $budet[0] = $this->getSafeMockLocal(Glagol::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($budet[0], 'text', 'будет');
        $budet[1] = $this->getSafeMockLocal(Glagol::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($budet[1], 'text', 'будет');

        $otmeneno[0] = $this->getSafeMockLocal(Deeprichastie::class, ['getText']);
        PHPUnitHelper::setProtectedProperty($otmeneno[0], 'text', 'отменено');

        return [
            'esli' => $esli,
            'povishenie' => $povishenie,
            'tarifov' => $tarifov,
            'zapiztaya' => $zapiztaya,
            'na' => $na,
            'elektoenergiu' => $elektoenergiu,
            'ne' => $ne,
            'budet' => $budet,
            'otmeneno' => $otmeneno,
        ];
    }

    /**
     * @param $class
     * @param array $except_methods
     * @return Chastica|Deeprichastie|Glagol|Suschestvitelnoe|Zapiataya|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSafeMockLocal($class, array $except_methods = ['getText'])
    {
        return call_user_func_array([$this, 'getSafeMock'], func_get_args());
    }

    /**
     * @return \Aot\Text\Matrix
     */
    public function getMatrix()
    {
        $mixed = $this->getWordsAndPunctuation();

        $matrix = \Aot\Text\Matrix::create($mixed);

        return $matrix;
    }

    /**
     * @return \Aot\Text\NormalizedMatrix
     */
    public function getNormalizedMatrix()
    {
        $matrix = $this->getMatrix();

        $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

        return $normalized_matrix;
    }

    /**
     * @return \Aot\Sviaz\Rule\Base
     */
    public function getRule()
    {
        $main = \Aot\Sviaz\Rule\AssertedMember\Main::create();

        $depended = \Aot\Sviaz\Rule\AssertedMember\Depended::create();

        $rule = \Aot\Sviaz\Rule\Base::create(
            $main,
            $depended
        );

        /** @var  $Suschestvitelnoe \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base */
        $Suschestvitelnoe = $this->getSafeMock(\Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base::class);
        $Suschestvitelnoe->chislo = new \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe();


        $asserted_match = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching::create(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class,
            \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class
        );

        $rule->addAssertedMatching($asserted_match);

        return $rule;

    }

    protected function getMorphologyMatching()
    {
        $MorphologyMatching = \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatching::create(
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base::class,
            \Aot\Sviaz\Rule\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Base::class
        );

        return $MorphologyMatching;
    }


    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base
     */
    public function getAssertedMemberBuilder_main()
    {
        $builder =
            \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                ChastiRechiRegistry::SUSCHESTVITELNOE,
                RoleRegistry::OTNOSHENIE
            )
                ->text("text text");

        $builder->morphologyEq(MorphologyRegistry::PADESZH_DATELNIJ);
        $builder->check(MemberCheckerRegistry::PredlogPeredSlovom);

        return $builder;
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base
     */
    public function getAssertedMemberBuilder_depended()
    {
        $builder =
            \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                ChastiRechiRegistry::PRILAGATELNOE,
                RoleRegistry::OTNOSHENIE
            )
                ->text("text text");


        return $builder;
    }

    /**
     * @return \Aot\Sviaz\Rule\AssertedMember\Builder\Base
     */
    public function getAssertedMemberBuilder_member()
    {
        $builder =
            \Aot\Sviaz\Rule\AssertedMember\Builder\Third::create(
                ChastiRechiRegistry::SUSCHESTVITELNOE
            )
                ->position(PositionRegistry::POSITION_BEFORE_DEPENDED)
                ->text("text text");


        return $builder;
    }

    /**
     * @return \Aot\Sviaz\Sequence
     */
    public function getRawSequence()
    {
        $raw_member_builder = \Aot\Sviaz\SequenceMember\RawMemberBuilder::create();

        $raw_sequences = $raw_member_builder->getRawSequences(
            $this->getNormalizedMatrix()
        );

        /** @var \Aot\Sviaz\SequenceMember\Word\Base[] | \Aot\Sviaz\SequenceMember\Base[] $raw_sequence */
        $raw_sequence = $raw_sequences[0];

        return $raw_sequence;
    }

}