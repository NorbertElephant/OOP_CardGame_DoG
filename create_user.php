<?php 
require_once("lib/functions.php");
require_once("ini.php");



if(SRequest::getInstance()->get('del') !==null) {
    SRequest::getInstance()->unset('session',APP_TAG);
    header('Location:index.php?del');
    exit;
}

try {
    $rank_model =new RankModel( SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
 
    if(isset($_SESSION[APP_TAG]['user'])) {
        $user =  unserialize($_SESSION[APP_TAG]['user']);
        $user_model= new UserModel( SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    }
    
    
    if(!empty(SRequest::getInstance()->get('id'))) {
        $update_user = $user_model->ReadOne(SRequest::getInstance()->get('id'));
    }
    
 
    if ( (SRequest::getInstance()->post('creer') !== null ) || (SRequest::getInstance()->post('modifier') !==null ) ) {
           $error = $user->NoEmptyDataUser(SRequest::getInstance()->post());

        if($error === false ) {

            if (SRequest::getInstance()->post('modifier') !== null) {
                if( ($valid = $user_model->ValidUpdate($update_user)) === true) {
                    
                    if ($user->get_power() <= $update_user->get_power()) {
                        $update = $user_model->UpdateUser($update_user->get_id(), SRequest::getInstance()->post());
                        $update_user = $user_model->ReadOne(SRequest::getInstance()->get('id'));
                    }            
                  
                }
                    
                } else {
                    if( ($valid = $user_model->ValidCreate(SRequest::getInstance()->post('pseudo'),SRequest::getInstance()->post('email')) )  === true ) {
                        $create_user = $user_model->CreateUser(SRequest::getInstance()->post()); 
                    }
                 
                } 
                if($valid !== true) {
                    if ((SRequest::getInstance()->post('pseudo') ==  $valid->get_pseudo())) {
                        $error = '_err:pseudo_utilise';
                    } else {
                        $error = '_err:email_utilise';
                    }
                }
                unset($error); 
        }
    }    

$listing_rank = $rank_model->ReadAll($user->get_power());
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
    <title>Creation d'User</title>

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
 
    <?php include('nav.php'); ?>
<main class="page-content--bge5" style="padding-top:40px;">    

    <?php if (isset($error)) {
        echo'<div class="alert alert-danger" role="alert"> '. Error($error).' </div>';
    } ?>
    <div class="col-lg-7 login-wrap login-content">
        <div class="card-body card-block">
            <div class="card-header"> <?php echo (isset($update_user))  ? 'Formulaire de Modification D\'user' : 'Formulaire de Creation D\'user'; ?></div>
            <div class="card-body card-block">
                <form action="" method="POST" class="">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Nom</div>
                            <input type="text" id="username3" name="name" class="form-control"<?php if(isset($update_user)) echo'value="'.$update_user->get_name().'"'; ?> >
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Prénom</div>
                            <input type="text" id="username3" name="firstname" class="form-control"
                            <?php if(isset($update_user)) echo'value="'.$update_user->get_firstname().'"'; ?> >
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Pseudo</div>
                            <input type="text" id="username3" name="pseudo" class="form-control"<?php if(isset($update_user)) echo'value="'.$update_user->get_pseudo().'"'; ?> >
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Email</div>
                            <input type="email" id="email3" name="email" class="form-control"
                            <?php if(isset($update_user)) echo'value="'.$update_user->get_email().'"'; ?> >
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Password</div>
                            <input type="password" id="password3" name="psw" class="form-control">
                            <div class="input-group-addon">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col col-md-4">
                            <label for="select" class=" form-control-label">Rang User :</label>
                        </div>
                        <div class="col-8 col-md-8">
                        <?php if(!empty($listing_rank)) { ?>
                            <select name="rank" id="select" class="form-control">
                                <?php 
                                    foreach ($listing_rank as $value){
                                        if(isset($update_user)){
                                            $value->ShowOption($update_user->get_rank_fk()); 
                                        } else {
                                            $value->ShowOption(1);
                                        }
                                    }
                                }?>
                            </select>
                        </div>
                    </div>
                    <br>                    
                    <div class="form-actions form-group">
                        <button type="submit" class="btn btn-danger btn-sm" <?php echo (isset($update_user))  ? ' name="modifier"> Modifier </button> ' : ' name="creer" > Créer </button> '; ?> 
                    </div>
                    <!-- message de succes -->
                    <?php if(isset($update)) echo'<div class="alert alert-success" role="alert"> '.$update.' </div>'; ?> 
                        <?php if(isset($create_user)) echo '<div class="alert alert-success" role="alert"> '.$create_user.' </div>'; ?>

                </form>
            </div>
        </div>
    </div>
</main>

<footer>
</footer
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
