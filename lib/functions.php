<?php 
function Load_Classes($class){
    if(file_exists("Classes/".$class.".php")){
        require_once("Classes/".$class.".php");
    }
}

function Load_Controllers($class){
    if(file_exists("Controllers/".$class.".php")){
        require_once("Controllers/".$class.".php");
    }
}

function Load_Models($class){
    if(file_exists("Models/".$class.".php")){
        require_once("Models/".$class.".php");
    }
}

spl_autoload_register('Load_Classes');
spl_autoload_register('Load_Controllers');
spl_autoload_register('Load_Models');

function Error ($error) {
    switch ($error) {
        case '_err:pseudo':
            return 'Pseudo non remplie'; 
            break;
        case '_err:name':
            return 'Nom non remplie'; 
            break;
        case '_err:firstname':
            return 'Prénom non remplie'; 
            break;
        case '_err:email':
            return 'Email non remplie'; 
            break;
        case '_err:psw':
            return 'Nom non remplie'; 
            break;
        case '_err:pseudo_utilise':
            return 'Pseudo déjà utilisé'; 
            break;
        case '_err:email_utilise':
            return 'Email déjà utilisé'; 
            break;
        case '_err:del':
            return 'Vous n\'avez pas pu supprimer'; 
            break;
        case '_err:dels':
            return 'Vous ne pouvez pas vous supprimez d\'ici'; 
            break;
        case '_err:game':
            return 'Un problème est survenue lors du chargement de la partie'; 
            break;
        case '_err:drawcard':
            return 'Un problème est survenue lors de la pioche de carte'; 
            break;
            
            
            
        default:
            # code...
            break;
    }
}



function DeleteObjectGame(Player $player, Game  $game, CardModel $card_model, GameModel $game_model, HeroModel $hero_model, PlayerModel $player_model) {
    foreach ($player->get_deck() as $key => $card) { // Supprimer les Card_Game
        $card_model->DeleteCardGame($card);
    }
    $game_model->DeleteRejoinGame($player); // Supprimer la Table Rejoin 

    if($game->get_player_fk() == $player->get_playerid() ) {
        $game_model->DeleteGame($player); // Supprimer la Game 
    } 

    $player_model->UpdatePlayerHeroGameNull($player); // Mettre le Hero Game en Null 
    $hero_model->DeleteHeroGame($player); // Supprimer le Hero Game 

    $player_model->DeletePlayer($player); // Supprimer le Player

}