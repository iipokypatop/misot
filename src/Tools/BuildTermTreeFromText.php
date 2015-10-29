<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 23.10.2015
 * Time: 14:43
 */

namespace Aot\Tools;


class BuildTermTreeFromText
{
    const FALSE_VERTEX = 0;
    const TRUE_VERTEX = 1;

    static $states = [
        'sentence',
        'word_form',
        'initial_form',
        'term',
        'concept',
    ];

    /**
     * @param string $text
     * @param \SemanticPersistence\Entities\SemanticEntities\Concept[] $true_concepts
     * @return array предназначенный для вывода на интерфейс
     */
    public static function run($text, array $true_concepts = [])
    {
        assert(is_string($text));
        foreach ($true_concepts as $true_concept) {
            assert(is_a($true_concept, \SemanticPersistence\Entities\SemanticEntities\Concept::class, true));
        }

        $sentences = \Aot\Tools\ConvertTextIntoSlova::convert($text);

        static::resetState();
        $tmpl_sentences = [];
        foreach ($sentences as $sentence) {
            $tmpl_slova = [];
            foreach ($sentence as $word => $slova) {
                $tmpl_initial_forms = [];
                foreach ($slova as $slovo) {
                    $initial_form = $slovo->getInitialForm();
                    $terms = static::getTermsForInitialForm($initial_form);
                    $tmpl_definitions = [];
                    foreach ($terms as $term) {
                        $concept = $term->getConcept();
                        if (static::checkConcept($concept, $true_concepts)) {
                            static::fillState();
                        }
                        $tmpl_concept = static::fillTemplate($concept->getDescription(),
                            $term->getDefinition(), static::cutState('concept'), []);
                        $tmpl_definitions[] = static::fillTemplate($term->getDefinition(), $initial_form,
                            static::cutState('term'),
                            $tmpl_concept);
                    }
                    $tmpl_initial_forms[] = static::fillTemplate($initial_form, $word, static::cutState('initial_form'),
                        $tmpl_definitions);
                }
                $tmpl_slova[] = static::fillTemplate($word, $sentence->getRawSentenceText(),
                    static::cutState('word_form'),
                    $tmpl_initial_forms);
            }
            $tmpl_sentences[] = static::fillTemplate($sentence->getRawSentenceText(), "Предложения",
                static::cutState('sentence'),
                $tmpl_slova);
        }
        return [static::fillTemplate("Предложения", null, 1, $tmpl_sentences)];
    }

    /**
     * @param string $initial_form
     * @return \SemanticPersistence\Entities\SemanticEntities\Term[]
     */
    protected static function getTermsForInitialForm($initial_form)
    {
        assert(is_string($initial_form));
        $initial_form = strtolower($initial_form);

        $result = [];

        /** @var \SemanticPersistence\Entities\SemanticEntities\Word[] $word_entities */
        $word_entities =
            \SemanticPersistence\API\SemanticAPI::getAPI()
                ->findBy(
                    \SemanticPersistence\Entities\SemanticEntities\Word::class,
                    ['name' => [$initial_form]]
                );

        foreach ($word_entities as $word_entity) {
            $result = array_merge(
                $result,
                \SemanticPersistence\API\SemanticAPI::getAPI()
                    ->findBy(
                        \SemanticPersistence\Entities\SemanticEntities\Term::class,
                        ['word' => $word_entity->getId()]
                    )
            );
        }

        return $result;
    }

    /**
     * @return array
     */
    protected static function fillTemplate($name, $parent, $state, array $children)
    {
        return [
            'name' => $name,
            'parent' => $parent,
            'state' => $state,
            'children' => $children
        ];
    }

    /**
     * @brief Проверка, есть ли данный концепт в результирующем графе
     *
     * @param \SemanticPersistence\Entities\SemanticEntities\Concept $concept
     * @param \SemanticPersistence\Entities\SemanticEntities\Concept[] $true_concepts
     * @return bool
     */
    protected static function checkConcept(
        \SemanticPersistence\Entities\SemanticEntities\Concept $concept,
        array $true_concepts
    ) {
        foreach ($true_concepts as $true_concept) {
            if ($concept->getId() === $true_concept->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return void
     */
    protected static function resetState()
    {
        static::$states ['sentence'] = static::FALSE_VERTEX;
        static::$states ['word_form'] = static::FALSE_VERTEX;
        static::$states ['initial_form'] = static::FALSE_VERTEX;
        static::$states ['term'] = static::FALSE_VERTEX;
        static::$states ['concept'] = static::FALSE_VERTEX;
    }

    /**
     * @return void
     */
    protected static function fillState()
    {
        static::$states ['sentence'] = static::TRUE_VERTEX;
        static::$states ['word_form'] = static::TRUE_VERTEX;
        static::$states ['initial_form'] = static::TRUE_VERTEX;
        static::$states ['term'] = static::TRUE_VERTEX;
        static::$states ['concept'] = static::TRUE_VERTEX;
    }

    /**
     * @return int
     */
    protected static function cutState($level)
    {
        $state = static::$states [$level];
        static::$states [$level] = static::FALSE_VERTEX;
        return $state;
    }

}