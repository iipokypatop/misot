<?php
namespace Aot\MivarTextSemantic\dictionary;


class Helper
{
    public static function getWordFromAllDict($arrayWords)
    {
        //////////PHP MORPHY START
        //require_once LIB_DIR . 'phpmorphy/src/phpMorphy.php';
        //$dir = LIB_DIR . 'phpmorphy/dicts';
        //require('phpMorphy.php');
        $array_dictionary_word = array();
        // set some options
        $opts = array(
            // storage type, follow types supported
            // PHPMORPHY_STORAGE_FILE - use file operations(fread, fseek) for dictionary access, this is very slow...
            // PHPMORPHY_STORAGE_SHM - load dictionary in shared memory(using shmop php extension), this is preferred mode
            // PHPMORPHY_STORAGE_MEM - load dict to memory each time when phpMorphy intialized, this useful when shmop ext. not activated. Speed same as for PHPMORPHY_STORAGE_SHM type
            'storage' => PHPMORPHY_STORAGE_MEM,
            // Enable prediction by suffix
            'predict_by_suffix' => true,
            // Enable prediction by prefix
            'predict_by_db' => true,
            // TODO: comment this
            'graminfo_as_text' => true,
        );

        $lang = 'ru_RU';

        // Create phpMorphy instance
        try {
            $morphy = new \phpMorphy(PHPMORPHY_DIR . '/../dicts', $lang, $opts);
        } catch (\phpMorphy_Exception $e) {
            die('Error occured while creating phpMorphy instance: ' . PHP_EOL . $e);
        }


        if (function_exists('iconv')) {
            foreach ($arrayWords as &$word) {
                $word = iconv('utf-8', $morphy->getEncoding(), $word);
            }
            unset($word);
        }
        $array_dictionary_word = array();
        //dpm($arrayWords);
        try {
            foreach ($arrayWords as $word) {
                // by default, phpMorphy finds $word in dictionary and when nothig found, try to predict them
                // you can change this behaviour, via second argument to getXXX or findWord methods
                $base = $morphy->getBaseForm($word);
                $all = $morphy->getAllForms($word);
                $part_of_speech = $morphy->getPartOfSpeech($word);

                // $base = $morphy->getBaseForm($word, phpMorphy::NORMAL); // normal behaviour
                // $base = $morphy->getBaseForm($word, phpMorphy::IGNORE_PREDICT); // don`t use prediction
                // $base = $morphy->getBaseForm($word, phpMorphy::ONLY_PREDICT); // always predict word

                $is_predicted = $morphy->isLastPredicted(); // or $morphy->getLastPredictionType() == phpMorphy::PREDICT_BY_NONE
                $is_predicted_by_db = $morphy->getLastPredictionType() == \phpMorphy::PREDICT_BY_DB;
                $is_predicted_by_suffix = $morphy->getLastPredictionType() == \phpMorphy::PREDICT_BY_SUFFIX;

                // this used for deep analysis
                $word_up = mb_strtoupper($word, 'utf-8');

                $collection = $morphy->findWord($word_up);
                // or var_dump($morphy->getAllFormsWithGramInfo($word)); for debug

                if (false === $collection) {
                    //echo $word, " NOT FOUND\n";
                    continue;
                }


                //if($is_predicted) dpm('Предскзаано слово: '. $word);
                //$initial_form = $morphy->lemmatize($word_up);
                //$gram_info = $morphy->getGramInfo($word_up);

                /*
                foreach ($morphy->findWord($word_up) as $some_value)
                {
                    dpm($some_value->getBaseForm());
                    foreach($some_value->getFoundWordForm() as $xxxpornovariable)
                    {
                        dpm($xxxpornovariable->getPartOfSpeech());
                        dpm($xxxpornovariable->getGrammems());
                    }

                }
                */

                // dpm($initial_form);
                //dpm($gram_info);
                //$word = mb_strtolower($word);

                $array_initial = array();

                //ВСТАВИТЬ ПРИВЯЗКУ КОНТЕКСТА И ВЫБОР ЧАСТИ РЕЧИ
                //foreach($gram_info as $index=>$info2)
                foreach ($morphy->findWord($word_up) as $form_word) {
                    $initial_form = $form_word->getBaseForm();
                    foreach ($form_word->getFoundWordForm() as $form_word_form) {
                        //dpm($info);
                        $id = uniqid();//new MongoId();
                        $id = $id . '';

                        $info['pos'] = $form_word_form->getPartOfSpeech();
                        $info['grammems'] = $form_word_form->getGrammems();

                        //dpm($info);
                        //dpm($word);
                        //dpm($morphy->getPseudoRoot(mb_strtoupper($word)));
                        //dpm($morphy->getAllForms(mb_strtoupper($word)));
                        //dpm($morphy->getBaseForm(mb_strtoupper($word)));
                        if (isset($info['form_no'])) {
                            $array_initial[$info['form_no']] = 1;
                        }

                        $array_dictionary_word[$word][$id]['id_word_form'] = $id;
                        $array_dictionary_word[$word][$id]['word_form'] = $word;

                        $array_dictionary_word[$word][$id]['initial_form'] = mb_strtolower($initial_form, "utf-8"); // было просто $initial_form[0];

                        if ($info['pos'] == 'С') {
                            $id_word_class = 2;
                            $word_class_name = 'существительное';
                        } elseif ($info['pos'] == 'П') {
                            $id_word_class = 3;
                            $word_class_name = 'прилагательное';
                        } elseif ($info['pos'] == 'КР_ПРИЛ') {
                            $id_word_class = 16;
                            $word_class_name = 'краткое прилагательное';
                        } elseif ($info['pos'] == 'ИНФИНИТИВ') {
                            $id_word_class = 17;
                            $word_class_name = 'инфинитив';
                        } elseif ($info['pos'] == 'Г') {
                            $id_word_class = 1;
                            $word_class_name = 'глагол';
                        } elseif ($info['pos'] == 'ДЕЕПРИЧАСТИЕ') {
                            $id_word_class = 11;
                            $word_class_name = 'деепричастие';
                        } elseif ($info['pos'] == 'ПРИЧАСТИЕ') {
                            $id_word_class = 5;
                            $word_class_name = 'причастие';
                        } elseif ($info['pos'] == 'КР_ПРИЧАСТИЕ') {
                            $id_word_class = 18;
                            $word_class_name = 'краткое причастие';
                        } elseif ($info['pos'] == 'МС') {
                            $id_word_class = 4;
                            $word_class_name = 'местоимение-существительное'; //например ОН
                        } elseif ($info['pos'] == 'МС-П') {
                            $id_word_class = 19;
                            $word_class_name = 'местоименное прилагательное'; //например всякий
                        } elseif ($info['pos'] == 'МС-ПРЕДК') {
                            $id_word_class = 20;
                            $word_class_name = 'местоимение-предикатив'; //например всякий
                        } elseif ($info['pos'] == 'ЧИСЛ') {
                            $id_word_class = 14;
                            $word_class_name = 'числительное';
                        } elseif ($info['pos'] == 'ЧИСЛ-П') {
                            $id_word_class = 14;
                            $word_class_name = 'порядковое числительное';
                        } elseif ($info['pos'] == 'Н') {
                            $id_word_class = 12;
                            $word_class_name = 'наречие';
                        } elseif ($info['pos'] == 'ПРЕДК') {
                            $id_word_class = 13;
                            $word_class_name = 'предикатив';
                        } elseif ($info['pos'] == 'ПРЕДЛ') {
                            $id_word_class = 6;
                            $word_class_name = 'предлог';
                        } elseif ($info['pos'] == 'ПОСЛ') {
                            //ХЗ
                        } elseif ($info['pos'] == 'СОЮЗ') {
                            $id_word_class = 8;
                            $word_class_name = 'союз';
                        } elseif ($info['pos'] == 'МЕЖД') {
                            $id_word_class = 10;
                            $word_class_name = 'междометие';
                        } elseif ($info['pos'] == 'ЧАСТ') {
                            $id_word_class = 9;
                            $word_class_name = 'частица';
                        } elseif ($info['pos'] == 'ВВОДН') {
                            $id_word_class = 22;
                            $word_class_name = 'вводное';
                        } elseif ($info['pos'] == 'ФРАЗ') {
                            $id_word_class = 23;
                            $word_class_name = 'фразеологизм';
                        } else {

                        }

                        foreach ($info['grammems'] as $gram_val) {
                            $param_id = '';
                            $param_name = '';
                            $attr_id = '';
                            $attr_name = '';

                            if ($gram_val == 'МР') {
                                $param_id = 8;
                                $param_name = 'род';
                                $attr_id = 19;
                                $attr_name = 'м.р.';
                            } elseif ($gram_val == 'МР-ЖР') //общий род = сирота, пьяница
                            {
                                $param_id = 8;
                                $param_name = 'род';
                                //$attr_id = 19;
                                //$attr_name = 'м.р.';
                            } elseif ($gram_val == 'ЖР') {
                                $param_id = 8;
                                $param_name = 'род';
                                $attr_id = 21;
                                $attr_name = 'ж.р.';
                            } elseif ($gram_val == 'СР') {
                                $param_id = 8;
                                $param_name = 'род';
                                $attr_id = 20;
                                $attr_name = 'с.р.';
                            } elseif ($gram_val == 'ЕД') {
                                $param_id = 6;
                                $param_name = 'число';
                                $attr_id = 14;
                                $attr_name = 'ед.ч.';
                            } elseif ($gram_val == 'МН') {
                                $param_id = 6;
                                $param_name = 'число';
                                $attr_id = 15;
                                $attr_name = 'мн.ч.';
                            } elseif ($gram_val == 'ИМ') {
                                $param_id = 13;
                                $param_name = 'падеж';
                                $attr_id = 32;
                                $attr_name = 'и.п.';
                            } elseif ($gram_val == 'РД') {
                                $param_id = 13;
                                $param_name = 'падеж';
                                $attr_id = 33;
                                $attr_name = 'р.п.';
                            } elseif ($gram_val == 'ДТ') {
                                $param_id = 13;
                                $param_name = 'падеж';
                                $attr_id = 34;
                                $attr_name = 'д.п.';
                            } elseif ($gram_val == 'ВН') {
                                $param_id = 13;
                                $param_name = 'падеж';
                                $attr_id = 35;
                                $attr_name = 'в.п.';
                            } elseif ($gram_val == 'ТВ') {
                                $param_id = 13;
                                $param_name = 'падеж';
                                $attr_id = 36;
                                $attr_name = 'т.п.';
                            } elseif ($gram_val == 'ПР') {
                                $param_id = 13;
                                $param_name = 'падеж';
                                $attr_id = 37;
                                $attr_name = 'п.п.';
                            } elseif ($gram_val == 'ЗВ') //звательный падеж = отче, боже
                            {
                                $param_id = 13;
                                $param_name = 'падеж';
                                $attr_id = 37;
                                $attr_name = 'п.п.';
                            } elseif ($gram_val == '2') //второй родительный или второй предложный падежи
                            {
                                $param_id = 13;
                                $param_name = 'падеж';
                                $attr_id = 37;
                                $attr_name = 'п.п.';
                            } elseif ($gram_val == '1Л') {
                                $param_id = 7;
                                $param_name = 'лицо';
                                $attr_id = 16;
                                //$attr_id = 28;
                                $attr_name = '1';
                            } elseif ($gram_val == '2Л') {
                                $param_id = 7;
                                $param_name = 'лицо';
                                $attr_id = 17;
                                //$attr_id = 29;
                                $attr_name = '2';
                            } elseif ($gram_val == '3Л') {
                                $param_id = 7;
                                $param_name = 'лицо';
                                $attr_id = 18;
                                //$attr_id = 30;
                                $attr_name = '3';
                            } elseif ($gram_val == 'БЕЗЛ') {
                                $param_id = 7;
                                $param_name = 'лицо';
                                //$attr_id = 19;
                                //$attr_name = 'м.р.';
                            } elseif ($gram_val == 'НСТ') {
                                $param_id = 5;
                                $param_name = 'время';
                                $attr_id = 11;
                                $attr_name = 'наст';
                            } elseif ($gram_val == 'БУД') {
                                $param_id = 5;
                                $param_name = 'время';
                                $attr_id = 13;
                                $attr_name = 'буд';
                            } elseif ($gram_val == 'ПРШ') {
                                $param_id = 5;
                                $param_name = 'время';
                                $attr_id = 12;
                                $attr_name = 'прош';
                            } elseif ($gram_val == 'ОД') {
                                $param_id = 11;
                                $param_name = 'одуш-неодуш';
                                $attr_id = 26;
                                $attr_name = 'одуш';
                            } elseif ($gram_val == 'НО') {
                                $param_id = 11;
                                $param_name = 'одуш-неодуш';
                                $attr_id = 27;
                                $attr_name = 'неодуш';
                            } elseif ($gram_val == 'ДСТ') {
                                $param_id = 16;
                                $param_name = 'разряд прич';
                                $attr_id = 44;
                                $attr_name = 'дейст';
                            } elseif ($gram_val == 'СТР') {
                                $param_id = 16;
                                $param_name = 'разряд прич';
                                $attr_id = 45;
                                $attr_name = 'страд';
                            } elseif ($gram_val == '0') //неизменяемое
                            {
                                $param_id = 23;
                                $param_name = 'неизменяемость';
                                $attr_id = 68;
                                $attr_name = 'неизм';
                            } elseif ($gram_val == 'ИМЯ') {
                                $param_id = 27;
                                $param_name = 'ФИО';
                                $array_dictionary_word[$word][$id]['initial_form'] = self::my_ucfirst($array_dictionary_word[$word][$id]['initial_form']);
                            } elseif ($gram_val == 'ОТЧ') {
                                $param_id = 27;
                                $param_name = 'ФИО';
                                $array_dictionary_word[$word][$id]['initial_form'] = self::my_ucfirst($array_dictionary_word[$word][$id]['initial_form']);
                            } elseif ($gram_val == 'ФАМ') {
                                $param_id = 27;
                                $param_name = 'ФИО';
                                $array_dictionary_word[$word][$id]['initial_form'] = self::my_ucfirst($array_dictionary_word[$word][$id]['initial_form']);
                            } elseif ($gram_val == 'СРАВН') {
                                $param_id = 15;
                                $param_name = 'степень сравнения';
                                $attr_id = 42;
                                $attr_name = 'сравн';
                            } elseif ($gram_val == 'ПРЕВ') {
                                $param_id = 15;
                                $param_name = 'степень сравнения';
                                $attr_id = 43;
                                $attr_name = 'прев';

                            } elseif ($gram_val == 'ПВЛ') {
                                $param_id = 4;
                                $param_name = 'наклонение';
                                $attr_id = 9;
                                $attr_name = 'повел';
                            } elseif ($gram_val == 'СВ') {
                                $param_id = 1;
                                $param_name = 'вид';
                                $attr_id = 2;
                                $attr_name = 'сов';
                            } elseif ($gram_val == 'НС') {
                                $param_id = 1;
                                $param_name = 'вид';
                                $attr_id = 3;
                                $attr_name = 'несов';
                            } elseif ($gram_val == 'ПЕ') {
                                $param_id = 3;
                                $param_name = 'переходность';
                                $attr_id = 6;
                                $attr_name = 'перех';
                            } elseif ($gram_val == 'НП') {
                                $param_id = 3;
                                $param_name = 'переходность';
                                $attr_id = 7;
                                $attr_name = 'неперех';
                            } elseif ($gram_val == 'РАЗГ') //разговорое
                            {

                            } elseif ($gram_val == 'АРХ') //архаизм
                            {

                            } elseif ($gram_val == 'АББР') //аббревиатура
                            {

                            } elseif ($gram_val == 'ВОПР') {

                            } elseif ($gram_val == 'УКАЗАТ') {

                            } elseif ($gram_val == 'ЛОК') {
                                $array_dictionary_word[$word][$id]['initial_form'] = self::my_ucfirst($array_dictionary_word[$word][$id]['initial_form']);

                            } elseif ($gram_val == 'КАЧ') {

                            } elseif ($gram_val == 'ДФСТ') {

                            } elseif ($gram_val == 'ОРГ') {

                            } elseif ($gram_val == 'ЖАРГ') {

                            } elseif ($gram_val == 'ПРИТЯЖ') {

                            }

                            if (empty($param_id)) $param_name = $gram_val;

                            $array_dictionary_word[$word][$id]['parametrs'][$param_id] = array(
                                'id_morph_attr' => $param_id,
                                'name' => $param_name,
                                'id_value_attr' => array($attr_id => $attr_id),
                                'short_value' => array($attr_name => $attr_name)
                            );
                        }

                        $array_dictionary_word[$word][$id]['id_word_class'] = $id_word_class;
                        $array_dictionary_word[$word][$id]['name_word_class'] = $word_class_name;
                        //$this->arrayInitialForms[] = $array_dictionary_word[$word][$id]['initial_form'];
                    }
                }
            }
        } catch (\phpMorphy_Exception $e) {
            die('Error occured while text processing: ' . $e->getMessage());
        }
        ///////////PHP MORPHY STOP
        return $array_dictionary_word;
    }

    public static function my_ucfirst($string, $e = 'utf-8')
    {
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
            $string = mb_strtolower($string, $e);
            $upper = mb_strtoupper($string, $e);
            preg_match('#(.)#us', $upper, $matches);
            $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
        } else {
            $string = ucfirst($string);
        }
        return $string;
    }

}
