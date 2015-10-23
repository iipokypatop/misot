<?php


use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\Sviaz\Rule\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;
use Aot\Sviaz\Rule\AssertedMember\PresenceRegistry;
use Aot\Text\GroupIdRegistry;

class VSOTest extends \AotTest\AotDataStorage
{
    protected $cache_nf_member = [];


    public function testRunInClass()
    {
        $sequence = $this->getRawSequence();
        $misot_to_aot = \Aot\Sviaz\Processors\Aot::create();
        $misot_to_aot->run($sequence, []);
    }

    /**
     *
     */
    public function testRun()
    {
        $sequence = $this->getRawSequence();
        /** @var \Aot\Sviaz\SequenceMember\Base $member */
        $sentence_array = $this->getSentenceArrayBySequence($sequence);
        $sentence_string = join(' ', $sentence_array);

        $vso = $this->getOriginalVSOModel($sentence_string);

        /** @var \Objects\Rule $rule */
        foreach ($vso as $rule) {
            if ($rule->get_name_V() !== null && !empty($this->cache_nf_member[$rule->get_name_V()])) {

                if ($rule->get_name_O() !== null && !empty($this->cache_nf_member[$rule->get_name_O()])) {
                    if ($rule->get_type_relation() === 'x') {
                        $sequence->addSviaz($this->createSvyaz($sequence, $rule, 'V', 'O'));

                    } else {
                        $sequence->addSviaz($this->createSvyaz($sequence, $rule, 'O', 'V'));
                    }

                } elseif ($rule->get_name_SV() !== null && !empty($this->cache_nf_member[$rule->get_name_SV()])) {
                    $sequence->addSviaz($this->createSvyaz($sequence, $rule, 'V', 'SV'));
                }
            } elseif ($rule->get_name_O() !== null && !empty($this->cache_nf_member[$rule->get_name_O()])
                && $rule->get_name_SO() !== null && !empty($this->cache_nf_member[$rule->get_name_SO()])
            ) {
                $sequence->addSviaz($this->createSvyaz($sequence, $rule, 'O', 'SO'));
            }
        }

        print_r($sequence->getSviazi());


    }

    /**
     * Создаем правило
     * @param $role_main
     * @param $role_dep
     * @return \Aot\Sviaz\Rule\Base
     */
    private function createRule($role_main, $role_dep)
    {
        $roles_map = [
            'V' => RoleRegistry::VESCH,
            'O' => RoleRegistry::OTNOSHENIE,
            'SV' => RoleRegistry::SVOISTVO,
            'SO' => RoleRegistry::SVOISTVO,
        ];
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        $roles_map[$role_main]
                    )
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        $roles_map[$role_dep]
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                );
        return $builder->get();
    }

    /**
     * Создание связи в пространстве МИСОТ
     * @param $seq - последовательность
     * @param $rule
     * @param string $main_field - поле главного элемента
     * @param string $depend_field - поле зависимого элемента
     * @return \Aot\Sviaz\Podchinitrelnaya\Base
     */
    private function createSvyaz($seq, $rule, $main_field, $depend_field)
    {
        $main = $this->cache_nf_member[call_user_func_array([$rule, 'get_name_'.$main_field],[])];
        $depended = $this->cache_nf_member[call_user_func_array([$rule, 'get_name_'.$depend_field],[])];
        $rule = $this->createRule($main_field, $depend_field);
        return \Aot\Sviaz\Podchinitrelnaya\Base::create($main, $depended, $rule, $seq);
    }

    /**
     * @param $sequence
     * @return array
     */
    private function getSentenceArrayBySequence($sequence)
    {
        $sentence_array = [];
        foreach ($sequence as $member) {
            if ($member instanceof \Aot\Sviaz\SequenceMember\Punctuation) {
                /** @var \Aot\Sviaz\SequenceMember\Punctuation $member */
            } elseif ($member instanceof Aot\Sviaz\SequenceMember\Word\Base) {
                /** @var Aot\Sviaz\SequenceMember\Word\Base $member */
                $sentence_array[] = $member->getSlovo()->getText();
                $this->cache_nf_member[$member->getSlovo()->getInitialForm()] = $member;
            }
        }
        return $sentence_array;
    }

    /**
     * Получение VSO модели через АОТ
     * @param $sentence_string
     * @return mixed
     */
    private function getOriginalVSOModel($sentence_string)
    {
        $mivar = new DMivarText(['txt' => $sentence_string]);
        $mivar->semantic_model();
        return $mivar->getSemanticModel();
    }


}