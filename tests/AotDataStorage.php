<?php
namespace AotTest;

use Aot\RussianMorphology\ChastiRechi\Chastica\Base as Chastica;
use Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base as Deeprichastie;
use Aot\RussianMorphology\ChastiRechi\Glagol\Base as Glagol;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;
use MivarTest\PHPUnitHelper;

/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 04.07.2015
 * Time: 23:53
 */
class AotDataStorage extends \MivarTest\Base
{
    protected function getWordsAndPunctuation()
    {
        <<<TEXT
 Если повышение тарифов на электроэнергию не будет отменено, с понедельника баррикады из мусорных контейнеров на
 проспекте Маршала Баграмяна будут продвигаться вперед по направлению к президентскому дворцу.
TEXT;

        $esli[0] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($esli[0], 'text', 'если');
        $esli[1] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($esli[1], 'text', 'если');
        $esli[2] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($esli[2], 'text', 'если');
        $esli[3] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($esli[3], 'text', 'если');

        $povishenie[0] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($povishenie[0], 'text', 'повышение');
        $povishenie[1] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($povishenie[1], 'text', 'повышение');

        $tarifov[0] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($tarifov[0], 'text', 'тарифов');
        $tarifov[1] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($tarifov[1], 'text', 'тарифов');
        $tarifov[2] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($tarifov[2], 'text', 'тарифов');

        $zapiztaya = $this->getSafeMockLocal(Zapiataya::class);

        $na[0] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($na[0], 'text', 'на');

        $elektoenergiu[0] = $this->getSafeMockLocal(Suschestvitelnoe::class);
        PHPUnitHelper::setProtectedProperty($elektoenergiu[0], 'text', 'электроэнергию');

        $ne[0] = $this->getSafeMockLocal(Chastica::class);
        PHPUnitHelper::setProtectedProperty($ne[0], 'text', 'не');

        $budet[0] = $this->getSafeMockLocal(Glagol::class);
        PHPUnitHelper::setProtectedProperty($budet[0], 'text', 'будет');
        $budet[1] = $this->getSafeMockLocal(Glagol::class);
        PHPUnitHelper::setProtectedProperty($budet[1], 'text', 'будет');

        $otmeneno[0] = $this->getSafeMockLocal(Deeprichastie::class);
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
     * @return Chastica|Deeprichastie|Glagol|Suschestvitelnoe|Zapiataya | \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSafeMockLocal()
    {
        return call_user_func_array([$this, 'getSafeMock'], func_get_args());
    }

    /**
     * @return \Aot\Text\Matrix
     */
    public function getMatrix()
    {
        $mixed = $this->getWordsAndPunctuation();

        $matrix = new \Aot\Text\Matrix;

        foreach ($mixed as $value) {

            if (is_array($value) && $value[0] instanceof \Aot\RussianMorphology\Slovo) {
                $matrix->appendWordsForm($value);
            }

            if ($value instanceof \Aot\RussianSyntacsis\Punctuaciya\Base) {
                $matrix->appendPunctuation($value);
            }
        }

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


        $link = \Aot\Sviaz\Rule\AssertedLink\Base::create($main, $depended);

        $asserted_match = \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatching::create(
             \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class,
            \Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq::create(),
            \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Base::class
        );

        $link->addAssertedMatches($asserted_match);

        $rule->addLink($link);

        return $rule;

    }
}