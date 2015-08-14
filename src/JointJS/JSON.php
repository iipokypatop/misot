<?php

namespace Aot\JointJS;


abstract class JSON implements \JsonSerializable
{
    protected $storage = [];

    protected function get($name)
    {
        return $this->storage[$this->processName($name)];
    }

    protected function set($name, $value)
    {
        $this->storage[$this->processName($name)] = $value;
    }

    protected function isSetUp($name)
    {
        return isset($this->storage[$this->processName($name)]);
    }

    protected function processName($name)
    {
        //$name = str_replace('-', '_', $name);
        return $name;
    }

    protected function __construct()
    {

    }

    public function jsonSerialize()
    {
        $data = [];
        foreach ($this->getFieldForJsonSerialize() as $name) {
            if (array_key_exists($name, $this->storage)) {
                $data[$name] = $this->get($name);
            }
        }
        return $data;
    }

    /**
     * @return array
     */
    abstract protected function getFieldForJsonSerialize();
}