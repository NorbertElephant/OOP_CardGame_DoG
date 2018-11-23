<?php 

class GameModel extends CoreModel {
    CONST TABLE = 'GAME'; // Servira quand je refactoriserai en FULL-MVC
    CONST BOARD = '1';
    /**
     * --------------------------------------------------
     * MAGIC METHODS
     * --------------------------------------------------
    **/
    /**
     * __construct - Class constructor
     * @param   PDO     $instance
     * @return  
    **/
    public function __construct( $instance ) {
        try {
            parent::__construct( $instance );
        } catch( PDOException $e ) {
            throw new CoreException( $e->getMessage() );
        } 
    }

/***************************************************************************************************
  *                                       GETTERS
  ****************************************************************************************************/
    /**
     * Get $_db
     *
     * @return  string
     */ 
    public function get_db(){ return $this->_db; }

/***************************************************************************************************
  *                                       CREATE  
****************************************************************************************************/
  /**
   * CreateGame .................... Création d'une Game 
   *
   * @param player $player
   * @return void
   */
  public function CreateGame ( player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `GAME` (`GAM_earlygame`, `GAM_board_model_fk`,`GAM_player_fk`) VALUES (NOW(), 1, :id) 
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_playerid())
                ) {
                if($this->_requete->execute()) {
                    $idgame = $this->_db->lastInsertId(); 
                    $game = $this->ReadOne($idgame); 
                    $this->RejoinGame($player, $game);
                    
                    return $game;
                } 
            } 
        }return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  } 
   /**
   * RejoinGame .................... Rejoindre une game 
   *
   * @param Player $player
   * @param Game $game 
   * @return void
   */
  public function RejoinGame ( player $player, Game $game) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `REJOIN` (`REJ_game_fk`, `REJ_player_fk`) VALUES (:idgame, :idplayer)
                                            ')) !== false )  {
            if($this->_requete->bindValue('idgame', $game->get_id())
             &&  $this->_requete->bindValue('idplayer', $player->get_playerid())
                ) {
                if($this->_requete->execute()) {

                     $game= $this->ReadOne($game->get_id()); 
                    return $game;

                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  } 
 
/***************************************************************************************************
  *                                       READ 
****************************************************************************************************/
  /**
   * Read All ______ Lire tout les players Via le panel Admin
   *
   * @return array  $Card............. Tableau d'objet Cartes 
   */
  public function ReadAll() {
    try {
        if(($this->_requete = $this->_db->prepare('SELECT `GAME`.*
                                                   FROM `GAME`
                                                   JOIN `PLAYER` ON `GAME`.`GAM_player_fk`=`PLAYER`.`PLA_id`

                                                ')) !==false) {
            $games = array();
            if($this->_requete->execute()) {
                while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
        
                $games[] =new Game($data);
                  }
            } return $cards;
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }
   
  /**
   * ReadOne ........... Ressortir un seul Objet GAME  par rapport a son ID 
   *
   * @param int $id
   * @return Object Game 
   */
  public function ReadOne( $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `GAME`.*
                                                     FROM `GAME`
                                                     LEFT JOIN `REJOIN` ON `REJOIN`.`REJ_game_fk` = `GAME`.`GAM_id`
                                                     LEFT JOIN `PLAYER` ON `REJOIN`.`REJ_player_fk`=`PLAYER`.`PLA_id`
                                                     WHERE `GAME`.`GAM_id`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {                   
                        return  new Game($data); 
                    } 
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }


   /**
   * ReadOne ........... Ressortir un seul Objet GAME  par rapport a son ID 
   *
   * @param Player $player
   * @return Object Game 
   */
  public function ReadOneByPlayer(Player $player) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `GAME`.*
                                                   FROM `GAME`
                                                   JOIN `PLAYER` ON `GAME`.`GAM_player_fk`=`PLAYER`.`PLA_id`
                                                   JOIN `REJOIN` ON `REJOIN`.`REJ_game_fk` = `GAME`.`GAM_id`
                                                   WHERE `GAME`.`GAM_player_fk`=:id
                                                   OR `REJ_player_fk`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $player->get_playerid())){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {                   
                        return  new Game($data); 
                    } 
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }

  /**
   * ReadPlayerInGame ........... Ressortir le nombre de player dans la game 
   *
   * @param int $id
   * @return array $data 
   */
  public function IncompleteGame() {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `REJOIN`.`REJ_game_fk`, COUNT(`REJ_game_fk`) AS `nb_player`
                                                     FROM `REJOIN`
                                                     JOIN `GAME` ON `REJOIN`.`REJ_game_fk` = `GAME`.`GAM_id`
                                                     JOIN `PLAYER` ON `REJOIN`.`REJ_player_fk`=`PLAYER`.`PLA_id`
                                                     GROUP BY `REJ_game_fk`
                                            ')) !== false )  {
                if($this->_requete->execute()) {
                    while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
                        foreach ($data as $key => $value) {
                            if($key == 'nb_player' && $value < 2) {
                                $game = $this->ReadOne($data['REJ_game_fk']);
                                return $data;
                            }
                        } return false;          
                }
            }
        } 
  }catch (PDOException $e) {
    throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
}
/**
   * GamePlayed ........... Ressortir le nombre de player dans la game 
   *
   * @param Game $game
   * @return array $datas 
   */
  public function FindPlayerGame(Game $game, Player $player) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `REJOIN`.`REJ_player_fk`
                                                     FROM `REJOIN`
                                                     JOIN `GAME` ON `REJOIN`.`REJ_game_fk` = `GAME`.`GAM_id`
                                                     JOIN `PLAYER` ON `REJOIN`.`REJ_player_fk`=`PLAYER`.`PLA_id`
                                                     WHERE `REJ_game_fk`=:id
                                                     AND `REJ_player_fk`!=:idplayer
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $game->get_id() )
                 && $this->_requete->bindValue('idplayer', $player->get_playerid() 
                 ) ){
                if($this->_requete->execute()) {
                    if( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
                            return $data['REJ_player_fk'];
                        }
                    } return false;
                }
          } 
  }catch (PDOException $e) {
    throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
}


/***************************************************************************************************
  *                                       UPDATE 
****************************************************************************************************/
 
 /**
  * UpdateGame 
  *
  * @param game $game
  * @return void
  */
public function UpdateEndOfGame (GAME $game) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `GAME`  
                                                     SET     `GAM_endgame` =NOW()
                                                     WHERE   `GAM_id`=:id
                        
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $game->get_id())
              ) {
                if($this->_requete->execute()) {
                return true;
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
        }
    }
    
  
  
/***************************************************************************************************
  *                                       DELETE 
****************************************************************************************************/
  /**
   * DeleteGame.............. Suppression d'une Game 
   *
   * @param PLAYER $player.......... Objet Player
   * @return void
   */
  public function DeleteGame(PLAYER $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `GAME`
                                                    WHERE `GAM_player_fk` =:id
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('id', $player->get_playerid()) ) {
                if($this->_requete->execute()) {
                    return true;
                } 
            }
        } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
        }   
    }


    
   /**
   * DeleteRejoinGame.............. Suppression la Table Rejoin 
   *
   * @param PLAYER $player.......... Objet Player

   * @return void
   */
    public function DeleteRejoinGame(Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `REJOIN`
                                                     WHERE `REJ_game_fk` =:game
                                                     AND `REJ_player_fk` =:player
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('player', $player->get_playerid()) 
             && $this->_requete->bindValue('game', $player->get_game()->get_id()) 
            ) {
                if($this->_requete->execute()) {
                    return true;
                } 
            }
        } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
        }  
    }
}