<?php


class CardModel extends CoreModel {
  CONST TABLE = 'Card_Model'; // Servira quand je refactoriserai en FULL-MVC
  CONST TABLEGAME = 'Card_Game'; // Servira quand je refactoriserai en FULL-MVC
  CONST ETAT = FALSE;
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
  public function CreateCardModel (array $data) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `CARD_MODEL` (`CAR_name`,`CAR_description`,`CAR_background_card`,`CAR_picture`,`CAR_mana`, `CAR_pa`, `CAR_hp`, `CAR_position`, `CAR_quote`, `CAR_hero_model_fk`, `CAR_copy_max`) VALUES (:name, :description, :background, :picture, :mana, :pa, :hp, :position,  :quote, :hero_model_fk, :copy_max ) 
                                            ')) !== false )  {
            if($this->_requete->bindValue('name', $data['name'])
                && $this->_requete->bindValue('background', $data['background_card'])
                && $this->_requete->bindValue('picture', $data['picture'])
                && $this->_requete->bindValue('mana', $data['mana'])
                && $this->_requete->bindValue('pa', $data['pa'])
                && $this->_requete->bindValue('hp', $data['hp'])
                && $this->_requete->bindValue('position', $data['position'])
                && $this->_requete->bindValue('quote', $data['quote'])
                && $this->_requete->bindValue('hero_model_fk', $data['hero_model_fk'])
                && $this->_requete->bindValue('copy_max', $data['copy_max'])
                ) {
                if($this->_requete->execute()) {
                return 'La Carte Model a bien été crée';
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  } 
  /**
   *  CreateCardGame.... Création d'une copy de Carte pour une Game 
   *
   * @param Hero $hero... Objet Hero (vient de la table CardModel) pour la Copy
   * @return void
   */
  public function CreateCardGame (Player $player, Card $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `CARD_GAME` (`CAG_hp`,`CAG_pa`,`CAG_position`,`CAG_etat`, `CAG_card_player`,`CAG_card_model_fk`, `CAG_player_fk`) VALUES (:hp, :pa, :position, :etat, NOW(), :card_model_fk, :player_fk) 
                                            ')) !== false )  {
            if( $this->_requete->bindValue('hp', $card->get_hp()) &&
                $this->_requete->bindValue('pa', $card->get_pa()) && 
                $this->_requete->bindValue('position', $card->get_position()) &&
                $this->_requete->bindValue('etat', SELF::ETAT) &&
                $this->_requete->bindValue('card_model_fk', $card->get_id()) &&
                $this->_requete->bindValue('player_fk', $player->get_playerid() ) 
                ) {
                if($this->_requete->execute()) {
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
   * Read All ______ Lire tout les Cartes de la BDD 
   *
   * @return array  $Card............. Tableau d'objet Cartes 
   */
  public function ReadAll() {
    try {
        if(($this->_requete = $this->_db->prepare('SELECT `CARD_MODEL`.*, `TYPE_OF_CARD`.`TYP_card`, `TYPE_OF_CARD`.`TYP_name` AS `TYP_type`,`TYPE_OF_CARD`.`TYP_url_background_card`, `TYPE_OF_CARD`.`TYP_url_background_pion`   
                                                FROM `CARD_MODEL`
                                                JOIN `CORRESPOND` ON `CARD_MODEL`.`CAR_id` = `CORRESPOND`.`COR_card_model_fk`
                                                JOIN `TYPE_OF_CARD` ON `CORRESPOND`.`COR_type_of_card_fk` = `TYPE_OF_CARD`.`TYP_card` 

                                                ')) !==false) {
            $cards = array();
            if($this->_requete->execute()) {
                while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
        
                $cards[] =new Card($data);
                  }
            } return $cards;
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }
   /**
   * Read All ______ Lire tout les Cartes par Faction de la BDD 
   *
   * @return array  $Card............. Tableau d'objet de cartes
   */
  public function ReadAllByFaction() {
    try {
        if(($this->_requete = $this->_db->prepare('SELECT `CARD_MODEL`.*, `TYPE_OF_CARD`.`TYP_card`, `TYPE_OF_CARD`.`TYP_name` AS `TYP_type`,`TYPE_OF_CARD`.`TYP_url_background_card`, `TYPE_OF_CARD`.`TYP_url_background_pion`   
                                                   FROM `CARD_MODEL`
                                                   JOIN `CORRESPOND` ON `CARD_MODEL`.`CAR_id` = `CORRESPOND`.`COR_card_model_fk`
                                                   JOIN `TYPE_OF_CARD` ON `CORRESPOND`.`COR_type_of_card_fk` = `TYPE_OF_CARD`.`TYP_card` 
                                                   WHERE `CAR_hero_Model_fk` = :id
                                      ')) !== false )  {
            if($this->_requete->bindValue('id', $id)){
               $cards = array();
               if($this->_requete->execute()) {
                  while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
        
                    $cards[] =new Card($data);
                  }
            
            } return $cards;
          }
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }

   /**
   * Read All By Player ______ Lire tout les Cartes Du player de la BDD 
   *
   * @return array  $Card............. Tableau d'objet de cartes
   */
  public function ReadAllByPlayer() {
    try {
        if(($this->_requete = $this->_db->prepare('SELECT `CARD_GAME`.*, ,`CARD_MODEL`.`CAR_mana`, `CARD_MODEL`.`CAR_name`, `CARD_MODEL`.`CAR_description`,`CARD_MODEL`.`CAR_background_picutre`, `CARD_MODEL`.`CAR_picture`, `CARD_MODEL`.`CAR_quote` , `TYPE_OF_CARD`.`TYP_card`, `TYPE_OF_CARD`.`TYP_name` AS `TYP_type`,`TYPE_OF_CARD`.`TYP_url_background_card`, `TYPE_OF_CARD`.`TYP_url_background_pion`   
                                                   FROM `CARD_GAME`
                                                   JOIN `CARD_MODEL` ON `CARD_GAME`.`CAG_card_model_fk`= `CARD_MODEL`.`CAR_id`
                                                  JOIN `CORRESPOND` ON `CARD_MODEL`.`CAR_id` = `CORRESPOND`.`COR_card_model_fk`
                                                   JOIN `TYPE_OF_CARD` ON `CORRESPOND`.`COR_type_of_card_fk` = `TYPE_OF_CARD`.`TYP_card` 
                                                   WHERE `CAG_player_fk` = :id
                                      ')) !== false )  {
            if($this->_requete->bindValue('id', $id)){
               $cards = array();
               if($this->_requete->execute()) {
                  while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
        
                    $cards[] =new Card($data);
                  }
            
            } return $cards;
          }
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }


  /**
   * ReadOneCardModel ........... Ressortir un seul Objet CARD  par rapport a son ID 
   *
   * @param int $id
   * @return Object CARD 
   */
  public function ReadOneCardModel(int $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `CARD_MODEL`.* , `TYPE_OF_CARD`.`TYP_card`, `TYPE_OF_CARD`.`TYP_name` AS `TYP_type`,`TYPE_OF_CARD`.`TYP_url_background_card`, `TYPE_OF_CARD`.`TYP_url_background_pion`   
                                            FROM `CARD_MODEL`
                                            JOIN `CORRESPOND` ON `CARD_MODEL`.`CAR_id` = `CORRESPOND`.`COR_card_model_fk`
                                            JOIN `TYPE_OF_CARD` ON `CORRESPOND`.`COR_type_of_card_fk` = `TYPE_OF_CARD`.`TYP_card` 
                                            WHERE `CAR_id`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        return  new Card($data);
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
   * @return Object Card
   */
  public function ReadAllCardGame(Player $player) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `CARD_GAME`.* ,`CARD_MODEL`.`CAR_mana`, `CARD_MODEL`.`CAR_name`, `CARD_MODEL`.`CAR_description`,`CARD_MODEL`.`CAR_background_card`, `CARD_MODEL`.`CAR_picture`, `CARD_MODEL`.`CAR_quote` , `CARD_MODEL`.`CAR_hero_model_fk`, `TYPE_OF_CARD`.`TYP_card`, `TYPE_OF_CARD`.`TYP_name` AS `TYP_type`,`TYPE_OF_CARD`.`TYP_url_background_card`, `TYPE_OF_CARD`.`TYP_url_background_pion`   
                                            FROM `CARD_GAME`
                                            JOIN `CARD_MODEL` ON `CARD_GAME`.`CAG_card_model_fk`= `CARD_MODEL`.`CAR_id`
                                            JOIN `CORRESPOND` ON `CARD_MODEL`.`CAR_id` = `CORRESPOND`.`COR_card_model_fk`
                                            JOIN `TYPE_OF_CARD` ON `CORRESPOND`.`COR_type_of_card_fk` = `TYPE_OF_CARD`.`TYP_card` 
                                            WHERE `CAG_player_fk`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $player->get_playerid())){
                  $cards = array();
                if($this->_requete->execute()) {
                    while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){

                        $cards[] =new Card($data);
                      } return $cards;
                
                } 
            } return false;
    }
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }

  /**
   * ReadOneDeckByDeck ........... Ressortir un seul Objet DECK  par rapport a son ID 
   *
   * @param int $id
   * @return Object CARD 
   */
  public function ReadCardsByDeck(int $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `CARD_MODEL`.*
                                                     FROM `CONSIST_OF`
                                                     JOIN DECK_MODEL ON `CON_deck_model_fk` = `DEC_id`
                                                     JOIN CARD_MODEL ON `CON_card_model_fk` = `CAR_id`
                                                     WHERE `DEC_id` = :id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                $cards = array();
                if($this->_requete->execute()) {
                   while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
         
                     $cards[] =new Card($data);
                   }return $cards;
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
   * @return Object Hero  /// JE dois récup tout le!
   */
  public function ReadOneCardGame( $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `CARD_GAME`.* ,`CARD_MODEL`.`CAR_mana`, `CARD_MODEL`.`CAR_name`, `CARD_MODEL`.`CAR_description`,`CARD_MODEL`.`CAR_background_card`, `CARD_MODEL`.`CAR_picture`, `CARD_MODEL`.`CAR_quote` , `CARD_MODEL`.`CAR_hero_model_fk`, `TYPE_OF_CARD`.`TYP_card`, `TYPE_OF_CARD`.`TYP_name` AS `TYP_type`,`TYPE_OF_CARD`.`TYP_url_background_card`, `TYPE_OF_CARD`.`TYP_url_background_pion`   
                                            FROM `CARD_GAME`
                                            JOIN `CARD_MODEL` ON `CARD_GAME`.`CAG_card_model_fk`= `CARD_MODEL`.`CAR_id`
                                            JOIN `CORRESPOND` ON `CARD_MODEL`.`CAR_id` = `CORRESPOND`.`COR_card_model_fk`
                                            JOIN `TYPE_OF_CARD` ON `CORRESPOND`.`COR_type_of_card_fk` = `TYPE_OF_CARD`.`TYP_card` 
                                            WHERE `CAG_id`=:id
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        
                        return  new Card($data);
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
 * UpdateCardModel........... Espace Admin Seulement  
 *
 * @param integer $id................ Id de la Carte Model a modifié
 * @param array $data................ Tableau des POST du formulaire
 * @return void
 */
public function UpdateCardModel (int $id, array $data) {
  try{
      if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_MODEL`  
                                                  SET     `CAR_name` =:name,
                                                          `CAR_description`=: description,
                                                          `CAR_background_card`=:bacground,
                                                          `CAR_picture` =:picture,
                                                          `CAR_mana`=:mana,
                                                          `CAR_pa`=:pa,
                                                          `CAR_hp`=:hp
                                                          `CAR_position`=:position
                                                          `CAR_quote`=:quote
                                                          `CAR_hero_model_fk`=:hero_model_fk
                                                          `CAR_copy_max`=:copy_max
                                                    WHERE `CAR_id`=:id
                      
                                          ')) !== false )  {
          if($this->_requete->bindValue('id', $id)
             && $this->_requete->bindValue('name', $data['name'])
             && $this->_requete->bindValue('description', $data['description'])
             && $this->_requete->bindValue('background', $data['background_card'])
             && $this->_requete->bindValue('picture', $data['picture'])
             && $this->_requete->bindValue('mana', $data['mana'])
             && $this->_requete->bindValue('pa', $data['pa'])
             && $this->_requete->bindValue('hp', $data['hp'])
             && $this->_requete->bindValue('position', $data['position'])
             && $this->_requete->bindValue('quote', $data['quote'])
             && $this->_requete->bindValue('hero_model_fk', $data['hero_model_fk'])
             && $this->_requete->bindValue('copy_max', $data['copy_max'])) {
              if($this->_requete->execute()) {
              return 'La Carte Model a bien été modifé';
              } 
          } return false;
  }
  } catch (PDOException $e) {
      throw new Exception($e->getMessage(),99,$e);
      }
  }
  /**
   * UpdateCardGame............... Modification de la carte qui est EN JEU 
   *
   * @param integer $id................ Id de la Carte GAME a modifié
   * @param array $data................ Tableau des POST du formulaire
   * @return Bool
   */
  public function UpdateCardGame (int $id, CARD $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_GAME`  
                                                     SET    `CAG_pv`=:hp,
                                                            `CAG_pa` =:pa,
                                                            `CAG_position` =:position,
                                                            `CAG_etat`=:etat, 
                                                            `CAG_card_model_fk`=:card_model_fk, 
                                                            `CAG_player_fk`=:player_fk
                                                      WHERE `CAG_player`=:id
                        
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $id)&& 
               $this->_requete->bindValue('hp', $card->get_hp()) &&
               $this->_requete->bindValue('pa', $card->get_pa()) && 
               $this->_requete->bindValue('position', $card->get_position()) &&
               $this->_requete->bindValue('etat', $card->get_etat()) &&
               $this->_requete->bindValue('card_model_fk', $card->get_card_model_fk()) &&
               $this->_requete->bindValue('player_fk', $card->$card->get_player_fk()())
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
   * UpdateDrawCard
   *
   * @param Card $card
   * @return void
   */
  public function UpdateDrawCard(Card $card) {
        try{
            if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_GAME`  
                                                         SET    `CAG_position` = 1
                                                          WHERE `CAG_id`=:id
                                                ')) !== false )  {
                if($this->_requete->bindValue('id', $card->get_id())
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
   * UpdateDeckcard
   *
   * @param Card $card
   * @return void
   */
  public function UpdateDeckcard(Card $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_GAME`  
                                                     SET    `CAG_position` = 0
                                                      WHERE `CAG_id`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $card->get_id())
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
   * UpdateBoardcard
   *
   * @param Card $card
   * @return void
   */
  public function UpdateBoardcard(Card $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_GAME`  
                                                     SET    `CAG_position` = 2
                                                      WHERE `CAG_id`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $card->get_id())
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
   * UpdateCemeterriescard
   *
   * @param Card $card
   * @return void
   */
  public function UpdateCemeterriesCard(Card $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_GAME`  
                                                     SET    `CAG_position` = 3
                                                      WHERE `CAG_id`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $card->get_id())
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
   * UpdateCemeterriescard
   *
   * @param Card $card
   * @return void
   */
  public function UpdateHpCard(Card $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_GAME`  
                                                     SET    `CAG_hp` = :hp
                                                      WHERE `CAG_id`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $card->get_id())
               && $this->_requete->bindValue('hp', $card->get_hp())
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
   * UpdateEtatOn
   *
   * @param Card $card
   * @return void
   */
  public function UpdateEtatOn(Card $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_GAME`  
                                                     SET    `CAG_etat` = 1
                                                     WHERE `CAG_id`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $card->get_id())
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
   * UpdateEtatOff
   *
   * @param Card $card
   * @return void
   */
  public function UpdateEtatOff(Card $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' UPDATE `CARD_GAME`  
                                                     SET    `CAG_etat` = 0
                                                     WHERE `CAG_id`=:id
                                            ')) !== false )  {
            if($this->_requete->bindValue('id', $card->get_id())
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
  public function DeleteCardModel(int $id) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `CARD_MODEL`
                                                    WHERE `CARD_id` =:id
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('id', $id) ) {
                if($this->_requete->execute()) {
                    return 'La Carte Model a bien été supprimé';
                } 
            }
        } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
    }
}

 /**
   * DeleteCardGame.............. Suppression de la carte en GAME via la fin de GAME
   * @param CARD $card.......... Objet Card a supprimé
   * @return void
   */
  public function DeleteCardGame(CARD $card) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `CARD_GAME`
                                                    WHERE `CAG_id` =:id
                                                    AND `CAG_player_fk` =:player
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('id', $card->get_id())
               && $this->_requete->bindValue('player', $card->get_player_fk()
            )) {
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


