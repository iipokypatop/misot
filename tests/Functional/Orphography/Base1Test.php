<?php
/**
 * User: Яковенко Иван
 */

namespace AotTest\Functional\Orphography;

use MivarTest\PHPUnitHelper;

class BaseTest extends \AotTest\AotDataStorage
{
    public function testLaunch()
    {
        $base = \Orthography\Base1::create();
        print_r($base->checkWord('ПРиветт'));
    }

    public function testWords()
    {
        $base = \Orthography\Base1::create();
        $text = <<<EVBUFFER_EOF
Ведущий российский поисковый сайт опубликовал информацию о наиболее частых орфографических ошибках пользователей Яндекса Статистика показывает что более четверти российских пользователей совершают ошибку в слове комментарий двадцать семь процентов пишут его с одной буквой Второе место в рейтинге занимают слова жесткий и девчонка Чуть менее четверти посетителей не знают как правильно писать эти слова Статистические данные об ошибках компания собирает на основе информации об исправлении и уточнении запросов которые делают сами пользователи а также обменивается ими с такими крупными сайтами как Википедия Поисковику приходится исправлять ошибочное написание слов предлагать пользователям уточнить запрос чтобы находить необходимое пользователю на этом тест стоит закончить
EVBUFFER_EOF;
        $words = explode(" ", $text);
        $with_out_mistakes = ['time' => 0, 'words' => $words];
        /*
        print_r("Без ошибок: ");
        print_r($with_out_mistakes);*/

        $text = <<<EVBUFFER_EOF
Ведуущий росийский поиковый сайт апубликовал инфармацию о наиболее частых орфаграфических ашибках пользавателей Яндекса Статистика паказывает что более четверти росийских пользавателей совершают ашибку в слове коментарий двадцать семь процентов пишут его с одной буквой Втарое место в ретинге занимают слова жеский и девчёнка Чуть менее четвирти пасетителей не знатют как правельно песать эти слава Статестические даные обб ашибках кмпания саббирает наа аснове информациии об исправлениии ии уточнениии запросав каторые делают сами пользователи а также абменивается ими с такими крупными сатами как Википедия Поиссковику преходится испровлять ашибочное напесание словв прелагать пользовтелям уточить зопрос чтобы ноходить необходмое пользвателю на этам тес стоет закончит
EVBUFFER_EOF;
        $words = explode(" ", $text);
        $with_mistakes = ['time' => 0, 'words' => $words];
        $with_mistakes_after_check = $base->checkWords($words);
        /*
        print_r("С ошибками: ");
        print_r($with_mistakes);*/

        $count = count($words);
        for ($i = 0; $i < $count; $i++) {
            //print_r($with_out_mistakes['words'][$i] . ' - ' . $with_mistakes['words'][$i] . ' - ' . $with_mistakes_after_check['words'][$i] . "\n");
        }
        $this->compare($with_out_mistakes, $with_mistakes, $with_mistakes_after_check, $count);
    }

    protected function compare($with_out_mistakes, $with_mistakes, $with_mistakes_after_check, $count)
    {
        $with_out_mistakes = $with_out_mistakes['words'];
        $with_mistakes = $with_mistakes['words'];
        $statistic = $with_mistakes_after_check['statistic'];
        $with_mistakes_after_check = $with_mistakes_after_check['words'];


        $count_real_mistakes = 0;
        for ($i = 0; $i < $count; $i++) {
            if (strcasecmp($with_out_mistakes[$i], $with_mistakes[$i]) !== 0) {
                $count_real_mistakes++;
            }
        }


        $true = 0;
        $false = 0;
        for ($i = 0; $i < $count; $i++) {
            if (strcasecmp($with_out_mistakes[$i], $with_mistakes_after_check[$i]) !== 0) {
                $false++;
                print_r($with_out_mistakes[$i] . ' - ' . $with_mistakes[$i] . ' - ' . $with_mistakes_after_check[$i] . "\n");
            } else {
                $true++;
            }
        }
        print_r("Результаты работы:\n");
        print_r("Всего слов: $count\n");
        print_r("Правильные/неправильные слова: " . ($count - $count_real_mistakes) . "/" . $count_real_mistakes . "\n");
        print_r("Верно/неверно написанные слова " . $true . '/' . $false . "\n");
    }

}