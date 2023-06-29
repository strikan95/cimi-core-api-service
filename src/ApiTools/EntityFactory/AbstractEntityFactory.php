<?php

namespace App\ApiTools\EntityFactory;

abstract class AbstractEntityFactory
{
    public function buildOrUpdate(object $source, object $target = null, array $context = null): object
    {
        if(null == $target)
        {
            $target = new ($this->getEntityClassName())();
        }

        $this->preLoad($source, $target, $context);

        $this->loadFromDto($source, $target);

        return $target;

    }

    abstract function getEntityClassName();
    abstract function preLoad($source, mixed $target, mixed $context);

    private function loadFromDto(object $source, object $target): void
    {
        foreach (get_object_vars($source) as $param => $value)
        {
            if(null == $value) continue;

            $method = 'set'.ucwords($param);
            if (method_exists($this, $method) && is_callable([$this, $method])) {
                $this->$method($value, $target);
            } else {
                $this->setValue($param, $value, $target);
            }
        }
    }

    private function setValue(mixed $param, object $value, object $target): void
    {
        $method = 'set'.ucwords($param);

        if (method_exists($target, $method) && is_callable([$target, $method])) {
            $target->$method($value);
        }
    }
}