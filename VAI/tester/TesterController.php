<?php

class TesterController
{
    public function showTest(){
        require_once "view/tester/index.php";
    }

    public function addTests()
    {
    }

    public function runTest(){
        exec("php controller/int/test.php --recursive > output.html");
        require_once "view/tester/index.php";
    }
    public function viewTestResult(){
        require_once "output.html";
    }


}