<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27/07/15
 * Time: 20:43
 */

namespace Aot\Text\TextParser\Filters;


class NoValid extends Base
{

    /**
     * @return array
     */
    protected function getPatterns()
    {
        return [
            "/([^" .
            "а-яА-ЯёЁъЪ" .
            "a-zA-Z" .
            '\\\\' .
            "\\/" .
            "\\?\\!\\,\\.\\:\\;" .
            "\"\\'\\`\\‘\\‛\\’\\«\\»\\‹\\›\\„\\“\\‟\\”" .
            "%$\\s\\d\\*\\-" .
            "\\{\\}\\[\\]\\(\\)" .
            "])/u" => '',
        ];
    }

    /**
     * @param $text
     * @return string
     */
    public function filter($text)
    {
        $arr = [];
        foreach ($this->getPatterns() as $pattern => $replacement) {
            $array = preg_split($pattern, $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
            $par = (preg_match($pattern, $array[0][0])) ? 2 : 1;
            foreach ($array as $key => $value) {
                if (($key % 2) === $par) {
                    $this->logger->warn("Убрали невалидный символ [{$value[0]}] на позиции [{$value[1]}]");
                    continue;
                }
                $arr[] = $value[0];
            }
        }
        return join('', $arr);
    }


}