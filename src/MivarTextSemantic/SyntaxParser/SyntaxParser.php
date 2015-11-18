<?php

namespace Aot\MivarTextSemantic\SyntaxParser;

use Aot\MivarTextSemantic\Module;


/**
 * class SyntaxParser
 *
 * @brief Реализация бизнес логики модуля syntax_parser
 *
 */
class SyntaxParser extends Module
{

    protected static $defines = array(
        'DB_MIVAR_INTELLIGENCE_DIMA',
        'DB_CONNECTION',
        'LIB_DIR'
    );

    protected static $dbconn;

    /**
     * @brief проверка параметров
     */

    public static function check()
    {
        $canrun = true;

        // проверка дефайнов

        foreach (self::$defines as $define) {
            if (!defined($define)) {
                \Aot\MivarTextSemantic\Error::error(__CLASS__ . ": Не определена константа $define");
                $canrun = false;
            }
        }

        // проверка БД

        if (!self::$dbconn = @pg_connect(\Aot\MivarTextSemantic\Constants::DB_MIVAR_INTELLIGENCE)) {
            \Aot\MivarTextSemantic\Error::error(__CLASS__ . ': Невозможно соединиться с базой данных');
            $canrun = false;
        }

        // проверка прочих данных, которые нужны всегда но могут отвалиться

        return $canrun;
    }

    /**
     * @brief Метод запуска модуля
     */

    public function run()
    {


        global $_module_vars;
        $this->_module_vars = $_module_vars;

        $module_node = $this->page->appendChild(new \DOMElement('syntax_parser_module'));
        if (isset($_POST['request'], $_POST['text']) && $_POST['request'] == 'syntax_parser_perfom' && $_POST['request']) {
            $this->syntax_parser_perfom($_POST['text'], $module_node);
        } else {
            if (isset($_POST['request'], $_POST['text']) && $_POST['request'] == 'syntax_parser_train' && $_POST['request']) {
                $this->syntax_parser_train($_POST['text'], $module_node);
            } else {
                if (isset($_POST['request'], $_POST['text']) && $_POST['request'] == 'syntax_parser_wdw' && $_POST['request']) {
                    $this->syntax_parser_wdw($_POST['text'], $module_node);
                } else {
                    if (isset($_POST['request'], $_POST['text']) && $_POST['request'] == 'glossary_parser_perfom' && $_POST['request']) {
                        $this->glossary_parser_perfom($_POST['text'], $module_node);
                    }
                }
            }
        }
        //$this->syntax_parser_wdw("Мама мыла раму. Папа рубил брова.", $module_node);
        add_array_to_xml($module_node->appendChild(new \DOMElement('parameter')), $_POST, array('text', 'request'));
    }

    /**
     * @brief Метод для обработки режима syntax_parser_perfom - синтаксический разбор текста
     *
     * @param $text - текст
     * @param $parent_node - родительский узел для отображения
     */

    protected function syntax_parser_perfom($text, $parent_node)
    {

        if ($text) {
            require_once LIB_DIR . "/parser/syntax_parser/class.SyntaxParserManager.php";

            $syntax_parser = new SyntaxParserManager;

            require_once LIB_DIR . "/class.GraphViz.php";
            $graph_viz = new \GraphViz;
            $syntax_model = $syntax_parser->create_syntax_model($text);
            if ($syntax_model) {
                $pic = $graph_viz->createGraphFromDot($graph_viz->makeDot_wdw($syntax_model));
                if ($pic) {
                    $parent_node->setAttribute('pic', HTTP_ROOT . $pic);
                }
            }
        }
        return $this;
    }

    /**
     * @brief Метод для обработки режима syntax_parser_train - обучения БД
     *
     * @param $text - текст
     * @param $parent_node - родительский узел для отображения
     */

    protected function syntax_parser_train($text, $parent_node)
    {
        if ($text) {
            //xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
            require_once LIB_DIR . "/parser/syntax_parser/class.SyntaxParserManagerTeacher.php";

            //$syntax_parser = new SyntaxParserManagerTeacher(true);
            $syntax_parser = new SyntaxParserManagerTeacher(false);

            require_once LIB_DIR . "/class.GraphViz.php";
            $graph_viz = new GraphViz;
            $syntax_model = $syntax_parser->create_syntax_model($text);
            $parent_node->setAttribute('count_saved_rules', $syntax_parser->count_saved_rules);
            if ($syntax_model) {
                $pic = $graph_viz->createGraphFromDot($graph_viz->makeDot_wdw($syntax_model));
                if ($pic) {
                    $parent_node->setAttribute('pic', HTTP_ROOT . $pic);
                }
            }

            /*$xhprof_data = xhprof_disable();
            $XHPROF_ROOT = "/var/php_only/xhprof-0.9.4";
            include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
            include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
            $xhprof_runs = new XHProfRuns_Default();
            $run_id = $xhprof_runs->save_run($xhprof_data, "syntax_rule");*/
        }
        return $this;
    }

    /**
     * @brief Метод для обработки режима syntax_parser_wdwd - отображение таблицы с wdw
     * @author Елисеев Д.В.
     * @param $text - текст
     * @param $parent_node - родительский узел для отображения
     */

    protected function syntax_parser_wdw($text, $parent_node)
    {
        if ($text) {
            require_once LIB_DIR . "/parser/syntax_parser/class.SyntaxParserManager.php";

            $syntax_parser = new SyntaxParserManager;

            $syntax_parser->reg_parser->parse_text($text);
            $syntax_parser->create_dictionary_word();
            foreach ($syntax_parser->reg_parser->get_sentences() as $sentence) {
                $wdw = $syntax_parser->create_sentence_space($sentence);
                $node_wdw = $wdw->to_xml($parent_node);
                $node_wdw->setAttribute('sentence', $sentence->text);
            }
        }
        return $this;
    }

    /**
     * @brief Метод для обработки режима glossary_parser_perfom - парсер для глоссария
     *
     * @param $text - текст
     * @param $parent_node - родительский узел для отображения
     */

    protected function glossary_parser_perfom($text, $parent_node)
    {
        if ($text) {
            //require_once LIB_DIR."/parser/reg_parser/class.RegParser.php";
            //$reg_parser = new RegParser;
            //$reg_parser->parse_text($text);
            //$reg_parser->create_dictionary_word();
            //$reg_parser->merge_text_dict();
            //$reg_parser->create_sentence_space($reg_parser->current_text_with_dictionary['items'][0]['items'][0]['items'][0]);
            require_once LIB_DIR . "/parser/syntax_parser/class.GlossaryParserManager.php";

            $syntax_parser = new GlossaryParserManager;
            require_once LIB_DIR . "/class.GraphViz.php";
            /*require_once LIB_DIR."/parser/classes_wdw/class.morph_attribute.php";
            require_once LIB_DIR."/parser/classes_wdw/class.dw.php";
            require_once LIB_DIR."/parser/classes_wdw/class.word.php";*///
            $graph_viz = new GraphViz;

            $glossary_model = $syntax_parser->create_syntax_model($text);

            /*foreach ($syntax_model as $point){
                $word = array('kw' => $point['w']['key_word'],
                            'word' => $point['w']['text'],
                            'id_sentense' => $point['w']['id_sentence']);
                unset($point['w']['key_word'], $point['w']['text'], $point['w']['id_sentence'], $point['w']['name']);

                $w = new word(array_merge($word, $point['w']));
                $this->view($w);
                $parametr = array();
                foreach($point['dw']['parametrs'] as $param) {
                    $parametr[$param['id_morph_attr']] = new morph_attribute($param);
                    //$this->view($ff);
                }
                $point['dw']['parametrs'] = $parametr;
                $ff = new dw($point['dw']);
                $this->view($ff);
            }*/
            //$this->view($glossary_model);

            if ($glossary_model) {
                $pic = $graph_viz->createGraphFromDot($graph_viz->makeDot_wdw($glossary_model));
                if ($pic) {
                    $parent_node->setAttribute('pic', HTTP_ROOT . $pic);
                }
            }

            //$this->view(ROOT_PATH);
            //$this->view($pic);
        }
        return $this;
    }
}
