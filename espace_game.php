<?php 
require_once("lib/functions.php");
require_once("ini.php");

if(SRequest::getInstance()->get('del') !==null) {
    SRequest::getInstance()->unset('session',APP_TAG);
    header('Location:index.php?del');
    exit;
}

try {
    $user_model =new UserModel( SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $hero_model = new HeroModel(SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $deck_model = new DeckModel(SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $player_model = new PlayerModel (SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $card_model = new CardModel (SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());
    $game_model = new GameModel (SPDO::getInstance( DB_HOST, DB_NAME, DB_LOGIN, DB_PWD )->getPDO());

    $heros = $hero_model-> readAll();
   

    if(isset($_SESSION[APP_TAG]['user'])) {
        $user =  unserialize($_SESSION[APP_TAG]['user']);
        $user = $user_model->ReadOne($user->get_id());
    }

    if(SRequest::getInstance()->post('delete')){
        $target = $user_model->ReadOne(SRequest::getInstance()->post('delete'));

        if ( ($error = $user->ValidDel($target)) === true){
                $delete = $user_model->DeleteUser(SRequest::getInstance()->post('delete'));
                unset($error);
        }
    }

    $connect = $user->get_connect();
    // var_dump($connect);
    if ($connect == 0 ) {
        $player = $player_model->ReadOneByUser($user->get_id()); 
     
        if($player !== false ) {
            $heroGame = $hero_model->ReadOneHeroGameByPlayer( $player); // Lire le Héro_Game
            $cardsGame = $card_model->ReadAllCardGame($player); // Lire les Cards_Game
            $game = $game_model->ReadOneByPlayer($player); // Lire la game relié 
            // var_dump($game);
            $player->set_heroGame($heroGame); // Envoi du Hero Game dans le player
            $player->set_deck($cardsGame); // Envoi des Cards Game dans le player
            $player->set_game($game); // Envoi de la Game dans le player
            // var_dump($player);
            DeleteObjectGame($player, $game, $card_model, $game_model, $hero_model, $player_model);
        }
    }



    if (SRequest::getInstance()->get('hero') !== NULL ) {
       $decks = $deck_model->ReadAllByPlayer($user,SRequest::getInstance()->get('hero') );
      
    }
    if (SRequest::getInstance()->get('hero') !== NULL && SRequest::getInstance()->get('deck') !== NULL && SRequest::getInstance()->get('jouer') !== NULL  ) {
        $connect = $user_model->UpdateUserConnectOn($user);

        if($connect == true) {

            // Connaitre si l'user a un player ou non  -- S'il n'existe pas = FAlSE -- s'il existe = Object Player 
            $player = $player_model->ReadOneByUser($user->get_id()); 


            // A repenser /!\  $player = Player qui est en Game (Faire l'algo et function permettant de savoir si le joueur a une partie en cours ou non ) 
             // -> Si FALSE = Initialisation de la Game 
             // -> Si TRUE = reprendre la partie en cours 

            if($player === false ) { // Initialisation de la Game 

                $hero = $hero_model->ReadOneHeroModel(SRequest::getInstance()->get('hero')); // Création du Héro_Model 
                $IdHeroGame = $hero_model->CreateHeroGame($hero); // Création du Héro_Game
                $heroGame = $hero_model->ReadOneHeroGame( $IdHeroGame); // Lire le Héro_Game
                $deck = $deck_model->ReadOneDeck(SRequest::getInstance()->get('deck')); // Création du Deck_Model
             
                $Idplayer = $player_model->CreatePlayer($user, $heroGame, $deck ); // Création du Player en BDD 
                $player = $player_model->ReadOne( $Idplayer); // Lire Un Player

                /**************** CREATION COPY CARD ************ */
                $cardsDeck =$card_model->ReadCardsByDeck($deck->get_id()); // Récupération de toutes les Cartes_Model du Decks      
                // var_dump($cardsDeck);
                foreach ($cardsDeck as  $card) { // Boucle pour créer les Cards_Game
                    $max =  $card->get_copy_max();
                    for($i=0; $i<$max; $i++ ) {
                        $idcardGame = $card_model->CreateCardGame($player , $card);
                    }
                }

                $cardsGame = $card_model->ReadAllCardGame($player); // Lire les Cards_Game
                
                $player->set_heroGame($heroGame);
                $player->set_deck($cardsGame); // Envoi des Cards Game dans le player

                //!\\    Création ou Rejoindre la Game  //!\\ 
                $game = $game_model->IncompleteGame();
                // var_dump($game);
               
                if($game === NULL ) {
                    $game = $game_model->CreateGame($player);
                    $player->set_game($game); 
                    if($player->FirstPlayer() === true) {
                        $hero_model-> UpdateTurnOn($player);
                        $hero_model-> UpdateNumTurn($player);
                    }
                    header('Location:player_waiting.php');
                } else {
                    $game = $game_model->ReadOne($game['REJ_game_fk']);
                    $game = $game_model->RejoinGame($player,$game);
                    $player->set_game($game);
                    header('Location:player_waiting.php');
                }

            } else { // Partir Sur la Partie Car il a une Game  
          
                // Redirection a la Game 
                header('Location:gameview.php');
            }

           
           
        } 
    }
 
} catch (Exception $e) {
        // header('Location: 404.php');
        // exit;
}

?><!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>espace_admin</title>

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
        <?php include('nav.php'); ?>
    <body class="animsition" style="animation-duration: 900ms; opacity: 1;">
    <main class="page-content--bge5" style="padding-top:40px;">  
        <section class="p-t-20">
            <?php 
            if (isset($error)) {
            echo'<div class="alert alert-danger" role="alert"> '. Error($error).' </div>';
            } ?>
             <div class="container">
                <h3> Listing des Héros </h3>
                <div class=" row col-md-12">
                    <form method ="GET" class="row col-md-12">
                        <?php
                            foreach ($heros as $hero) {
                                if (SRequest::getInstance()->get('hero') == $hero->get_id() ) {
                                    echo '<div class=" col-lg-6 selected ">
                                            <div class="card-header">
                                            <input type="radio" name="hero" id="hero" value="'. $hero->get_id() .' " checked="checked">
                                    ' ;
                                } else {
                                    echo '<div class=" col-lg-6">
                                    <div class="card-header">
                                    <input type="radio" name="hero" id="hero" value="'. $hero->get_id() .' " ';
                                }
                        ?>
                
                        
                                <i class="fa fa-user"></i>
                                <strong class="card-title pl-2"><?php echo $hero->get_factionName(); ?></strong>
                            </div>
                            <div class="card-body">
                                <div class="mx-auto d-block">
                                    <img class=" mx-auto d-block hero" src="<?php echo $hero->get_picture(); ?>" alt="Card image cap">
                                    <h5 class="text-sm-center mt-2 mb-1"><?php echo $hero->get_name(); ?></h5>
                                    <div class="location text-sm-center">
                                </div>
                                <hr>
                            </div>
                        </div>
                        </div> 
                        <?php } ?>
                        <button  class="btn btn-info btn-sm"> Choisis ton Héro ! </button> 
                    <form> 
                </div>

                <?php if (!empty($decks)) { ?>
                <div class=" row col-md-12">
                    <form action=".?hero=<?php echo SRequest::getInstance()->get('hero')?> " method ="GET" class="row col-md-12">
                        <?php 
                
                            foreach ($decks as $deck) {
                                if (SRequest::getInstance()->get('hero') == $hero->get_id() ) { 
                                    echo '<input type="radio" name="deck" id="deck" value="'.$deck->get_id().'" Checked="checked">'.$deck->get_name().'</input> <br>';
                                } else {
                                    echo '<input type="radio" name="deck" id="deck" value="'.$deck->get_id().'">'.$deck->get_name().'</input> <br>';
                                }
                            
                            }
                        
                        ?>
                        
                        <button class="btn btn-info btn-sm"> Choisis ton Deck ! </button> 
                    </form> 
                </div>
                
                <?php } elseif(SRequest::getInstance()->get('hero')!== null && $decks !== null) {
                    echo '<p class="alert alert-danger">Vous n\'avez pas de deck de cette faction </p>'; 
                }
                 if (!empty(SRequest::getInstance()->get('hero')) && !empty(SRequest::getInstance()->get('deck')) ) {
                     echo ' <div style="float:right;"> <a class="btn btn-success btn-sm" href="espace_game.php?hero='.SRequest::getInstance()->get('hero').'&deck='.SRequest::getInstance()->get('deck').'&jouer=true "> Jouer </a>  </div>';
                 }
                
                ?>
                
            </section>

             <?php if(isset($delete)) echo'<div class="alert alert-success" role="alert"> '.$delete.' </div>'; 
            //  var_dump($decks);
             ?> 
        </main>



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