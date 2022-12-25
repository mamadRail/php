<?php
class ErrorMassage
{
    private $myerrors = [];

    public function set($name, $value)
    {
        $this->myerrors[$name] = $value;
    }

    public function has($name)
    {
        return isset($this->myerrors[$name]);
    }
    public function get($name)
    {
        if ($this->has($name))
            return $this->myerrors[$name];

        return null;
    }
}
