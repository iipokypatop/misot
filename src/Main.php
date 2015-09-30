<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 024, 24.09.2015
 * Time: 17:03
 */

namespace Aot;


class Main
{
    public static function create()
    {
        $ob = new static();

        return $ob;
    }

    /**
     * @param $raw_text
     * @param array $rules
     */
    public function run($raw_text, array $rules)
    {
        assert(is_string($raw_text));

        foreach ($rules as $rule) {
            assert(is_a($rule, \Aot\Sviaz\Rule\Base::class, true));
        }

        $parser = \Aot\Text\TextParser\TextParser::create();
        $parser->execute($raw_text);
        $parser->render();


        foreach ($parser->getSentenceWords() as $sentence) {

            $slova = \Aot\RussianMorphology\Factory::getSlova($sentence);
            $processor = \Aot\Sviaz\Processor\Base::create();
            $matrix = \Aot\Text\Matrix::create($slova);
            $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

            $sequences = $processor->go($normalized_matrix, $rules);

            $best_sequence = $this->chooseBestSequence($sequences);
        }
    }

    protected function saveSviazi(\Aot\Sviaz\Sequence $sequence)
    {
        $api = \SemanticPersistence\API\SemanticAPI::getAPI("host=192.168.10.51 dbname=mivar_semantic_new user=postgres password=@Mivar123User@");

        foreach ($sequence->getSviazi() as $sviaz) {

            $syntax_rule = new \SemanticPersistence\Entities\SemanticEntities\SyntaxRule;

            $main = new \SemanticPersistence\Entities\SemanticEntities\Word;
            $main->setName(
                $sviaz->getMainSequenceMember()->getSlovo()->getInitialForm()
            );

            /*$main_mivar_type = new \SemanticPersistence\Entities\MivarType;

            //$sviaz->getRule()->getAssertedMain()->getDao()->getRole()

            $main_mivar_type = $api->findBy(
                \SemanticPersistence\Entities\MivarType::class,
                []
            );

            $syntax_rule->setMainMivarType($main_mivar_type);
            */

            $syntax_rule->setMain($main);

            $depend = new \SemanticPersistence\Entities\SemanticEntities\Word;
            $depend->setName(
                $sviaz->getDependedSequenceMember()->getSlovo()->getInitialForm()
            );

            /*$depend_mivar_type = new \SemanticPersistence\Entities\MivarType;
            $syntax_rule->setDependMivarType($depend_mivar_type);*/
            $syntax_rule->setDepend($depend);


            $api->getEntityManager()->persist($syntax_rule);

        }


        $api->getEntityManager()->flush();
    }

    /**
     * @param \Aot\Sviaz\Sequence[] $sequences
     * @return null | \Aot\Sviaz\Sequence
     */
    protected function chooseBestSequence(array $sequences)
    {
        foreach ($sequences as $sequence) {
            assert(is_a($sequence, \Aot\Sviaz\Sequence::class, true));
        }

        $sviazi_count = [];
        foreach ($sequences as $index => $sequence) {
            $sviazi_count[count($sequence->getSviazi())][] = $index;
        }

        $max_sviazi_count = max(array_keys($sviazi_count));

        if ($max_sviazi_count === 0) {
            return null;
        }


        return $sequences[$sviazi_count[$max_sviazi_count][0]];
    }
}



