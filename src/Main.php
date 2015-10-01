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
     * @param \SemanticPersistence\Entities\SemanticEntities\Word $word1
     * @param \SemanticPersistence\Entities\SemanticEntities\Word $word2
     * @return \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[]|null
     */
    public static function findSviazBetweenTwoWords (\SemanticPersistence\Entities\SemanticEntities\Word $word1,\SemanticPersistence\Entities\SemanticEntities\Word $word2)
    {
        //Соединение, АПИ
        $api = \SemanticPersistence\API\SemanticAPI::getAPI("host=192.168.10.51 dbname=mivar_semantic_new user=postgres password=@Mivar123User@");

        //ищем слова в БД. Помним, что слово должно быть только одно!
        //todo Где проверять, вдруг слово 2 раза добавили?
        $word1_obj=$api->findOneBy(\SemanticPersistence\Entities\SemanticEntities\Word::class,['name'=>$word1->getName()]);
        if (!is_a($word1_obj,\SemanticPersistence\Entities\SemanticEntities\Word::class))
            return null;
        $word2_obj=$api->findOneBy(\SemanticPersistence\Entities\SemanticEntities\Word::class,['name'=>$word2->getName()]);
        if (!is_a($word2_obj,\SemanticPersistence\Entities\SemanticEntities\Word::class))
            return null;
        //достаём из БД правила. Их может быть несколько, что не есть хорошо

        //"Прямая последовательность"
        /** @var \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[] $syntax_rules_part1 */
        $syntax_rules_part1=$api->findBy(\SemanticPersistence\Entities\SemanticEntities\SyntaxRule::class,['main'=>$word1_obj->getId(),'depend'=>$word2_obj->getId()]);

        //"Обратная последовательность"
        /** @var \SemanticPersistence\Entities\SemanticEntities\SyntaxRule[] $syntax_rules_part2 */
        $syntax_rules_part2=$api->findBy(\SemanticPersistence\Entities\SemanticEntities\SyntaxRule::class,['main'=>$word2_obj,'depend'=>$word1_obj]);

        //Мёржим массивы
        $syntax_rules=array_merge($syntax_rules_part1,$syntax_rules_part2);

        //Проверяем, существуют ли правила
        if (count($syntax_rules)>0)
            return $syntax_rules;

        //возвращаем null если вообще никаких правил не найдено
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



