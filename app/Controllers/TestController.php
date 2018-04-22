<?php

namespace App\Controllers;

class X
{
    public function x()
    {
        return "A@a()";
    }
}


class A
{
    public function __construct(X $x)
    {
        $this->x = $x;
    }

    public function a()
    {
        return $this->x->x();
    }
}


class TestController
{

    public function __construct(A $a)
    {
        $this->a = $a;
    }

    public function test()
    {  
        return $this->a->a();
    }
}