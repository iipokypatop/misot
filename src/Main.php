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
     * @param string $text1
     * @param string $text2
     * @return \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[]|null
     */
    public static function findSviazBetweenTwoWords($text1, $text2)
    {
        assert(is_string($text1));
        assert(is_string($text2));

        $api = \SemanticPersistence\API\SemanticAPI::getAPI("host=192.168.10.51 dbname=mivar_semantic_new user=postgres password=@Mivar123User@");

        //ищем слова в БД. Помним, что слово должно быть только одно!
        /* @var \SemanticPersistence\Entities\SemanticEntities\Word $word1_obj */
        $word1_obj = $api->findOneBy(\SemanticPersistence\Entities\SemanticEntities\Word::class, ['name' => $text1]);
        if (empty($word1_obj)) {
            return null;
        }
        /* @var \SemanticPersistence\Entities\SemanticEntities\Word $word2_obj */
        $word2_obj = $api->findOneBy(\SemanticPersistence\Entities\SemanticEntities\Word::class, ['name' => $text2]);
        if (empty($word2_obj)) {
            return null;
        }
        //достаём из БД правила. Их может быть несколько, что не есть хорошо


        //"Прямая последовательность"
        /** @var \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[] $syntax_rules_part1 */
        $syntax_rules_part1 = $api->findBy(
            \SemanticPersistence\Entities\SemanticEntities\SyntaxRule::class,
            ['main' => $word1_obj->getId(), 'depend' => $word2_obj->getId()]
        );

        //"Обратная последовательность"
        /** @var \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[] $syntax_rules_part2 */
        $syntax_rules_part2 = $api->findBy(
            \SemanticPersistence\Entities\SemanticEntities\SyntaxRule::class,
            ['main' => $word2_obj->getId(), 'depend' => $word1_obj->getId()]
        );

        $syntax_rules = array_merge($syntax_rules_part1, $syntax_rules_part2);

        //Если
        if (count($syntax_rules) > 0) {
            return $syntax_rules;
        }

        //если вообще никаких правил не найдено
        return null;
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



