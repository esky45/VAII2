<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="./css/style.css">

</head>

<body>
<!-- NAVIGATION -->
<?php

?>
<section id=" "  class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
        <h3>Tester</h3>
        <hr class="mx-auto">

    </div>

    <div class="row mx-auto container">
        <form id="login_form">
            <div class="alert alert-danger" role="alert" id="div-error-1" style="display: none">

            </div>

            <a class="btn btn-primary" aria-current="page" href="index.php">viewTest</a>
            <a class="btn btn-primary" aria-current="page" href="int/report.html">output</a>
            <a class="btn btn-primary" aria-current="page" href=<?php exec("php8.2 int/test.php --recursive > output.html");    ?>>RunTest</a>
            
        </form>


    </div>
</section>





</body>

</html>