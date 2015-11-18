<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 09.11.2015
 * Time: 13:25
 */

namespace Aot\RussianSyntacsis\Sentence\Member;


class Word extends Base
{
    /** @var  \Aot\RussianSyntacsis\Sentence\Member\Role\Base */
    protected $role;

    /**
     * @return Role\Base
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role\Base $role
     */
    public function setRole(Role\Base $role)
    {
        $this->role = $role;
    }


}