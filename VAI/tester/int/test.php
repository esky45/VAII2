<?php
//Default hodnoty prepinacov
$directoryPath = "."; // default = terajsi priecinok
$recursive = false;
$intOnly = false;
$parseOnly = false;
$jexamxml;

$parsephp = "parse.php";
$interpretpy = "interpret.py";
$jexamxml = "/pub/courses/ipp/jexamxml/jexamxml.jar";
$pathArgs = array("--directory", "--parse-script", "--int-script", "--jexamxml");
$boolArgs = array("--recursive", "--parse-only", "--int-only", "--help");
$testingFiles = array();
$parsedArgs = array();
//Pocitadla vysledkov testov
$success = 0;
$failed = 0;

foreach ($argv as $arg) {
    if (strpos($arg, "=")) {
        $divided_arg =  explode("=", $arg, 2);
        if (in_array($divided_arg[0], $pathArgs)) {
            array_push($parsedArgs, $divided_arg);
        } else {

            exit(10);
        }
    } else {

        if ($arg == $argv[0]) {

            continue;
        } else if (in_array($arg, $boolArgs)) {

            array_push($parsedArgs, $arg);
        } else {

            exit(10);
        }
    }
}

// kontroluje ci su cesty platne
foreach ($parsedArgs as $arg) {

    if (is_array($arg)) {

        if ($arg[0] == "--directory") {

            if (file_exists($arg[1])) {

                $directoryPath = $arg[1];
            } else {

                exit(11);
            }
        } else if ($arg[0] == "--parse-script") {

            if (in_array("--int-only", $parsedArgs)) {

                exit(10);
            }

            if (file_exists($arg[1])) {

                $parsephp = $arg[1];
            } else {

                exit(11);
            }
        } else if ($arg[0] == "--int-script") {

            if (in_array("--parse-only", $parsedArgs)) {

                exit(10);
            }

            if (file_exists($arg[1])) {

                $interpretpy = $arg[1];
            } else {

                exit(11);
            }
        } else if ($arg[0] == "--jexamxml") {

            if (file_exists($arg[1])) {

                $jexamxml = $arg[1];
            } else {

                exit(11);
            }
        } else {

            exit(10);
        }
    } else {

        if ($arg == "--help") {

            if ($argc != 2) {

                exit(10);
            }

            echo "pouzitie:

    php7.4 test.php > <output>.html <args>

         <output> = meno vstupneho suboru
         <args> Argumenty:

        1)    --help |vypise tuto napovedu

        2)    --directory=<path>  |<path> = cesta k priecinku z testami ak nebude zadana testy sa budu hladat v terajsom priecinku

        3)    --parse-script=<path> |<path> = cesta k skriptu parseru ak nebude zadana pouzije sa skript z nazvom parse.php v terajsom priecinku

        4)    --int-script=<path> |<path> = cesta k skriptu interpretu ak nebude zadana pouzije sa skript z nazvom interpret.py v terajsom priecinku

        5)    --int-only |Argument ktory prepne skript do rezimu ktory otestuje iba inerpret

        6)    --parse-only |Argument ktory prepne skript do rezimu ktory otestuje iba parser

        7)    --recursive |Argument ktory povie skriptu ze ma hladat v danej ceste aj vnorene priecinkz s testami \n";

            exit(0);
        } else if ($arg == "--recursive") {

            $recursive = true;
        } else if ($arg == "--parse-only") {

            $parseOnly = true;
        } else if ($arg == "--int-only") {

            $intOnly = true;
        } else {

            exit(10);
        }
    }
}
// intonly a parseonly nemoze byt naraz zadane
if (in_array("--int-only", $parsedArgs) && in_array("--parse-only", $parsedArgs)) {

    exit(10);
}


function recursive_func($path)
{

    $tf = array();

    $s_dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

    foreach ($s_dir as $f) {

        if ($f->isDir()) {

            continue;
        }

        array_push($tf, $f->getPathname());
    }

    return $tf;
}


#funkcia vrati subory s priponou .src
function getSourceFiles($files)
{

    $s = array();

    foreach ($files as $f) {

        if (preg_match('/.+(.src)$/', $f)) {

            array_push($s, $f);
        }
    }

    return $s;
}


// spustenie testu pre interpret alebo parser podla premmennej testType
function test_interpret_or_parser($file, $expected_value, $out, $in, $testType)
{

    $returned_values = array();

    global $parsephp;
    global $interpretpy;
    global $jexamxml;
    global $failed;
    global $success;



    if ($testType == "interpret") {

        $temporary_file = preg_replace('/(.src)|(.xml)$/', ".tmp", $file);

        $generated_file = fopen($temporary_file, "w");

        fclose($generated_file);

        exec("python3.8 " . $interpretpy . " --source=" . $file . " < " . $in . " > " . $temporary_file . " 2> /tmp/IPPtemp", $test_return_int, $test_return_value);
    } else if ($testType == "parser") {

        exec("php7.4 $parsephp < $file", $test_output, $test_return_value);
    }

    if ($test_return_value != 0) {

        if ($expected_value == $test_return_value) {

            array_push($returned_values, $file, $expected_value, $test_return_value, "", "SUCC");

            if ($testType == "interpret") unlink($temporary_file);

            $success++;
        } else {

            array_push($returned_values, $file, $expected_value, $test_return_value, "", "FAILED");

            if ($testType == "interpret") unlink($temporary_file);

            $failed++;
        }
    } else {

        if ($expected_value == $test_return_value) {

            if ($testType == "interpret") {

                shell_exec("diff $temporary_file $out");
            } else if ($testType == "parser") {


                shell_exec('java -jar ' . $jexamxml . ' " $out $in');
            }

            $diff_ret = shell_exec('echo $?');

            if ($diff_ret == 0) {

                array_push($returned_values, $file, $expected_value, $test_return_value, "equal", "SUCC");

                if ($testType == "interpret") unlink($temporary_file);

                $success++;
            } else {

                array_push($returned_values, $file, $expected_value, $test_return_value, "not equal", "FAILED");

                if ($testType == "interpret") unlink($temporary_file);

                $failed++;
            }
        } else {

            array_push($returned_values, $file, $expected_value, $test_return_value, "", "FAILED");

            if ($testType == "interpret") unlink($temporary_file);

            $failed++;
        }
    }

    return $returned_values;
}

#odtestuje aj parse aj int
function test_both($file, $expected_value, $out, $in)
{
    $return_values = array();
    global $parsephp;
    global $failed;
    global $success;

    exec("php7.4 $parsephp < $file", $test_output, $test_return_value);



    if ($test_return_value == 0) {

        $xml_input = preg_replace('/(.src)$/', ".xml", $file);

        $generated_file = fopen($xml_input, "w");

        foreach ($test_output as $xml_instruction) {

            fwrite($generated_file, $xml_instruction);
        }

        fclose($generated_file);

        $values = test_interpret_or_parser($xml_input, $expected_value, $out, $in, "interpret");

        unlink($xml_input);

        array_push($return_values, $values[0], $values[1], $values[2], $values[3], $values[4]);
    } else {

        if ($test_return_value == $expected_value) {

            array_push($return_values, $file, $expected_value, $test_return_value, "", "SUCC");

            $success++;
        } else {

            array_push($return_values, $file, $expected_value, $test_return_value, "", "FAILED");

            $failed++;
        }
    }

    return $return_values;
}


#zavolanie hladania  vnorenych priecinkov s testamy
if ($recursive) {

    $testingFiles = recursive_func($directoryPath);
} else {

    $s_dir = glob($directoryPath . '/*.*');

    foreach ($s_dir as $f) {

        array_push($testingFiles, $f);
    }
}

$sourceFiles = getSourceFiles($testingFiles);
$result = array();


foreach ($sourceFiles as $src_file) {

    $in_f = preg_replace('/(.src)$/', ".in", $src_file);
    $out_f = preg_replace('/(.src)$/', ".out", $src_file);
    $expected_f = preg_replace('/(.src)$/', ".rc", $src_file);



    if (!in_array($in_f, $testingFiles)) {

        $generated_file = fopen($in_f, "w");



        fclose($generated_file);
    }



    if (!in_array($out_f, $testingFiles)) {

        $generated_file = fopen($out_f, "w");

        fclose($generated_file);
    }



    if (!in_array($expected_f, $testingFiles)) {

        $expected_value = 0;

        $generated_file = fopen($expected_f, "w");

        fwrite($generated_file, $expected_value);

        fclose($generated_file);
    } else {

        $read_file = fopen($expected_f, "r");

        $expected_value = fread($read_file, filesize($expected_f));

        fclose($read_file);
    }

    if ($intOnly) {

        $values = test_interpret_or_parser($src_file, $expected_value, $out_f, $in_f, "interpret");

        array_push($result, $values);
    } else if ($parseOnly) {

        $values = test_interpret_or_parser($src_file, $expected_value, $out_f, $in_f, "parser");

        array_push($result, $values);
    } else if (!$parseOnly && !$intOnly) {

        $values = test_both($src_file, $expected_value, $out_f, $in_f);

        array_push($result, $values);
    }
}

if ($parseOnly) {

    $tested_script = $parsephp;
} else if ($intOnly) {

    $tested_script = $interpretpy;
} else {

    $tested_script = $interpretpy . " and " . $parsephp;
}

// html napisane pomocou templatov
echo '

<!DOCTYPE HTML>

<html>

    <head>

	    <meta charset="UTF-8"> ';



echo '

	    <title>IPP test.php results</title>

	    <style type="text/css">

        *{

            margin: 0;

            padding: 0;

            box-sizing: border-box;

            

        }

        body{
            background: rgb(0,0,0);
            background: linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(29,29,31,1) 50%, rgba(94,100,101,1) 100%);
            min-width: 700px;
            

        ';


echo '

            font-family: Arial;';

echo '  }

        h3 {

            font-weight: initial;

        }

        header{

            background: black;
            background: linear-gradient(0deg, rgba(0,0,0,1) 0%, rgba(29,29,31,1) 50%, rgba(94,100,101,1) 100%);

            width: 100%;

            height: auto;

            padding: 10px 0;

            color: white;

        }

        .header_content{

            display: flex;

            align-items: center;

            justify-content: space-between;

            font-size: 23px;

            max-width: 1240px;

            margin: 0 auto;

            padding: 0px 40px;

        }

        .header_content span{

            font-weight: 100;

        }

        #content{

            color: white;

        }

        #content .overview {

            display: flex;

            justify-content: space-between;

            padding: 40px;

            margin: 0 auto;

            max-width: 1240px;

        }

        #content .package{

            background: #404040;

        }

        #content .overview .summary h3{

            display: inline-block;

            padding-left: 30px;

        }

        #content .overview h3 span{

            font-weight: bold;

        }

        #content .results {

            display: flex;

            flex-flow: column;

            padding: 30px 40px 60px 40px;

            margin: 0 auto;

            max-width: 1240px;

            

        }

        #content .results .row{

            display: flex;

            justify-content: space-between;

            margin-bottom: 12px;

        }

        #content .results .row:not(:first-child){

            background: #595959;

           

        }

        #content .results .row h3{

            padding: 15px 20px;

        }

        #content .results .row .field{

            display: flex;

            align-items: center

        }

        #content .results .row .field:first-child h3{

            text-align: left;

            max-width: 350px;

            word-break:  break-all;

        }

        #content .results .row .field:first-child {

            width: 100%;

        }

        #content .results .row:not(:first-child) .field:not(:first-child) {

            border-left: solid 1px #8c8c8c;

        }

        #content .results .row .field:not(:first-child) {

            text-align: center;

            justify-content: center;

            width: 40%;

        }

        #content .overview .no_files {

            width: 100%;

            text-align: center;

        }

        .check, .cross{

    

            display: block;

            height: 25px;

            width: 25px;

    
        }


        .check:before, .check:after, .cross:before, .cross:after{

            display: block;

            background: white;

            width: 4px;

            content: " ";

            position: relative;

        }

        .check:before{

            transform: rotate(40deg);

            height: 16px;

            left: 16px;

            top: 7px;

        }

        .check:after{

            transform: rotate(-50deg);

            height: 10px;

            left: 9px;

            top: -3px;

        }

        .cross:after{

            transform: rotate(-45deg);

            height: 20px;

            margin: 0 auto;

            left: 0px;

            top: -15px;

        }

        .cross:before{

            transform: rotate(45deg);

            height: 20px;

            margin: 0 auto;

            top: 5px;

        }

        .bold{

            font-weight: bold;

        }

        </style>

    </head>

    <body>

        <header>

            <div class="header_content">

                <h2> <spanstyle="color:white;font-weight:bold">IPP testing ' . $tested_script . " using " . $directoryPath . '</span></h2>

               

            </div>

        </header>

        <section id="content">

            <div class="overview"> ';

if ($success + $failed == 0) {

    echo '      <h3 class="invalidPath"><span>No test files in given directory</span></h3>

            </div>

        </section>

    </body>

</html>





';

    return 0;
} else if ($success + $failed > 0) {

    echo '

                <div class="summary">
                    
                    <h3>Summary: -> TOTAL: <span style="color:lightblue;font-weight:bold">' . ($success + $failed) . '</span></h3>

                    <h3>PASSED %: <span style="color:lightblue;font-weight:bold">' . (($success * 100) / ($success + $failed)) . '%</span></h3>

                    <h3>PASSED: <span style="color:lightgreen;font-weight:bold">' . $success . '</span></h3>

                    <h3>FAILED: <span style="color:pink;font-weight:bold">' . $failed . '</span></h3>

                </div>

            </div>

            <div class="package">

                <div class="results">

                    <div class="row">

                        <div class="field">

                            <h3>Path file</h3>

                        </div>

                        <div class="field">

                            <h3>Expected return code</h3>

                        </div>

                        <div class="field">

                            <h3>Real return code</h3>

                        </div>

                        <div class="field">

                            <h3>Expected Out vs Actual Out</h3>

                        </div>

                        <div class="field">

                            <h3>Result</h3>

                        </div>

                    </div>';
}

foreach ($result as $one_result) {

    if ($one_result[4] == "SUCC") {

        $one_result[4] = '<a class="check"></a>';
    } else {

        $one_result[4] = '<a class="cross"></a>';
    }

    echo '

                    <div class="row">

                        <div class="field">

                            <h3>' . $one_result[0] . '</h3>

                        </div>

                        <div class="field">

                            <h3>' . $one_result[1] . '</h3>

                        </div>

                        <div class="field">

                            <h3>' . $one_result[2] . '</h3>

                        </div>

                        <div class="field">

                            <h3 class="bold">' . $one_result[3] . '</h3>

                        </div>

                        <div class="field">

                            <h3>' . $one_result[4] . '</h3>

                        </div>

                    </div>

    ';
}

echo '

                </div>

            </div>

        </section>

    </body>

</html>



';
