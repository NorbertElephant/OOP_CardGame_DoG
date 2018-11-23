<?php
require_once("lib/functions.php");
require_once("ini.php");


if( SRequest::getInstance()->get('del') !==null ) {
    SRequest::getInstance()->unset('session',APP_TAG);
    header('Location:index.php');
    exit;
}
if( SRequest::getInstance()->session(APP_TAG) !==null ) {
    header('Location:espace_membres.php');
    exit;
}


try {
    $user_model =new UserModel( SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    

    if(!empty(SRequest::getInstance()->post('pseudo')) && !empty(SRequest::getInstance()->post('psw')) ) {

        $_SESSION[APP_TAG]['user'] =  serialize($user_model->Connect(SRequest::getInstance()->post('pseudo'),SRequest::getInstance()->post('psw')));
        $user = unserialize($_SESSION[APP_TAG]['user']);

    }

  

    if(isset($user) && !empty($user)) {
        header('Location:espace_membres.php');
        exit;
    }

   

} catch (Exception $e) {
        header('Location: 404.php');
        exit;
}



?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Espace_de_connexion</title>

    <!-- Fontfaces CSS-->
    <link href="./assets/css/font-face.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="./assets/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="./assets/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="./assets/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="./assets/css/theme.css" rel="stylesheet" media="all">

    </head>
   


    <body class="animsition" style="animation-duration: 900ms; opacity: 1;">
        <main class="page-content--bge5" style="padding-top:40px;">   
   
            <?php     
                if(!isset($_SESSION[APP_TAG]['user']) || empty($_SESSION[APP_TAG]['user'])) {
                echo '
                <div class="container">
                        <div class="login-wrap">
                            <div class="login-content">
                                <div class="login-logo">
                                    <a href="#">
                                        <img src="./assets/images/logo-dog_03.png" alt="CoolAdmin">
                                    </a>
                                </div>
                                <h1> Espace de connexion </h1> 
                                <div class="login-form">
                                    <form action="" method="post">
                                        <div class="form-group">
                                            <label>Pseudo</label>
                                            <input class="au-input au-input--full" type="text" name="pseudo" placeholder="Pseudo">
                                        </div>
                                        <div class="form-group">
                                            <label>Mot de passe</label>
                                            <input class="au-input au-input--full" type="password" name="psw" placeholder="Mot de passe">
                                        </div>
                                        <div class="login-checkbox">
                                            <label>
                                                <a href="#">Forgotten Password?</a>
                                            </label>
                                        </div>
                                        <button class="au-btn au-btn--block btn-danger m-b-20" type="submit">se connecter</button>
                                    </form>
                                    <div class="register-link">
                                        <p>
                                           Vous avez une compte ?
                                            <a href="inscription.php">S\'incrire</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> ';
                }

                
            ?>      

        </main>



    <!-- Jquery JS-->
        <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
        <script src="vendor/bootstrap-4.1/popper.min.js"></script>
        <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
        <script src="vendor/slick/slick.min.js">
        </script>
        <script src="vendor/wow/wow.min.js"></script>
        <script src="vendor/animsition/animsition.min.js"></script>
        <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
        </script>
        <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
        <script src="vendor/counter-up/jquery.counterup.min.js">
        </script>
        <script src="vendor/circle-progress/circle-progress.min.js"></script>
        <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="vendor/chartjs/Chart.bundle.min.js"></script>
        <script src="vendor/select2/select2.min.js">
        </script>

    <!-- Main JS-->
        <script src="js/main.js"></script>
    </body>
</html>