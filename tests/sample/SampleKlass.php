<?php

namespace PhpDefInfo\sample;

/**
 * Sample Class Summary
 *
 * descri
 * ption
 *
 * @method string getHoge(string $fuga, [ array &$result ])
 * @property string $name Name description
 * @property-read int $id ID description
 * @property-write mixed $val Val description
 */
class SampleKlass
{
    const HOGE = 'HOGE';

    /** @var int ID real description */
    private $id;

    /** @var string Gender description */
    public $gender;

    /** @var array */
    private $data = [];

    /**
     * @param string $name
     * @param string $gender
     */
    public function __construct($name, $gender)
    {
        $this->id = mt_rand();
        $this->data['name'] = $name;
        $this->data['gender'] = $gender;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return property_exists($this, $name) ? $this->$name : $this->data['name'];
    }

    public function __isset($name)
    {
        return property_exists($this, $name) ? isset($this->name) : isset($this->data['name']);
    }
}
