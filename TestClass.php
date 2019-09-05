<?php

class TestClass
{
    private $var = 20;
    private $var2 = 30;


    public function __call($name, array $params)
    {
        if ($name == 'plus') {
            echo $this->var + $this->var2;
        } else {
            echo $this->var - $this->var2;
        }

        echo "\n";
    }
}


$test = new TestClass();

$test->plus();

$test->aaaa();
