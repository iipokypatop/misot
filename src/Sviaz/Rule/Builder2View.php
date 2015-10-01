<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 16.07.2015
 * Time: 11:42
 */

namespace Aot\Sviaz\Rule;


class Builder2View
{
    /**
     * @var Builder2
     */
    protected $builder;

    public function __construct(Builder2 $builder)
    {
        $this->builder = $builder;
    }

    public static function create(Builder2 $builder)
    {
        return new static($builder);
    }


}