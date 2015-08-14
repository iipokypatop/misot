<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.08.2015
 * Time: 16:50
 */

namespace Aot\JointJS;

trait MagicSetGetRealization
{
    public function __get($name)
    {
        $getter1 = [$this, 'get' . $name];
        if (is_callable($getter1)) {
            return call_user_func($getter1, $name);
        }

        $getter2 = [$this, 'get' . str_replace('_', '', $name)];
        if (is_callable($getter2)) {
            return call_user_func($getter2, $name);
        }

        $getter3 = [$this, 'get' . str_replace('-', '', $name)];
        if (is_callable($getter3)) {
            return call_user_func($getter3, $name);
        }

        throw new \RuntimeException("unsupported field exception ", var_export($name, 1));
    }

    public function __isset($name)
    {
        $getter1 = [$this, 'get' . $name];
        if (is_callable($getter1)) {
            $result = call_user_func($getter1, $name);
            if (null !== $result) {
                return true;
            }
        }

        $getter2 = [$this, 'get' . str_replace('_', '', $name)];
        if (is_callable($getter2)) {
            $result = call_user_func($getter2, $name);
            if (null !== $result) {
                return true;
            }
        }

        $getter3 = [$this, 'get' . str_replace('-', '', $name)];
        if (is_callable($getter3)) {
            $result = call_user_func($getter3, $name);
            if (null !== $result) {
                return true;
            }
        }

        return false;
    }

    public function __set($name, $value)
    {
        $setter1 = [$this, 'set' . $name];
        if (is_callable($setter1)) {
            call_user_func($setter1, $value);
            return;
        }

        $setter2 = [$this, 'set' . str_replace('_', '', $name)];
        if (is_callable($setter2)) {
            call_user_func($setter2, $value);
            return;
        }

        $setter3 = [$this, 'set' . str_replace('-', '', $name)];
        if (is_callable($setter3)) {
            call_user_func($setter3, $value);
            return;
        }

        throw new \RuntimeException("unsupported field exception ", var_export($name, 1));
    }
}