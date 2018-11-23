<?php 

class HeroModel extends CoreModel {
/***************************************************************************************************
 *                                       CONSTANT
****************************************************************************************************/
CONST TABLE = 'Hero_Model'; // Servira quand je refactoriserai en FULL-MVC
CONST TABLEGAME = 'Hero_Game'; // Servira quand je refactoriserai en FULL-MVC
Const HP = 20;
Const MANA = 1;
Const MANA_MAX = 1;
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
   * CreateHeroModel Via le panel d'administration du Jeu 
   *
   * @param array $data....... Tableau des POST
   * @return void
   */
  public function CreateHeroModel (array $data) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `HERO_MODEL` (`HER_name`,`HER_picture`,`HER_hp`,`HER_mana`,`HER_mana_max`, `HER_faction_fk`) VALUES (:name, :picture, :hp, :mana, :mana_max, :faction ) 
                                            ')) !== false )  {
            if($this->_requete->bindValue('name', $data['name'])&& 
                $this->_requete->bindValue('picture', $data['picture']) &&
                $this->_requete->bindValue('hp', HP) && 
                $this->_requete->bindValue('mana', MANA) &&
                $this->_requete->bindValue('mana_max', MANA_MAX) &&
                $this->_requete->bindValue('faction', $data['faction'])
                ) {
                if($this->_requete->execute()) {
                return 'L\'héro Model a bien été crée';
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  } 
  /**
   *  CreateHeroGame.... Création d'une copy de Hero pour une Game 
   *
   * @param Hero $hero... Objet Hero (vient de la table HeroModel) pour la Copy
   * @return void
   */
  public function CreateHeroGame (Hero $hero) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `HERO_GAME` (`HEG_hp`,`HEG_mana`,`HEG_mana_max`,`HEG_turn`, `HEG_swap`,`HEG_copy_hero_player`, `HEG_hero_model_fk`) VALUES ( :hp, :mana, :mana_max, :turn, :swap, NOW(), :idhero ) 
                                            ')) !== false )  {
            if( $this->_requete->bindValue('hp', $hero->get_hp()) &&
                $this->_requete->bindValue('mana', $hero->get_mana()) && 
                $this->_requete->bindValue('mana_max', $hero->get_mana_max()) &&
                $this->_requete->bindValue('turn', $hero->get_turn()) &&
                $this->_requete->bindValue('swap', $hero->get_swap()) &&
                $this->_requete->bindValue('idhero', $hero->get_id()) 
                ) {
                if($this->_requete->execute()) {
                return $this->_db->lastInsertId();
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  } 
  /**
   * CreateFaction........... Création d'un nouvelle Faction via le Panel D'administration 
   *
   * @param String $name........... Le Nom de la nouvelle faction 
   * @return void
   */
  public function CreateFaction (String $name) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `FACTION` (`FAC_name`) VALUES (:name ) 
                                            ')) !== false )  {
            if($this->_requete->bindValue('name', $name)
                ) {
                if($this->_requete->execute()) {
                return 'La Faction a bien été crée';
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
   * Read All ______ Lire tout les HERO avec FACTION de la BDD 
   *
   * @return array  $HEROS............. Tableau d'objet Hero 
   */
  public function ReadAll() {
    try {
        if(($this->_requete = $this->_db->prepare('SELECT `HERO_MODEL`.*, `FACTION`.`FAC_id` AS `FAC_factionID`, `FACTION`.`FAC_name` AS `FAC_factionName`
                                                FROM `HERO_MODEL`
                                                JOIN `FACTION` ON `FAC_id` = `HER_faction_fk`
                                                ')) !==false) {
                $heros = array();
                if($this->_requete->execute()) {
                    while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
            
                    $heros[] =new Hero($data);
                    }
            }   return $heros;
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }

  /**
   * ReadOneHeroModel ........... Ressortir un seul Objet HERO avec sa FACTION par rapport a son ID 
   *
   * @param int $id
   * @return Object Hero 
   */
  public function ReadOneHeroModel($id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `HERO_MODEL`.*,  `FACTION`.`FAC_id` AS `FAC_factionID`, `FACTION`.`FAC_name` AS `FAC_factionName`
                                            FROM `HERO_MODEL`
                                            JOIN `FACTION` ON `FAC_id` = `HER_id`
                                            WHERE `HER_id`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        return  new Hero($data);
                    } 
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }

  /**
   * ReadOneHeroModel ........... Ressortir un seul Objet HERO avec sa FACTION par rapport a son ID 
   *
   * @param int $id
   * @return Object Hero 
   */
  public function ReadOneHeroGame(int $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `HERO_GAME`.*, `HER_picture`, `HER_name`,  `HER_pion`, `HEG_player` AS `HEG_id`,  `FACTION`.`FAC_id` AS `FAC_factionID`, `FACTION`.`FAC_name` AS `FAC_factionName`
                                                     FROM `HERO_GAME`
                                                     JOIN `HERO_MODEL` ON `HER_id` = `HEG_hero_model_fk`
                                                     JOIN `FACTION` ON `FAC_id` = `HER_id`
                                                     WHERE `HEG_player`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        return  new Hero($data);
                    } 
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }
  /**
   * ReadOneHeroModel ........... Ressortir un seul Objet HERO avec sa FACTION par rapport a son ID 
   *
   * @param Player $player
   * @return Object Hero 
   */
  public function ReadOneHeroGameByPlayer(Player $player) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `HERO_GAME`.*, `HER_picture`, `HER_name`,  `HER_pion`, `HEG_player` AS `HEG_id`,  `FACTION`.`FAC_id` AS `FAC_factionID`, `FACTION`.`FAC_name` AS `FAC_factionName`
                                                     FROM `HERO_GAME`
                                                     JOIN `PLAYER` ON `PLA_hero_game_fk` = `HEG_player`
                                                     JOIN `HERO_MODEL` ON `HER_id` = `HEG_hero_model_fk`
                                                     JOIN `FACTION` ON `FACTION`.`FAC_id` = `HERO_MODEL`.`HER_id`
                                                     WHERE `PLA_id`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $player->get_playerid())){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        return  new Hero($data);
                    } 
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }

  /**
   * ReadAllFaction ______ Lire les FACTIONS de la BDD 
   *
   * @return array  $Factions............. Tableau de données de la BDD
   */
  public function ReadAllFaction() {
    try {
        if(($this->_requete = $this->_db->prepare('SELECT `HERO_MODEL`.*, FACTION.*
                                                FROM `HERO_MODEL`
                                                JOIN `FACTION` ON `FAC_id` = `HER_id`
                                                ')) !==false) {
            if($this->_requete->bindValue('power', $power)){
                $factions = array();
                if($this->_requete->execute()) {
                    while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
            
                    $factions[] = $data;
                    }
                }

            }   return $factions;
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }
   /**
   * ReadOneHeroFaction ........... Ressortir un seul Objet HERO avec sa FACTION par rapport a son ID 
   *
   * @param int $id.............. ID de la Faction
   * @return array $data ........... Tableau de la BDD FACTION
   */
  public function ReadOneFaction(int $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `FACTION`
                                            FROM `FACTION`
                                            WHERE `FAC_id`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        return $data;
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
 * UpdateHeroModel 
 *
 * @param integer $id................ Id de l'Hero Model a modifié
 * @param array $data................ Tableau des POST du formulaire
 * @return void
 */
public function UpdateHeroModel (int $id, array $data) {
  try{
      if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_MODEL`  
                                                  SET     `HER_name`=:name ,
                                                          `HER_picture`=:picture,
                                                          `HER_hp`=:hp ,
                                                          `HER_mana`=:mana,
                                                          `HER_mana_max`=:mana_max, 
                                                          `HER_faction_fk` =:faction 
                                                    WHERE `HER_id`=:id
                      
                                          ')) !== false )  {
          if($this->_requete->bindValue('id', $id)&& 
              $this->_requete->bindValue('name', $data['name'])&& 
              $this->_requete->bindValue('picture', $data['picture']) &&
              $this->_requete->bindValue('hp', $data['hp']) && 
              $this->_requete->bindValue('mana', $data['mana']) &&
              $this->_requete->bindValue('mana_max', $data['mana_max']) &&
              $this->_requete->bindValue('faction', $data['faction'])
              ) {
              if($this->_requete->execute()) {
              return 'L\'Héro Model a bien été modifé';
              } 
          } return false;
  }
  } catch (PDOException $e) {
      throw new Exception($e->getMessage(),99,$e);
      }
  }
  /**
   * UpdateHeroGame............... Modification de l'héro qui est en jeu 
   *
   * @param integer $id................ Id de l'Hero GAME a modifié
   * @param HERO  $hero................ Objet Hero
   * @return Bool
   */
  public function UpdateHeroGame (int $id, HERO $hero) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                     SET    `HEG_hp`=:hp ,
                                                            `HEG_mana`=:mana,
                                                            `HEG_mana_max`=:mana_max, 
                                                            `HEG_turn`=:turn, 
                                                            `HEG_swap`=:swap, 
                                                      WHERE `HEG_player`=:id
                        
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $id)&& 
                $this->_requete->bindValue('hp', $hero->get_hp()) && 
                $this->_requete->bindValue('mana', $hero->get_mana()) &&
                $this->_requete->bindValue('mana_max', $hero->get_mana_max()) &&
                $this->_requete->bindValue('turn', $hero->get_turn()) &&
                $this->_requete->bindValue('swap', $hero->get_swap()) 
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
  /**
   * UpdateFaction............... Modification de la Faction avec le panel Admin
   *
   * @param integer $id................ Id de la FACTION a modifié
   * @param array $data................ Tableau des POST du formulaire
   * @return Bool
   */
  public function UpdateFaction ( $id, array $data) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `FACTION`  
                                                     SET    `FAC_name`=:name 
                                                      WHERE `FAC_id`=:id
                        
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $id)&& 
                $this->_requete->bindValue('hp', $data['name']) 
                ) {
                if($this->_requete->execute()) {
                  return 'La Faction a bien été modifé';;
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
        }
  }
/**
   * UpdateSwap............... Modification de l'héro qui est en jeu 
   *
   * @param integer $id................ Id de l'Hero GAME a modifié
   * @param HERO  $hero................ Objet Hero
   * @return Bool
   */
  public function UpdateSwap ( Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                     SET    `HEG_swap`=1
                                                      WHERE `HEG_player`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id())
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
/**
   * UpdateNumTurn............... Augmenter le compteur des Tours
   *
   * @param Player  $player................ Objet Player
   * @return Bool
   */
  public function UpdateNumTurn ( Player $player) {
      $turn = $player->get_heroGame()->get_num_turn() + 1; 
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                      SET    `HEG_num_turn`=:turn 
                                                      WHERE `HEG_player`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id())
             && $this->_requete->bindValue('turn', $turn)
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
/**
   * UpdateTurnOn............... Modification du Tour l'héro qui est en jeu 
   * 
   * @param Player  $hero................ Objet Player
   * @return Bool
   */
  public function UpdateTurnOn ( Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                      SET    `HEG_turn`= 1
                                                      WHERE `HEG_player`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id())
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
   /**
   * UpdateTurnOff............... Modification du Tour l'héro qui est en jeu 
   * 
   * @param Player  $Player................ Objet Player
   * @return Bool
   */
  public function UpdateTurnOff ( Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                     SET    `HEG_turn`= 0
                                                      WHERE `HEG_player`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id())
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
    /**
   * UpdateMana............... Modification du Mana l'héro qui est en jeu 
   * 
   * @param Player  $Player................ Objet Player
   * @return Bool
   */
  public function UpdateMana ( Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                     SET    `HEG_mana`=:mana
                                                      WHERE `HEG_player`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id())
             && $this->_requete->bindValue('mana', $player->get_heroGame()->get_mana())
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
/**
   * UpdateManaTurn............... Rénétialisation du Mana du Héro Chaque tour 
   * 
   * @param Player  $Player................ Objet Player
   * @return Bool
   */
  public function UpdateManaTurn ( Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                     SET    `HEG_mana`=:mana
                                                      WHERE `HEG_player`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id())
             && $this->_requete->bindValue('mana', $player->get_heroGame()->get_mana_max())
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
/**
   * UpdateManaMax............... Modification du Mana l'héro qui est en jeu 
   * 
   * @param player  $player................ Objet player
   * @return Bool
   */
  public function UpdateManaMax ( Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                     SET    `HEG_mana_max`=:mana_max
                                                      WHERE `HEG_player`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id())
             && $this->_requete->bindValue('mana_max', $player->get_heroGame()->get_mana_max())
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

/**
   * UpdateHpHeroGame............... Modification du Pv l'héro qui est en jeu 
   * 
   * @param Player  $player................ Objet Hero
   * @return Bool
   */
  public function UpdateHpHeroGame ( Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `HERO_GAME`  
                                                     SET    `HEG_hp`=:hp
                                                      WHERE `HEG_player`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id())
             && $this->_requete->bindValue('hp', $player->get_heroGame()->get_hp())
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
   * DeleteHeroModel.............. Suppression de l'héro model via le panel Admin
   *
   * @param integer $id.......... ID du Hero a supprimer
   * @return void
   */
  public function DeleteHeroModel(int $id) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `HERO_MODEL`
                                                    WHERE `HER_id` =:id
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('id', $id) ) {
                if($this->_requete->execute()) {
                    return 'L\'Héro Model a bien été supprimé';
                } 
            }
        } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
    }
}

 /**
   * DeleteHeroGame.............. Suppression de l'héro GAME via la fin de GAME
   * @param Player $player.......... Player pour aller chercher son Hero avec son id
   * @return void
   */
  public function DeleteHeroGame(Player $player) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `HERO_GAME`
                                                    WHERE `HEG_player` =:id
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('id', $player->get_heroGame()->get_id()) ) {
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
   * DeleteFaction.............. Suppression de la FACTION via le panel Admin
   * @param integer $id.......... ID du Hero a supprimer
   * @return void
   */
  public function DeleteFaction(int $id) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `FACTION`
                                                    WHERE `FAC_id` =:id
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('id', $id) ) {
                if($this->_requete->execute()) {
                  return 'La Faction a bien été supprimé';
                } 
            }
        } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
    }
  }



}