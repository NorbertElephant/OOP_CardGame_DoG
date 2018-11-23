<?php 
require_once("lib/functions.php");
require_once("ini.php");

if (!isset($_SESSION[APP_TAG]['progress'])) {
    $_SESSION[APP_TAG]['progress'] = 1;
} else {
    $_SESSION[APP_TAG]['progress'] =  $_SESSION[APP_TAG]['progress'] + 1 ;
}

if(SRequest::getInstance()->get('del') !==null) {
    SRequest::getInstance()->unset('session',APP_TAG);
    header('Location:index.php?del');
    exit;
}

// Récupérer les données de la Session de l'user pour le mettre en Player1 et chercher le Player2 dans la BDD // Marche OK  
try {
    $user_model =new UserModel( SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $hero_model = new HeroModel(SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $deck_model = new DeckModel(SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $player_model = new PlayerModel (SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $card_model = new CardModel (SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $game_model = new GameModel (SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    

    if(isset($_SESSION[APP_TAG]['user'])) {
        $user =  unserialize($_SESSION[APP_TAG]['user']) ;
        $player1 = $player_model->ReadOneByUser($user->get_id());  // Lire le pLayer 
        $heroGame = $hero_model->ReadOneHeroGameByPlayer( $player1); // Lire le Héro_Game
        $cardsGame = $card_model->ReadAllCardGame($player1); // Lire les Cards_Game
        $game = $game_model->ReadOneByPlayer($player1); // Lire la game relié 
        // var_dump($game);
        $player1->set_heroGame($heroGame); // Envoi du Hero Game dans le player
        $player1->set_deck($cardsGame); // Envoi des Cards Game dans le player
        $player1->set_game($game); // Envoi de la Game dans le player
        // var_dump($player1);

    // ------------- RECUPERER LE JOUEUR 2 DE LA PARTIE  --------------------------------------// Marche OK 
        // Récupération en BDD du Second joueur -> Création du Player 2 Donc J2 
        $idplayer2 = $game_model->FindPlayerGame($game, $player1); // Récupérer l'id de l'autre Player 
        // var_dump($idplayer2);
        if($idplayer2 !== false ){
            unset($_SESSION[APP_TAG]['progress']);
            header('Location:gameview.php');
        }
    }
} catch (Exception $e) {
    header('Location: 404.php');
    exit;
}




?><!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="refresh" content="10" />

        <title>Player_waiting</title>

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
    <body>
        <main>
            <section>
            <div class="card mx-auto">
                <div class="card-header">
                    <h4>En attente de Joueurs</h4>
                </div>
                <div class="card-body">
                    <?php switch ($_SESSION[APP_TAG]['progress']) {
                        case '1':
                            echo ' <div class="progress mb-2">
                                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                    </div>';
                            break;
                        case '2':
                        echo ' <div class="progress mb-2">
                                    <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                                </div>';
                        break;
                        case '3':
                        echo ' <div class="progress mb-2">
                                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                 </div>';
                        break;
                        case '3':
                        echo ' <div class="progress mb-2">
                                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                                 </div>';
                        break;
                        case '4':
                        echo ' <div class="progress mb-2">
                                    <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">90%</div>
                                </div>';
                        break;
                        
                        default:
                        echo ' <div class="progress mb-2">
                                  <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                </div>';
                            break;
                    } ?>
                   
                </div>
            </div>
            </section>
        </main>
    </body>
            
     <!-- Jquery JS-->
  <script src="./assets/vendor/jquery-3.2.1.min.js"></script>
  <!-- Bootstrap JS-->
  <script src="./assets/bootstrap-4.1/popper.min.js"></script>
  <script src="./assets/bootstrap-4.1/bootstrap.min.js"></script>
  <!-- Vendor JS       -->
  <script src="./assets/slick/slick.min.js">
  </script>
  <script src="./assets/wow/wow.min.js"></script>
  <script src="./assets/animsition/animsition.min.js"></script>
  <script src="./assets/bootstrap-progressbar/bootstrap-progressbar.min.js">
  </script>
  <script src="./assets/vendor/counter-up/jquery.waypoints.min.js"></script>
  <script src="./assets/vendor/counter-up/jquery.counterup.min.js">
  </script>
  <script src="./assets/vendor/circle-progress/circle-progress.min.js"></script>
  <script src="./assets/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="./assets/vendor/chartjs/Chart.bundle.min.js"></script>
  <script src="./assets/vendor/select2/select2.min.js">
  </script>

  <!-- Main JS-->
  <script src="./assets/js/main.js"></script>
        
    </body>
</html>