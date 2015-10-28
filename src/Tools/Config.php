<?php
/**
 * Created by PhpStorm.
 * User: vasily
 * Date: 02.10.15
 * Time: 15:49
 */

namespace Aot\Tools;


class Config
{

    private static $instance;

    private function __construct()
    {

    }

    public static function getConfig()
    {
        if (!isset(self::$instance)) {

            $file_path = __DIR__ . '/../../config/config.ini';

            if (!file_exists($file_path)) {
                throw new \Exception("There is no configuration file(config/config.ini) for this project");
            }

            $array = parse_ini_file($file_path, true);

            if (is_null($array)) {
                throw new \Exception("Parse error of a configuration file(config/config.ini)");
            }


            self::$instance = self::recursive_parse(self::parse_ini_advanced($array));
        }

        return self::$instance;
    }

    private static function parse_ini_advanced($array)
    {
        $returnArray = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $e = explode(':', $key);
                if (!empty($e[1])) {
                    $x = array();
                    foreach ($e as $tk => $tv) {
                        $x[$tk] = trim($tv);
                    }
                    $x = array_reverse($x, true);
                    foreach ($x as $k => $v) {
                        $c = $x[0];
                        if (empty($returnArray[$c])) {
                            $returnArray[$c] = array();
                        }
                        if (isset($returnArray[$x[1]])) {
                            $returnArray[$c] = array_merge($returnArray[$c], $returnArray[$x[1]]);
                        }
                        if ($k === 0) {
                            $returnArray[$c] = array_merge($returnArray[$c], $array[$key]);
                        }
                    }
                } else {
                    $returnArray[$key] = $array[$key];
                }
            }
        }
        return $returnArray;
    }

    private static function recursive_parse($array)
    {
        $returnArray = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = self::recursive_parse($value);
                }
                $x = explode('.', $key);
                if (!empty($x[1])) {
                    $x = array_reverse($x, true);
                    if (isset($returnArray[$key])) {
                        unset($returnArray[$key]);
                    }
                    if (!isset($returnArray[$x[0]])) {
                        $returnArray[$x[0]] = array();
                    }
                    $first = true;
                    foreach ($x as $k => $v) {
                        if ($first === true) {
                            $b = $array[$key];
                            $first = false;
                        }
                        $b = array($v => $b);
                    }
                    $returnArray[$x[0]] = array_merge_recursive($returnArray[$x[0]], $b[$x[0]]);
                } else {
                    $returnArray[$key] = $array[$key];
                }
            }
        }
        return $returnArray;
    }
}
