<?php

namespace TestApp\Lib;

class TestClass
{

    public function encode($value)
    {
        return $value . '1';
    }

    public function decode($value)
    {
        return $value;
    }

    public static function staticEncode($value)
    {
        return $value . '1';
    }

    public static function staticDecode($value)
    {
        return $value;
    }

}