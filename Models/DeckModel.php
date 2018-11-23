<?php


class DeckModel extends CoreModel {
  CONST TABLE = 'Deck'; // Servira quand je refactoriserai en FULL-MVC

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
   * CreateDeckModel Via le panel d'administration du Jeu 
   *
   * @param array $data....... Tableau des POST
   * @return void
   */
  public function CreateDeckModel (array $data) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `DECK_MODEL` (`DEC_name`,`DEC_creation`,`DEC_user_fk`) VALUES (:name,NOW(), :user ) 
                                            ')) !== false )  {
            if($this->_requete->bindValue('name', $data['name'])
                && $this->_requete->bindValue('user', $data['user'])
                ) {
                if($this->_requete->execute()) {
                return 'La Deck Model a bien été crée';
                } 
            } return false;
    } 
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  } 
   /**
   * CreateDeck Via le panel d'administration du Jeu 
   * 
   * --> Je vais devoir faire un factory car c'est pour ajouter une carte et il faut boucler comment faire ?? 
   *
   * @param array $data....... Tableau des POST (ou Objet a voir comment je vais faire ça)
   * @return void
   */
  public function CreateDeck (array $data) {
    try{
        if( ($this->_requete = $this->_db->prepare(' INSERT INTO `CONSIST_OF` (`CON_card_model_fk`,`CON_deck_model_fk`,`CON_card_copy`) VALUES (:Idcard, :Iddeck, :copy ) 
                                            ')) !== false )  {
            if($this->_requete->bindValue('Idcard', $data['card'])
                && $this->_requete->bindValue('Iddeck', $data['deck'])
                && $this->_requete->bindValue('copy', $data['copy'])
                ) {
                if($this->_requete->execute()) {
                return 'La Deck Model a bien été crée';
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
   * Read All ______ Lire tout les Decks 
   *
   * @return array  $Card............. Tableau d'objet Cartes 
   */
  public function ReadAll() {
    try {
        if(($this->_requete = $this->_db->prepare('SELECT `DECK_MODEL`.*, `CON_card_model_fk`
                                                   FROM `DECK_MODEL`
                                                   JOIN CONSIST_OF ON `CON_deck_model_fk` = `DEC_id`
                                                   JOIN CARD_MODEL ON `CON_card_model_fk` = `CAR_id`

                                                ')) !==false) {
            $decks = array();
            if($this->_requete->execute()) {
                while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
        
                $decks[] =new Deck($data);
                  }
            } return $cards;
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }
   /**
   * ReadAllByPlayer ______ Lire tout les Cartes du Deck pour le player
   *
   * @return array  $Card............. Tableau d'objet de cartes
   */
  public function ReadAllByPlayer(User $user, $hero) {
    try {
        if(($this->_requete = $this->_db->prepare('SELECT `DECK_MODEL`.* , `CON_card_model_fk`
                                                   FROM `DECK_MODEL`
                                                   JOIN CONSIST_OF ON `CON_deck_model_fk` = `DEC_id`
                                                   JOIN CARD_MODEL ON `CON_card_model_fk` = `CAR_id`
                                                   WHERE `DEC_user_fk` = :id
                                                   AND `CAR_hero_model_fk`=:hero
                                                   GROUP BY `DEC_id`
                                      ')) !== false )  {
            if($this->_requete->bindValue('id', $user->get_id())
                && $this->_requete->bindValue('hero', $hero)
            ){
               $decks = array();
               if($this->_requete->execute()) {
                  while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
        
                    $decks[] =new Deck($data);
                  }
            
            } return $decks;
          }
        } return false ;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
  }


  /**
   * ReadOneDeck ........... Ressortir un seul Objet DECK  par rapport a son ID 
   *
   * @param int $id
   * @return Object CARD 
   */
  public function ReadOneDeck(int $id) {
    try {
        if( ($this->_requete = $this->_db->prepare(' SELECT `DECK_MODEL`.*
                                                     FROM `DECK_MODEL`
                                                     JOIN CONSIST_OF ON `CON_deck_model_fk` = `DEC_id`
                                                     JOIN CARD_MODEL ON `CON_card_model_fk` = `CAR_id`
                                                     WHERE `DEC_id` = :id
                                                     GROUP BY `DEC_id`
                                            ')) !== false )  {
              if($this->_requete->bindValue('id', $id)){
                if($this->_requete->execute()) {
                    if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                        return  new Deck($data);
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
 * UpdateDeck........... Espace Admin Seulement  
 *
 * @param integer $id................ Id de la Carte Model a modifié
 * @param array $data................ Tableau des POST du formulaire
 *  * --> Je vais devoir faire un factory car c'est pour ajouter une carte et il faut boucler comment faire ?? 
   *
 * @return void
 */
public function UpdateDeck (int $id, array $data) {
  try{
      if( ($this->_requete = $this->_db->prepare(' UPDATE `CONSIST_OF`  
                                                   SET    `CON_card_model_fk` =:idcard,
                                                          `CON_card_copy`=:copy
                                                    WHERE `CON_deck_model_fk`=:iddeck
                      
                                          ')) !== false )  {
          if($this->_requete->bindValue('iddeck', $id)
             && $this->_requete->bindValue('idcard', $data['card'])
             && $this->_requete->bindValue('copy', $data['copy']) ) {
              if($this->_requete->execute()) {
              return 'Le Deck a bien été modifé';
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
   * DeleteHeroModel.............. Suppression de Du Deck Model via le panel Admin
   * /!\ Supprimer les liens avec la TABLE CONSIST_OF ! ou pas a faire des Test 
   * @param integer $id.......... ID du Hero a supprimer
   * @return void
   */
  public function DeleteDeckModel(int $id) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `DECK_MODEL`
                                                    WHERE `DECK_id` =:id
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('id', $id) ) {
                if($this->_requete->execute()) {
                    return 'Le Deck Model a bien été supprimé';
                } 
            }
        } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
    }
}
/**
   * DeleteHeroModel.............. Suppression de Du Deck Model via le panel Admin
   * /!\ Supprimer les liens avec la TABLE CONSIST_OF ! ou pas a faire des Test 
   * @param integer $id.......... ID du Hero a supprimer
   * @return void
   */
  public function DeleteDeck(int $idcard, int $iddeck ) {
    try{
        if( ($this->_requete = $this->_db->prepare(' DELETE FROM `CONSIST_OF`
                                                    WHERE `CON_card_model_fk` =:idcard
                                                    AND  `CON_deck_model_fk` =:iddeck
                                                    ' )) !== false )  {
            if($this->_requete->bindValue('idcard', $idcard)
                && $this->_requete->bindValue('iddeck', $iddeck)                     
                ) {
                if($this->_requete->execute()) {
                    return 'Le Deck Model a bien été supprimé';
                } 
            }
        } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
    }
}



}