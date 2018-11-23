<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Creation de Cartes</title>

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
            <div class="card-header"> <?php echo (isset($update_card))  ? 'Formulaire de Création et de Modification de cartes' : 'Formulaire de Creation et de Modification de  cartes'; ?></div>
            <div class="card-body card-block">
                <form action="" method="POST" class="">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Nom</div>
                            <input type="text" id="username3" name="name" class="form-control"<?php if(isset($update_card)) echo'value="'.$update_card->get_name().'"'; ?> >
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Description</div>
                            <input type="text" id="username3" name="description" class="form-control"
                            <?php if(isset($update_card)) echo'value="'.$update_card->get_description().'"'; ?> >
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Picture</div>
                            <input type="text" id="username3" name="picture" class="form-control"<?php if(isset($update_card)) echo'value="'.$update_card->get_picture().'"'; ?> >
                            <div class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Mana</div>
                            <input type="text" id="username3" name="mana" class="form-control"<?php if(isset($update_card)) echo'value="'.$update_card->get_mana().'"'; ?> >
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Points d'attaque</div>
                            <input type="text" id="username3" name="PA" class="form-control"<?php if(isset($update_card)) echo'value="'.$update_card->get_pa().'"'; ?> >
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Points de vies</div>
                            <input type="text" id="username3" name="PV" class="form-control"<?php if(isset($update_card)) echo'value="'.$update_card->get_hp().'"'; ?> >
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Citation</div>
                            <input type="text" id="username3" name="citation" class="form-control"<?php if(isset($update_card)) echo'value="'.$update_card->get_quote().'"'; ?> >
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Faction</div>
                            <input type="text" id="username3" name="faction" class="form-control"<?php if(isset($update_card)) echo'value="'.$update_card->get_().'"'; ?> >
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Copy Max</div>
                            <input type="text" id="username3" name="nbcopy" class="form-control"<?php if(isset($update_card)) echo'value="'.$update_card->get_copy_max().'"'; ?> >
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                    
                    <br>                    
                    <div class="form-actions form-group">
                        <button type="submit" class="btn btn-danger btn-sm" <?php echo (isset($update_card))  ? ' name="modifier"> Créer </button> ' : ' name="creer" > Créer </button> '; ?> 
                    </div>
                    <div class="form-actions form-group">
                        <button type="submit" class="btn btn-danger btn-sm" <?php echo (isset($update_card))  ? ' name="modifier"> Modifier </button> ' : ' name="creer" > Modifier </button> '; ?> 
                    </div>
                    <!-- message de succes -->
                    <?php if(isset($update)) echo'<div class="alert alert-success" role="alert"> '.$update.' </div>'; ?> 
                        <?php if(isset($create_card)) echo '<div class="alert alert-success" role="alert"> '.$create_card.' </div>'; ?>

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