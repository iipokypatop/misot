<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 17.07.2015
 * Time: 17:53
 */

namespace AotTest\Functional\RussianMorphology;

use MivarTest\PHPUnitHelper;

class Factory3Test extends \AotTest\AotDataStorage
{

    public function testLaunch()
    {
        
    }
    /**
     * скрипт для прогона всех словоформ из базы через фабрики
     */
    public function _testBuildFromEntityFromDb()
    {
        $factory = \Aot\RussianMorphology\Factory2\FactoryFromEntity::get();

        $form_entity = new Form;
        $mword_entity = new Mword();
        $mword_initial_form_entity = new Mword();
        $word_class_entity = new WordClass();

        $form_entity->setMword($mword_entity);
        $form_entity->setWordClass($word_class_entity);
        $form_entity->setInitialForm($mword_initial_form_entity);


        $data = $this->getData();
        //$this->profStart();

        foreach ($this->getData() as $row) {

            $form_entity->id = $row['id'];

            // слово
            $mword_entity->word = $row['word'];

            // начальная форма
            $mword_initial_form_entity->word = $row['initial_form'];

            // values
            $form_entity->valuesAgg = explode(',', $row['values_agg']);

            // word_class_id
            //PHPUnitHelper::setProtectedProperty($word_class_entity, 'id', $row['class_id']);
            $word_class_entity->id = $row['class_id'];

            $ob = $factory->buildFromEntity($form_entity);
        }
        //$this->profStop();


        $log = [];
        foreach ($factory->error_log as $item) {
            $log[] =
                'id:' . $item['id'] .
                ' chast_rechi_id:' . $item['chast_rechi_id'] .
                ' priznak_value_id:' . $item['priznak_value_id'];
        }

        echo "\n";
        echo join("\n", $log);
    }

    /**
     * @return \Generator
     */
    public function getData()
    {
        $config_all = \MivarUtils\Common\Config::getConfig(
            \TextPersistence\Common\Common::getProjectConfigFilePath()
        );

        $config_db_text = $config_all['text']['db'];

        $connection = [];
        foreach ($config_db_text as $name => $value) {
            $connection[] = $name . "=" . $value;
        }

        $connection_string = join(' ', $connection);

        $db = pg_connect($connection_string);

        $size = 4000000;
        $chunks = 100;
        $_limit = (int)($size / $chunks);

        for ($i = 0; $i < 10; $i++) {

            $_offset = $_limit * $i;;

            $q =
                <<<SQL
SELECT
    f.id,
    mw1.word as word,
    mw2.word as initial_form,
    class_id,
    array_to_string(
        case
            WHEN values_agg is null then '{}'
            else values_agg
        end , ','
        ) as values_agg

    FROM
    (SELECT * FROM text.forms f order by f.id limit $_limit OFFSET $_offset) f
    left join mwords mw1 on mw1.id = f.mword_id
    left join mwords mw2 on mw2.id = f.initial_form_id
    where
    values_agg is not null
    order by f.id
SQL;
            $res = pg_query($q);

            $data = pg_fetch_all($res);

            foreach ($data as $row) {
                yield $row;
            }
        }
        pg_close($db);
    }
}

class Form extends \TextPersistence\Entities\TextEntities\Form
{
    public $id;
    public $valuesAgg;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int[]
     */
    public function getValuesAgg()
    {
        return $this->valuesAgg;
    }

    /**
     * скрипт для выгрузки дампа соответствий старых_id и новыз_id в морфике
     */
    public function _testDumpTransferScript()
    {
        if (0) {

            echo "ChastiRechiRegistry", "\n";
            $a = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::new_old();
            asort($a);
            var_export($a);
            echo "\n";
            var_export(array_count_values($a));

            // части речи
            $table = "transf_class";
            $dump1 = [];
            foreach ($a as $k => $v) {
                $dump1[] = "INSERT INTO {$table} (id_word_class, id_new) VALUES ({$v}, {$k});";
            }
            echo "\n";

            echo "MorphologyRegistry", "\n";
            $a = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistry::new_old();
            asort($a);
            var_export($a);
            echo "\n";
            var_export(array_count_values($a));

            $table = "transf_val";
            $dump2 = [];
            foreach ($a as $k => $v) {
                $dump2[] = "INSERT INTO {$table} (id_value_attr, id_new) VALUES ({$v}, {$k});";
            }
            echo "\n";


            echo "MorphologyRegistryParent", "\n";
            $a = \Aot\RussianMorphology\ChastiRechi\MorphologyRegistryParent::new_old();
            asort($a);
            echo "\n";
            var_export($a);
            echo "\n";
            var_export(array_count_values($a));

            $table = "transf_attr";
            $dump3 = [];
            foreach ($a as $k => $v) {
                $dump3[] = "INSERT INTO {$table} (id_morph_attr, id_new) VALUES ({$v}, {$k});";
            }

            echo "\n";
            echo "\n";
            echo join("\n", $dump1);
            echo "\n";
            echo "\n";

            echo join("\n", $dump2);
            echo "\n";
            echo "\n";

            echo join("\n", $dump3);
            echo "\n";
            echo "\n";
        }
    }
}

class WordClass extends \TextPersistence\Entities\TextEntities\WordClass
{
    public $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}

class  Mword extends \TextPersistence\Entities\TextEntities\Mword
{
    public $word;

    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }
}