<?php
// Pour les Batterie de TEST // Initialisation des Sessions 


//Importer Fichiers nécessaires pour le Jeu
require_once("lib/functions.php");
require_once("ini.php");

// Pouvoir supprimer la Session en Cours du Jeu 
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
        if ($player1->get_connect() !== false) {
            // if($idplayer2 === false ) {
            //     header('Location:player_waiting.php');
            // }
            $user2 = $user_model->ReadOneByPlayer($idplayer2); // Lire l'user à partir du Player
            $player2 = $player_model->ReadOneByUser($user2->get_id());  // Lire le pLayer 
            $heroGame = $hero_model->ReadOneHeroGameByPlayer( $player2); // Lire le Héro_Game
            $cardsGame = $card_model->ReadAllCardGame($player2); // Lire les Cards_Game
            $game = $game_model->ReadOneByPlayer($player2); // Lire la game relié 

            $player2->set_heroGame($heroGame); // Envoi du Hero Game dans le player
            $player2->set_deck($cardsGame); // Envoi des Cards Game dans le player
            $player2->set_game($game); // Envoi de la Game dans le player
        }
       
       
    } else {
        header('Location:index.php?_err:game');
    }
        
} catch (Exception $e) {
    header('Location: 404.php');
    // exit;
}
$MessageJouerCarte_Joueur1 ='';
$MessageJouerCarte_Joueur2 ='';

$BoardAdv = $player2->get_CardsOnBoard();




/*************************************************************************************************************************** */
        // METHODES QUI SERA DANS LE GAME CONTROLLER -  // Tout est trop mal FAIT !!!!!!!!!!
/*************************************************************************************************************************** */
define("SORT", 2);
define("BOUCLIER", 4);
define("EARLY_DRAW_CARD", 3);
define("MAX_CARD_HAND", 10);
define("MAX_CARD_BOARD", 7);
define("MANA_MAX", 10);
define("GAIN_MANA_TURN", 1);

// Dans le controller je n'aurais plus besoin des model dans les méthods 
function Swap($player, $post, $card_model) {
    if(!empty($post['Hand'])){
        $number = count($post['Hand']);
        #Remettre les cartes dans le Deck et unset de la main
        if($number > 0 ){
            foreach ($post['Hand'] as $key => $element) {

                foreach ($player->get_CardsInHand() as $key_Carte => $carte) { 
                    $verif = false;
                    if($element == $carte->get_id()) {
                        $verif = true;
                    }
                    if ($verif == true ){ /// Quand BDD prendre la Clé Id Carte
                        // remettre les cartes dans le decks
                        $card_model->UpdateDeckcard($carte);
                    }
                }
            }

            #Re-piocher le nombre de cartes défausser 
            for ($i=0; $i < $number ; $i++) { 
                Draw($player, $card_model);
            }
        
        }

    }
    
    return true;
}
/**
 * Draw................ Piocher une carte 
 *
 * @param Player $player
 * @param CardModel $card_model
 * @return void
 */
function Draw (Player $player, CardModel $card_model) {
    $cardDraw = $player->get_CardDraw();
    if(count($player->get_CardsInHand()) <  MAX_CARD_HAND ) {
        if($card_model->UpdateDrawCard($cardDraw)) {
            return true;
        } 
    }else {
        if($card_model->UpdateCemeterriesCard($cardDraw)) {
            return true;

        }
    }
}
/**
 * DrawEarlyGame
 *
 * @param Player $player
 * @param CardModel $card_model
 * @return void
 */
function DrawEarlyGame(Player $player, CardModel $card_model){
    if($player->get_heroGame()->get_turn() == true){
        for ($i=0; $i < EARLY_DRAW_CARD ; $i++) { 
            Draw($player, $card_model);
        }
      } else {
          for ($i=0; $i < (EARLY_DRAW_CARD + 1) ; $i++) { 
            Draw($player, $card_model);
            }
        }
}
/**
 * EndOfGame................. Fin de jeu
 *
 * @param Player $player1
 * @param Player $player2
 * @return void
 */
function EndOfGame (Player $player1, Player $player2) {
    if($player1->get_heroGame()->get_hp() <= 0 || ( empty($player1->get_CardsInHand()) && empty($player1->get_CardsOnBoard()) && empty($player1->get_CardsInDeck()) ) ){
        return 'Player2';
    }elseif ($player2->get_heroGame()->get_hp() <= 0 || ((empty($player2->get_CardsInHand()) && empty($player2->get_CardsOnBoard())) && empty($player2->get_CardsInDeck())) ){
        return 'Player1';
    }
    return NULL;
}
// A REVOIR 
//////****//////**************************************************************************************************************/ */


/**
 * GameOver................... 
 *
 * @param Player $player1
 * @param Player $player2
 * @param UserModel $user_model
 * @param GameModel $game_model
 * @param String $Winner
 * @return void
 */
function GameOver(Player $player, Game $game , String $Winner, UserModel $user_model,GameModel $game_model,CardModel $card_model, HeroModel $hero_model,PlayerModel $player_model) {
    
    if ($Winner === 'Player1') {
        $message="Vous avez Gagné la partie";
    } elseif($Winner === 'Player2') {
        $message="Vous avez Perdu la partie";
    }

    $user_model->UpdateNumGamePlayed($player); // augmenter le nombre de partie jouées
    $user_model->UpdateNumGameWin($player); // augmenter le nombre de partie gagnés
    $game_model->UpdateEndOfGame($game); // Update en Fin de Game 
    $user_model->UpdateUserConnectOff($player); // Update l'User pour qu'il ne soit plus en jeux 


    return $message;
        
} 



//////****//////**************************************************************************************************************/ */
/**
 * PlayingCard
 *
 * @param Player $player1
 * @param string $MessagePlayCard
 * @param array $post
 * @param Player $player2
 * @param HeroModel $hero_model
 * @param CardModel $card_model
 * @return void
 */
function PlayingCard (Player $player1, string &$MessagePlayCard,array $post, Player $player2, HeroModel $hero_model, CardModel $card_model){
    
    $CardsInHand = $player1->get_CardsInHand();

    foreach ($CardsInHand as $key => $card) { 
        if( ($card->get_id()) == $post['Hand'][0]){
            // Lancer Un Sort  // 
            if($card->get_card() == SORT ){ // Lancer Un Sort 
                if ($player1->PlayCard($CardsInHand[$key]) == true) {

                    $hero_model->UpdateMana($player1);

                    $CardsInHand[$key]->PlaySpellAttackArea($player2); // Sort De Dégats De Zone
                    #Si on veut faire plusieurs types de sort juste a faire un switch et envoyé sur la fonction désiré ! 

                    foreach ($player2->get_CardsOnBoard() as $card) {
                        $card_model->UpdateHpCard($card);
                        if($card->get_hp() <= 0) {
                            $card_model->UpdateCemeterriesCard($card);
                        } 
                    }
                    $card_model->UpdateCemeterriesCard($CardsInHand[$key]);

                   return $MessagePlayCard = 'Vous avez joué la sort !';
                    
                } else {
                    return $MessagePlayCard = 'Vous n\'avez pas assez de mana pour jouer cette carte ';
                }
            } else {
            // Jouer Une Carte Créature ou Spéciale //
                if(count($player1->get_CardsOnBoard()) < 7) {
                    if ($player1->PlayCard($CardsInHand[$key]) == true) { 

                        $hero_model->UpdateMana($player1);
                        $card_model->UpdateBoardCard($CardsInHand[$key]);
                        
                        return  $MessagePlayCard = 'Vous avez joué la carte !';
                    } else {
                        return $MessagePlayCard = 'Vous n\'avez pas assez de mana pour jouer cette carte ';
                    }

                } else {
                    return  $MessagePlayCard = 'Vous ne pouvez pas joué de carte car il ya trop de carte sur le Board !';
                }
                  
            }
        }
    }
};


/**
 *EndOfTurn......................... Fonction permettant de mettre fin a un tour et de faire tout le processus qui en découle 
 *
 * @param Player $player1.................. Objet Player 
 * @param Player $player2.................. Objet Player Adverse
 * @param HeroModel $hero_model............ Hero Model afin de modifier dans la BDD
 * @param CardModel $card_model............ Card Model afin de modifier dans la BDD 
 * @return void
 */
function EndOfTurn (Player $player1, Player $player2, HeroModel $hero_model, CardModel $card_model) {

    $hero_model->UpdateTurnOff($player1); // Passer sont tour a off

    if( $player2->get_heroGame()->get_mana_max() < MANA_MAX ){
        $player2->get_heroGame()->set_mana_max($player2->get_heroGame()->get_mana_max() + GAIN_MANA_TURN ); // +1 Mana Max
    }
    $hero_model->UpdateManaMax($player2); // +1 Mana Max
    $hero_model->UpdateManaTurn($player2); // Reset Mana Turn
   
    Draw($player2,$card_model); // Fonction qui permet de Piocher une carte Si possible  
    // chercher le model pour modifier car Draw doit être dans le player.. 
    

    $AwakenedCards = $player2->get_CardsOnBoard(); // Réveil des Créatures Lors Du Tour du Joueur
    if(count($AwakenedCards) > 0 ){
        foreach ($AwakenedCards as $key => $card) {
            $card_model->UpdateEtatOn($card); // Modif en BDD 
        }
    }
    $hero_model->UpdateTurnOn($player2); // Mettre le Tour du Player 2 en ON 
    $hero_model->UpdateNumTurn($player2);

    } 


/**
 * WakeAttackCards.............. Réveil des cartes permettant qu'elles puissent attaquer 
 *
 * @param Player $player..................... Objet Player 
 * @param CardModel $card_model.............. Card Model afin de modifier dans la BDD le statut des Cartes 
 * @return void
 */
function WakeAttackCards(Player $player ,CardModel $card_model) { 
        foreach ($player->get_CardsOnBoard() as $key=>$card) {
           $card_model->UpdateEtatOn($card); // Mal Fait ! ça doit etre dans Card
        }
    }



/**
 * Attack......................... Fonction permettant d'attaquer et de faire tout le processus qui en découle  
 *
 * @param Player $player1.................. Objet Player 
 * @param Array $post...................... Tableau des Posts
 * @param Player $player2.................. Objet Player Adverse
 * @param CardModel $card_model............ Card Model afin de modifier dans la BDD 
 * @param HeroModel $hero_model............ Hero Model afin de modifier dans la BDD
 * @return void
 */
function Attack (Player $player1 , $post, Player $player2, CardModel $card_model, HeroModel $hero_model){

    foreach ($player1->get_CardsOnBoard() as $key=>$card) {
        if($card->get_id() == $post['Board'] )  { // Connaitre la Carte qui attaque 
            if($card->get_etat() == true) { // Si c'est bien a la carte d'attaquer
                if($player1->get_heroGame()->get_turn() == true){ // Si c'est bien au bon joueur de Jouer 

                    if($post['Attack'] == 'hero'){ // Attaque Sur Le Héro Adverse
                       
                        if( $card->AttackHero($player2) === false ) {
                            $hero_model->UpdateHpHeroGame($player2);
                            return EndofGame($player1,$player2);
                        } else {
                            $hero_model->UpdateHpHeroGame($player2);
                            $card_model->UpdateEtatOff($card);
    
                        }
            
                    } else { // Attaque Sur La Carte Adverse

                        foreach ($player2->get_CardsOnBoard() as $key => $cardp2) {
                            if($cardp2->get_id() == $post['Attack']) {

                                $cardp2->AttackCard($card);
                                $card->AttackCard($cardp2); 
                                
                                $card_model->UpdateHpCard( $card); // MAJ des pv 
                                $card_model->UpdateHpCard( $cardp2); // MAJ des pv De la carte adverse 
                                $card_model->UpdateEtatOff($card); // Mise en sommeil de la créature 
                                
                                // MAJ de la postion si la carte a 0 hp 
                                if($card->get_hp() <= 0) {
                                    $card_model->UpdateCemeterriesCard($card);
                                } 
                                if ($cardp2->get_hp() <= 0) {
                                    $card_model->UpdateCemeterriesCard($cardp2); 
                                }                           
                            } 
                        }
                    }
                }
            }
        }
    }
}


/*************************************************************************************************************************** */
        // ALGO DEBUT DE PARTIE - Montrer Carte avec choix de jouer carte -  
/*************************************************************************************************************************** */
 

    if ($player1->get_heroGame()->get_swap() === false) {
        if($player1->get_heroGame()->get_turn() === false) {
            $hero_model-> UpdateTurnOn($player2);
        }
        if(count($player1->get_CardsInHand()) < EARLY_DRAW_CARD ) {
            DrawEarlyGame($player1, $card_model);
        }
    }
    
 
if( SRequest::getInstance()->post('Swap') !== NULL) {
    if($player1->get_heroGame()->get_swap() === false){
        if (Swap($player1,Srequest::getInstance()->post(),$card_model)) {
            $hero_model->UpdateSwap($player1);
        }
    } 
}


/*************************************************************************************************************************** */
                                           // Algo FIN DE LA PARTIE  // 
/*************************************************************************************************************************** */

// /!\-------- Problème de Suppression des Cartes Games, Hero Games --------------------/!\ //


if(SRequest::getInstance()->post('Surrend') !== NULL) {
    $Winner = 'Player2';
    $message = GameOver($player1, $game, $Winner, $user_model, $game_model, $card_model, $hero_model,$player_model); // Mettre Fin a la Game
    GameOver($player2, $game, $Winner, $user_model, $game_model, $card_model, $hero_model,$player_model); 
}

if(EndOfGame($player1,$player2) !== Null) {
    $Winner = EndOfGame($player1,$player2);
    $message = GameOver($player1, $game, $Winner,  $user_model, $game_model, $card_model, $hero_model, $player_model ); // Mettre Fin a la Game 

}





/*************************************************************************************************************************** */
                                            // Algo JOUER CARTES // 
/*************************************************************************************************************************** */
if(SRequest::getInstance()->post('JouerCarte') !== NULL)  { 
    if( SRequest::getInstance()->post('Hand') !== NULL) {
        PlayingCard($player1, $MessageJouerCarte_Joueur1, SRequest::getInstance()->post(),$player2, $hero_model, $card_model);    
    } else{
        echo 'Veuillez Sélectionner une Carte';
    }
}


/*************************************************************************************************************************** */
                                           // Algo FIN DE TOUR  // 
/*************************************************************************************************************************** */


if(SRequest::getInstance()->post('FinDeTour') != NULL) {
    if ($player1->get_heroGame()->get_turn() === true) {
      EndOfTurn($player1, $player2, $hero_model, $card_model); 
    } 
} 


/*************************************************************************************************************************** */
                                           // Algo ATTAQUE // 
/*************************************************************************************************************************** */


if(SRequest::getInstance()->post('Attaque') !== NULL && SRequest::getInstance()->post('Board')  !== NULL)  { 
       echo Attack($player1,SRequest::getInstance()->post(), $player2, $card_model, $hero_model);
    } 

?> 
<!-- /*************************************************************************************************************************** */ !--> 
<!-- /*************************************************************************************************************************** */ !--> 
<?php 
// Rechargement Des players pour le Visu !  Fais a la Zeub 
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
    
    $user2 = $user_model->ReadOneByPlayer($idplayer2); // Lire l'user à partir du Player
    $player2 = $player_model->ReadOneByUser($user2->get_id());  // Lire le pLayer 
    $heroGame = $hero_model->ReadOneHeroGameByPlayer( $player2); // Lire le Héro_Game
    $cardsGame = $card_model->ReadAllCardGame($player2); // Lire les Cards_Game
    $game = $game_model->ReadOneByPlayer($player2); // Lire la game relié 

    $player2->set_heroGame($heroGame); // Envoi du Hero Game dans le player
    $player2->set_deck($cardsGame); // Envoi des Cards Game dans le player
    $player2->set_game($game); // Envoi de la Game dans le player

?> 
<!-- /*************************************************************************************************************************** */ !--> 
                                           <!-- Algo Principal avec Affichage ! --> 
<!-- /*************************************************************************************************************************** */ !--> 
               
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">


          <!-- Fontfaces CSS-->
        <link href="./assets/css/font-face.css" rel="stylesheet" media="all">
        <link href="./assets/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
        <link href="./assets/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
        <link href="./assets/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
        <link href="https://fonts.googleapis.com/css?family=Barlow" rel="stylesheet"> 
        <link href="./assets/css/font.css" rel="stylesheet" media="all">

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
        <link href="./assets/css/card.css" rel="stylesheet" media="all">
        <link href="./assets/css/game.css" rel="stylesheet">
        <link href="./assets/css/eventail.css" rel="stylesheet">

        <title>Duel Of Giants | Cut The Cook </title>
    </head>

    <body>
        <?php
            if(isset($message) ) {
                echo' <div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="staticModalLabel">Fin de la partie </h5>
						</div>
						<div class="modal-body">
							<p>
								'. $message.'
							</p>
						</div>
						<div class="modal-footer">
							<a href="espace_game.php" class="btn btn-primary">Retour à l\'acceuil </a>
						</div>
					</div>';
                
            } elseif($player1->get_connect() == false) {
                echo' <div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="staticModalLabel">Fin de la partie </h5>
						</div>
						<div class="modal-body">
							<p>
								Vous avez Gagné la partie
							</p>
						</div>
						<div class="modal-footer">
							<a href="espace_game.php" class="btn btn-primary">Retour à l\'acceuil </a>
						</div>
					</div>';
                
            }


        ?>
        <main class='fullPlateau'>
     
            <!--....................... Héro du player 2.................................... !--> 
            <div class="bannerHeroTop grid5">
                
                <div class='namePlayer calango beige'>
                    <h2 class='beige' > <?php echo $player2->get_name() ?> </h2>
                </div>
                
                <div class='HeroTop'>
                    <img class='heartTop' src="./assets/images/heart_hero.png" alt="">
                    <p class='hp calango beige'> <?php echo $player2->get_heroGame()->get_hp(); ?> <p> 
                </div>
            <!--....................... Carte De La Main Du Player 2 .................................... !--> 
                
                <div class="hand handTop">
                        <?php  echo $player2->ShowCardInHandBack(); ?> 
                </div>
                <!--.................. Mana Restant Par Tour Chiffre Et Image Joueur n°2.................................... !--> 
                <div class="ManaTop">
                    <div class='PointManaTop'>
                        <p class="pointManaTop calango beige"> <?php echo $player2->get_heroGame()->get_mana() ." / ". $player2->get_heroGame()->get_mana_max();?> </p>
                    </div>  
                </div>
              
            </div>
        

        <!--.................. Board Du Joueur n°1.................................... !--> 
        <form method="POST" action=""> 
            <div class="fullBoard grid3">
                <img class='circleTop' src="./assets/images/red_hero_circle.png" alt="">
                <img class='imageHeroTop' src="<?php echo $player2->get_heroGame()->get_pion(); ?>" alt="">
           
                <img class ="leftMountain"src="./assets/images/left_mountain.png" alt="">
                <img class ="rightMountain"src="./assets/images/right_mountain.png" alt="">
                <div class= "turn">
                    <?php if($player1->get_heroGame()->get_turn() === true ) { ?>
                    <button class="endOfTurn calango beige " name="FinDeTour" value="FinDeTour" type="submit" >
                    Fin Du Tour 
                     </button>
                     <?php } ?>
                     <p class=" turnOne calango beige"> <?php echo $player1->get_heroGame()->get_num_turn(); ?></p>
                     <p class="turnTwo calango beige"><?php echo $player2->get_heroGame()->get_num_turn(); ?> </p>
                     <?php 
                        if($player1->get_heroGame()->get_turn() === true) {
                    
                            echo '
                            <div class="btnPlay">
                                <div class="content-btn">
                                    <button name="Surrend" type="submit" class="btnDog calango beige" >
                                                    Abandonner La Partie
                                    </button> 
                                </div>  
                                <div style="display: inline-block">
                                    <div class="content-btn" >
                                        <button name="JouerCarte" type="submit" class="btnDog calango beige">
                                            Jouer la carte 
                                        </button> 
                                    </div>
                                    ';
                                if(count($player1->get_CardsOnBoard() ) >0 && $player1->get_CardCanAttack() == true){
                                    echo  '
                                    <select name ="Attack">
                                        '. $player2->get_CardsToAttack() .'
                                    </select> 
                                    <div class="content-btn" >
                                        <button name="Attaque" type="submit" class="btnDog calango beige">
                                            Attaquer
                                        </button>
                                    </div>
                                    ';
                                }
                                echo ' 
                                
                                </div> 
                            </div>
                            ';}
                     ?>
                </div>

                <div class='board'>
                    <div class="board2">
                        <?php 
                             echo $player2->ShowCardOnBoardAdv();
                        ?> 

                    </div>
                    <div class="board1">
                        <?php 
                             echo $player1->ShowCardOnBoard();
                        ?> 

                    </div>
                </div>
                <div class="decks">
                    <div class="deck2" style=":hover ">
                        <?php echo $player2->ShowCardBack() ?>
                        <span> <?php echo count($player2->get_CardsInDeck()) .' Cartes'; ?> </span> 
                    </div>
                    <div class="deck1">
                        <?php echo $player1->ShowCardBack() ?>
                        <span> <?php echo count($player1->get_CardsInDeck()) .' Cartes'; ?> </span> 
                    </div>
                </div>
       
        </div>
       

        <!--....................... Héro du player 1.................................... !--> 
        <div class="bannerHeroBot grid5Bot">
            <div class='namePlayer calango beige'>
                <h2 class='beige' > <?php echo $player1->get_name() ?> </h2>
            </div>
            <div class='Hero'> 
                    <img class='heartBot' src="./assets/images/heart_hero.png" alt="">
                    <img class='imageHeroBot' src="<?php echo $player1->get_heroGame()->get_pion(); ?>" alt="">
                    <img class='circleBot' src="./assets/images/rond_hero_circle.png" alt="">
                    <p class='hpBot calango beige'> <?php echo $player1->get_heroGame()->get_hp(); ?> <p>  
            </div>
        <!--....................... Carte De La Main Du Player 2 .................................... !--> 
            
            <div class="handBot hand">
                 <?php 
                        if ($player1->get_heroGame()->get_swap() === false) {
                        echo $player1->ShowCardForSwap();
                    } else {
                        echo $player1->ShowCardInHand();
                    
                    }
                ?> 
            </div>
            <!--.................. Mana Restant Par Tour Chiffre Et Image Joueur n°2.................................... !--> 
            
            <div class="ManaBot">
                <?php
                    $player1-> ShowManaImage();
                ?>
            </div>
            <div class='PointManaBot'>
                <p class="pointManaBot calango beige"> <?php echo $player1->get_heroGame()->get_mana() ." / ". $player1->get_heroGame()->get_mana_max();?> </p>
            </div>  
        </div>

       
     

        </form>

    </main>
<?php 
// var_dump(SRequest::getInstance()->post());
// var_dump($player1);
// var_dump($player2);
?>


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