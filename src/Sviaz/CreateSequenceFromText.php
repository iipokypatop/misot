<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27.10.2015
 * Time: 11:23
 */

namespace Aot\Sviaz;


use \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\Sviaz\Role\Registry as RoleRegistry;
use Aot\Sviaz\Rule\Builder\Base as AssertedLinkBuilder;

class CreateSequenceFromText
{
    /** @var  string */
    protected $text;
    /** @var  \Aot\Sviaz\Sequence[] */
    protected $sequences;

    /**
     * @return CreateSequenceFromText
     */
    public static function create()
    {
        $obj = new static();
        return $obj;
    }

    protected function __construct()
    {

    }

    /**
     * string $text
     * return void
     */
    public function convert($text)
    {
        assert(is_string($text));
        $this->text = $text;
        $sentences = $this->getSentencesForMatrix($text);

        $this->buildSequences($sentences);
    }

    /**
     * @return \Aot\Sviaz\Sequence[]
     */
    public function getSequences()
    {
        return $this->sequences;
    }


    /**
     * @brief Построение предложений всего текста из объектов класса \Aot\RussianMorphology\Slovo
     * причём, каждой словоформе может соответствовать несколько объектов
     *
     * @param $text
     * @return \Aot\RussianMorphology\Slovo[][][]
     */
    protected function getSentencesForMatrix($text)
    {
        $sentences = \Aot\Tools\ConvertTextIntoSlova::convert($text);
        $sentences_for_sequences = [];
        foreach ($sentences as $sentence) {
            $sentences_for_sequences[] = $sentence->getSlova();
        }
        return $sentences_for_sequences;
    }

    /**
     * @brief построение матрицы для одного предложения
     *
     * @param \Aot\RussianMorphology\Slovo[][] $mixed
     * @return \Aot\Text\Matrix
     */
    protected function getMatrix(array $mixed)
    {
        $matrix = \Aot\Text\Matrix::create($mixed);
        return $matrix;
    }

    /**
     * @brief построение нормализованной матрицы для одного предложения
     *
     * @param \Aot\Text\Matrix $matrix
     * @return \Aot\Text\NormalizedMatrix
     */
    protected function getNormalizedMatrix(\Aot\Text\Matrix $matrix)
    {
        $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);
        return $normalized_matrix;
    }

    /**
     * @brief построение одного фиктивного правила
     *
     * @return \Aot\Sviaz\Rule\Base[]
     */
    protected function getRuleOne()
    {
        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::OTNOSHENIE)
                )
                ->depended(
                    \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::SVOISTVO
                    )
                )
                ->link(
                    AssertedLinkBuilder::create()
                );
        $rule[] = $builder->get();

        return $rule;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @brief построение всех фиктивных правил, связывающих все части речи со всеми частями речи
     * @return \Aot\Sviaz\Rule\Base[]
     * @throws \Exception
     */
    protected function getRuleAll()
    {
        throw new \Exception("Нигде не используется, но удалять не надо");
        $chasti_rechi = [
            ChastiRechiRegistry::SUSCHESTVITELNOE,
            ChastiRechiRegistry::PRILAGATELNOE,
            ChastiRechiRegistry::GLAGOL,
            ChastiRechiRegistry::PRICHASTIE,
            ChastiRechiRegistry::NARECHIE,
            ChastiRechiRegistry::PRICHASTIE,
            ChastiRechiRegistry::DEEPRICHASTIE,
            ChastiRechiRegistry::CHISLITELNOE,
            ChastiRechiRegistry::MESTOIMENIE,
            ChastiRechiRegistry::INFINITIVE,
            ChastiRechiRegistry::PREDLOG,
            ChastiRechiRegistry::SOYUZ,
            ChastiRechiRegistry::CHASTICA,
        ];

        $rule = [];

        foreach ($chasti_rechi as $main_id) {
            foreach ($chasti_rechi as $depended_id) {
                $builder =
                    \Aot\Sviaz\Rule\Builder2::create()
                        ->main(
                            \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                                $main_id,
                                RoleRegistry::OTNOSHENIE)
                        )
                        ->depended(
                            \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                                $depended_id,
                                RoleRegistry::SVOISTVO
                            )
                        )
                        ->link(
                            AssertedLinkBuilder::create()
                        );
                $rule[] = $builder->get();
            }

        }
        return $rule;
    }

    /**
     * @brief Построение последовательностей (объектов класса \Aot\Sviaz\Sequence) для каждого предложения текста.
     * Причем, из всех возможных последовательностей мы берём только первую.
     *
     * @param \Aot\RussianMorphology\Slovo[][][] $sentences
     * @return \Aot\Sviaz\Sequence[][]
     */
    protected function buildSequences($sentences)
    {
        $result_sequences = [];
        foreach ($sentences as $sentence) {
            $processor = \Aot\Sviaz\Processor::create();
            $processor->attachProcessor(Processors\Aot\Base::create());
            $processor->attachPreProcessor(\Aot\Sviaz\PreProcessors\Predlog::create());
            $processor->attachPreProcessor(\Aot\Sviaz\PreProcessors\HomogeneitySupposed::create());
            //$processor->attachPostProcessor(\Aot\Sviaz\PostProcessors\HomogeneityVerification::create());
            $matrix = $this->getMatrix($sentence);
            $normalized_matrix = $this->getNormalizedMatrix($matrix);
            $sequences = $processor->go($normalized_matrix, $this->getRuleOne());
            $result_sequences[] = $sequences[0];
        }
        $this->sequences = $result_sequences;
        return $result_sequences;
    }


}