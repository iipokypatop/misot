<?php
namespace Aot\MivarTextSemantic;

/**
 *
 * class Module
 *
 * @brief Абстрактный класс, который содержит все общие функции по работе с модулями
 *
 */
abstract class Module
{

    protected $page, $dom, $_module_vars;
    /**< переменные окружения: страница, дом документ и параметры модуля */

    /**
     * @brief Конструктор
     *
     * @param bool $reset сбрасывает сессию у класса
     */

    public function __construct($reset = false)
    {
        global $page, $__dom;
        $this->page = $page;
        $this->dom = $__dom;
        if ($reset)
            unset($_SESSION[get_class($this)]);
    }

    /**
     * @brief Отправляет запрос на выполнение серверу
     *
     * @param $sql - sql запрос
     *
     * @return resource
     */

    public function query($sql)
    {
        return \pg_query(self::$dbconn, $sql);
    }

    /**
     * @brief Абстрактный метод запуска модуля
     */

    abstract public function run();

    /**
     * @brief Отладка. Вывод на экран и завершение работы
     */

    protected function view($data)
    {
        echo "<pre>";
        print_r($data);
        echo "<pre/>";
        die;
    }
}

?>
