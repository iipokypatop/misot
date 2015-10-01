<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 09.07.2015
 * Time: 13:45
 */

namespace Aot\Sviaz\Rule;


class Builder2
{
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base */
    protected $asserted_main_builder;


    /** @var  \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base */
    protected $asserted_depended_builder;


    /** @var  \Aot\Sviaz\Rule\Builder\Base */
    protected $link_builder;


    /**
     * @return static
     */
    public static function   create()
    {
        return new static();
    }

    public function main(AssertedMember\Builder\Base $main)
    {
        $this->asserted_main_builder = $main;

        return $this;
    }

    public function depended(AssertedMember\Builder\Base $main)
    {
        $this->asserted_depended_builder = $main;

        return $this;
    }

    public function link(\Aot\Sviaz\Rule\Builder\Base $link_builder)
    {
        $this->link_builder = $link_builder;

        return $this;
    }


    /**
     * @return \Aot\Sviaz\Rule\Base
     */
    public function get()
    {
        if (empty($this->asserted_main_builder)) {
            throw new \RuntimeException("no main builder");
        }

        if (empty($this->asserted_depended_builder)) {
            throw new \RuntimeException("no depended builder");
        }

        if (empty($this->link_builder)) {
            throw new \RuntimeException("no link builder");
        }

        $rule = \Aot\Sviaz\Rule\Base::create(
            $this->asserted_main_builder->get(),
            $this->asserted_depended_builder->get()
        );

        $this->link_builder->get(
            $rule
        );

        return $rule;
    }
}