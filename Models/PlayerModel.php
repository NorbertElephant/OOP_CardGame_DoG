<?php

// Le player est éphémère  !!  création débur de Game et Suppression Fin de Game ! =) 

class PlayerModel extends CoreModel {
  CONST TABLE = 'PLAYER'; // Servira quand je refactoriserai en FULL-MVC
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
   * CreatePlayer.............. Création d'un Player pour aller dans le Jeu 
   *
   * @param User $user....... Objet User pour créer l'Objet Player
   * @param Hero $hero....... Objet Hero pour créer l'Objet Player
   * @param Deck $deck....... Objet User pour créer l'Objet Player
   * @return void
   */
  public function CreatePlayer (User $user, Hero $heroGame, Deck $deck ) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `PLAYER` (`PLA_NumGamePlayed_player`,`PLA_NumGameWin_player`,`PLA_user_fk`, `PLA_deck_model_fk`, `PLA_hero_game_fk`) VALUES (:NumGamePlayed, :NumGameWin, :iduser, :iddeck, :idhero ) 
                                            ')) !== false )  {
            if($this->_requete->bindValue('NumGamePlayed', $user->get_NumGamePlayed_player())
                && $this->_requete->bindValue('NumGameWin', $user->get_NumGameWin_player())
                && $this->_requete->bindValue('iduser', $user->get_id())
                && $this->_requete->bindValue('iddeck', $deck->get_id())
                && $this->_requete->bindValue('idhero', $heroGame->get_id())
                ) {
                if( $this->_requete->execute()) {
                    return $this->_db->LastInsertId();
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
        if(($this->_requete = $this->_db->prepare('SELECT `PLAYER`.*
                                                FROM `PLAYER`

                                                ')) !==false) {
            $players = array();
            if($this->_requete->execute()) {
                while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
        
                $players[] =new Player($data);
                  }
            } return $cards;
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }
   
  /**
   * ReadOneByUser ........... Ressortir un seul Objet PLAYER  par rapport a l'ID USER 
   *
   * @param int $id
   * @return Object CARD 
   */
  public function ReadOneByUser( $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `PLAYER`.`PLA_id` AS `PLA_playerid`, `USER`.*, `RAN_name` AS `RAN_rank`, `RAN_power`AS `USE_power`
                                            FROM `PLAYER`
                                            JOIN `USER` ON `USER`.`USE_id`=`PLAYER`.`PLA_user_fk`
                                            JOIN `RANK` ON `USE_rank_fk` = `RAN_id`
                                            WHERE `PLA_user_fk`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        return  new Player($data); 
                    } 
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }
  /**
   * ReadOne ........... Ressortir un seul Objet PLAYER  par rapport a son ID 
   *
   * @param int $id
   * @return Object CARD 
   */
  public function ReadOne( $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `PLAYER`.`PLA_id` AS `PLA_playerid`, `USER`.*, `RAN_name` AS `RAN_rank`, `RAN_power`AS `USE_power`
                                            FROM `PLAYER`
                                            JOIN `USER` ON `USER`.`USE_id`=`PLAYER`.`PLA_user_fk`
                                            JOIN `RANK` ON `USE_rank_fk` = `RAN_id`
                                            WHERE `PLA_id`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        return  new Player($data); 
                    } 
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }




/***************************************************************************************************
  *                                       UPDATE 
****************************************************************************************************/
 
 /**
 * UpdatePlayer........... Fin de Game Seulement
 *
 * @param Player $player................ Objet Player
 * @return void
 */
public function UpdatePlayer (Player $player) {
  try{
      if( ($this->_requete = $this->_db->prepare(' UPDATE `PLAYER`  
                                                  SET     `PLA_NumGamePlayed_player` =:played,
                                                          `PLA_NumGameWin_player`=:win,
                                                  WHERE `PLA_id`=:id
                      
                                          ')) !== false )  {
          if($this->_requete->bindValue('id', $player->get_playerid())
             && $this->_requete->bindValue('played', $player->get_NumGamePlayed_player())
             && $this->_requete->bindValue('win', $player->get_NumGameWin_player())
            ) {
              if($this->_requete->execute()) {
              return 'La Player a bien été modifé';
              } 
          } return false;
  }
  } catch (PDOException $e) {
      throw new Exception($e->getMessage(),99,$e);
      }
  }
  
   /**
 * UpdatePlayerHeroGameNull ........... Fin de Game Seulement
 *
 * @param Player $player................ Objet Player
 * @return void
 */
public function UpdatePlayerHeroGameNull (Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `PLAYER`  
                                                    SET     `PLA_hero_game_fk` = NULL
                                                    WHERE `PLA_id`=:id
                        
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_playerid())
              ) {
                if($this->_requete->execute()) {
                return 'La Player a bien été modifé';
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
   * DeleteHeroModel.............. Suppression du PLAYER a la Fin de la GAME !
   *
   * @param PLAYER $player.......... Objet Player
   * @return void
   */
  public function DeletePlayer(PLAYER $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `PLAYER`
                                                    WHERE `PLA_id` =:id
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('id', $player->get_playerid()) ) {
                if($this->_requete->execute()) {
                    return 'L\'user a bien été supprimé';
                } 
            }
        } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
    }
}


}