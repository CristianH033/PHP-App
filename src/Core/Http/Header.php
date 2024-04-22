<?php

namespace NewsApp\Core\Http;

class Header
{
    private $name;
    private $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public static function parseFromHeaderString($headerString): ?self
    {
        $parts = explode(':', $headerString, 2);
        if (count($parts) !== 2) {
            return null;
        }

        $name = trim($parts[0]);
        $value = trim($parts[1]);

        return new static($name, $value);
    }

    public static function parseFromKeyValueArray(array $header): ?self
    {
        if (array_keys($header) !== range(0, count($header) - 1)) {
            $name = array_keys($header)[0];
            $value = array_values($header)[0];

            return new static($name, $value);
        }

        return null;
    }

    public function set()
    {
        header($this->toHeaderString());
    }

    public function isCookie()
    {
        return strtolower($this->name) === 'set-cookie';
    }

    public function toArray()
    {
        return [$this->name => $this->value];
    }

    public function toString()
    {
        return $this->name . ': ' . $this->value;
    }

    public function toHeaderString()
    {
        return $this->toString();
    }

    public function __toString()
    {
        return $this->toString();
    }
}
