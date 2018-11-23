<?php 


class Player extends User {
  /***************************************************************************************************
 *                                       CONSTANT
****************************************************************************************************/
    
   Const MAX_HAND = '7' ; 
/***************************************************************************************************
 *                                       ATTRIBUTS
****************************************************************************************************/
        /**
     * Id de du Player
     *
     * @var int
     */
    private $_playerid;

    /**
     * Tableau des Cartes Jouées  dans la partie
     *
     * @var array Deck
     */
    private $_deck;

    /**
     * Objet Hero Game qu'il incarne dans la partie
     *
     * @var object HeroGame
     */
    private $_heroGame;

    /**
     * Objet Game
     *
     * @var array Game
     */
    private $_game;


    /***************************************************************************************************
  *                                       HYDRATE
  ****************************************************************************************************/
 /**
   * Hydratation
   *
   * @param array $data
   * @return void
   */
  public function hydrate(array $data) {
    foreach( $data as $key=>$value ) {
        $methodName = 'set' .substr($key,3);
      if(method_exists($this, $methodName)) {
        $this->$methodName($value);
      }
    }
  }
/***************************************************************************************************
  *                                       MAGIC
  ****************************************************************************************************/
  /**
   * Constructeur
   *
   * @param array $data
   */
  public function __construct(array $data) {
    Parent::__construct($data);
    $this->hydrate($data);
  }

/***************************************************************************************************
 *                                       GETTERS
****************************************************************************************************/
     /**
     * Get id du player
     *
     * @return  int
     */ 
    public function get_playerid(){return $this->_playerid;}

    /**
     * Get heroGame
     *
     * @return  hero
     */ 
    public function get_heroGame(){ return $this->_heroGame;}

    /**
     * Get deck
     *
     * @return  array
     */ 
    public function get_deck(){return $this->_deck;}
      /**
     * Get game
     *
     * @return  array
     */ 
    public function get_game(){return $this->_game;}

/***************************************************************************************************
*                                       SETTERS
****************************************************************************************************/
    /**
     * Set id de l'user
     *
     * @param  int  $_id  Id de l'user
     *
     * @return  self
     */ 
    public function set_playerid(int $_id) {
        $this->_playerid = $_id;

        return $this;
    }
     /**
     * Set deck
     *
     * @param  array  $_deck  Deck
     *
     * @return  self
     */ 
    public function set_deck(array $_deck){
        $this->_deck = $_deck;

        return $this;
    }

     /**
     * Set heroGame
     *
     * @param  array  $_heroGame  HeroGame
     *
     * @return  self
     */ 
    public function set_heroGame(Hero $_heroGame){
        $this->_heroGame = $_heroGame;

        return $this;
    }
    /**
     * Set game
     *
     * @param  Game  $_game  Game
     *
     * @return  self
     */ 
    public function set_game(Game $_game){
      $this->_game = $_game;

      return $this;
  }



/***************************************************************************************************
  *                                       FUNCTIONS
****************************************************************************************************/


/**--------------------------------------------------------------------------------------------------------------------- */
// --------------------------------- FONCTIONS POUR GERER LA POSITION DES CARTES  --------------------------------------//
/**--------------------------------------------------------------------------------------------------------------------- */
/**
 * get_CardsInHands............. permet de retourner les cartes qui sont en mains du Player
 *
 * @return array $Cards_In_Hand.................. Tableau d'objet Cards qui sont en main
 */
  public function get_CardsInHand() {
    $CardsInHand = array();
    foreach ($this->get_deck() as $key => $card) {
      
      if ($card->get_position() == 1 ) {
        $CardsInHand[] = $card;
      }
    } return $CardsInHand;
  }

  /**
   * get_CardsInDeck............. permet de retourner les cartes qui sont dans le deck du Player
   *
   * @return array $Cards_In_Hand.................. Tableau d'objet Cards qui en Decks
   */
  public function get_CardsInDeck() {
    $CardsInDeck = array();

    foreach ($this->get_deck() as $key => $card) {
      if ($card->get_position() == 0 ) {
        $CardsInDeck[] = $card;
      }
    } 
    return $CardsInDeck;
  }

  /**
   * get_CardsInBoard............. permet de retourner les cartes qui sont sur le Board du Player
   *
   * @return array $Cards_In_Hand.................. Tableau d'objet Cards qui sont sur le Board
   */
  public function get_CardsOnBoard() {
    $CardsInBoard = array();

    foreach ($this->get_deck() as $key => $card) {
      if ($card->get_position() == 2 ) {
        $CardsInBoard[] = $card;
      }
    } return $CardsInBoard;
  }
  /**
   * get_CardsInCemeteries............. permet de retourner les cartes qui sont sur le Board du Player
   *
   * @return array $Cards_In_Hand.................. Tableau d'objet Cards qui sont sur le Board
   */
  public function get_CardsInCemeteries() {
    $CardsInCemeteries = array();
    foreach ($this->get_deck() as $key => $value) {
      if ($value->get_position() == 3 ) {
        $CardsInCemeteries[] = $value;
      }
    } return $CardsInCemeteries;
  }

  /**
 * get_Taunt.......................................... Connaitre S'Il Y A Des Provocations Dans Les Créatures Sur le Board
 *
 * @param Player $player................. Objet Player
 * @return void
 */
function get_Taunt() {
  $str ='';
  foreach ($this->get_CardsOnBoard() as $key => $card) {
      if ($card->get_card() === 4 ) {
      $str .= '
      <option value='.$card->get_id().'> ' . $card->get_name() . ' </option>
      ';
      }
  }
  return $str;
}

/**
 * get_CardsToAttack 
 *
 * @param Player $player
 * @return void
 */
function get_CardsToAttack(){
  $str = '';
  if ($this->get_Taunt() != ''){
      return $this->get_Taunt();
  } else {
      foreach ($this->get_CardsOnBoard() as $key => $card) {
      
          $str .= '
          <option value='.$card->get_id() .'> ' .$card->get_name() . ' </option>
          ';
      }
  }
  $str .= '
  <option value="hero"> Héro </option>
  ';
  return $str;
}

/**
 * get_CardCanAttack.............................. Montrer le Bouton Attque que si une carte peut attaquer
 *
 * @param array $Board_Player................... Les cartes qui sont sur le Board pour attaquer
 * @return Boolean
 */
function get_CardCanAttack() {
  foreach ($this->get_CardsOnBoard() as $key => $card) {
     if($card->get_etat() == true) {
         return true;
     }
  }
  return false;
}


  /**--------------------------------------------------------------------------------------------------------------------- */
  // --------------------------------- FONCTIONS POUR GERER LE PREMIER JOUEUR  --------------------------------------//
  /**--------------------------------------------------------------------------------------------------------------------- */

 /**
     * Random_Player..................... Choisir Aléatoirement si le Joueur 1 doit jouer en Premier ou non.
     *
     * @param boolean $turn........... Connaitre si le joueur doit jouer ou non
     * @return bool $turn............. Oui / Non
     */
    public function FirstPlayer () {
      $turn = (bool) mt_rand(0,1);
       return boolval($turn);
  }

 

/**--------------------------------------------------------------------------------------------------------------------- */
// --------------------------------- FONCTIONS POUR GERER LA PIOCHE ET CARTES   --------------------------------------//
/**--------------------------------------------------------------------------------------------------------------------- */

  /** Draw................................ Permet de Piocher une carte à la fois 
  * 
  * 
  * @return Bool ..................... True = Réussi /// False = Echoué
  */
  public function get_CardDraw(){

    $deck = $this->get_CardsInDeck();
  
    if(count($deck) > 0 ){
        $cardInHand = $this->get_CardsInDeck();
        Shuffle($deck);

        if (count($cardInHand) < SELF::MAX_HAND && count($deck) > 0 ) {
            return $deck[0];
        }

    } return $deck[0] ;

  }

  
  /**
   * PlaySpellAttackArea........................................ Fonction pour les Cartes Sort qui ont un Effet sur TOUT le Board Adverse
   *
   * @param I/O array $Spell_Card.................................. Carte Sort qui est jouée
   * @param I/O array $Board_Player_DFS............................ Board du Joueur Adverse
   * @return void
   */
  function PlaySpellAttackArea(Card $Spell_Card, Player $player2 ){
      
    foreach ($player2->get_CardsInBoard() as  $card) {

            $card->set_hp($card->get_hp() - $Spell_Card->get_pa()); 

            return $boardAdv;
        }
    }


  /**
   * PlayCreatureCard 
   *
   * @param Card $Card
   * @return void
   */
  function PlayCard( Card $Card){
    if( $this->get_heroGame()->get_mana() >= $Card->get_mana()) {
        $this->get_heroGame()->set_mana($this->get_heroGame()->get_mana() - $Card->get_mana());  
        return true;
    } 
    return false; 
  }

  



  /**--------------------------------------------------------------------------------------------------------------------- */
// --------------------------------- FONCTIONS De View des Cartes  --------------------------------------//
/**--------------------------------------------------------------------------------------------------------------------- */
function ShowManaImage(){
  if($this->get_heroGame()->get_mana() > 0 ){
      for ($i=1; $i <= $this->get_heroGame()->get_mana() ; $i++) { 
          echo'<img src="./assets/images/mana_pleins.png" alt="Picto_Mana" />';
      }
  }
  $Mana_Vide = $this->get_heroGame()->get_mana_max() - $this->get_heroGame()->get_mana(); 
  if($Mana_Vide > 0){
      for ($i=0; $i <  $Mana_Vide ; $i++) { 
          echo'<img  src="./assets/images/mana_vide.png" alt="Picto_Mana" />';
      }
  }
}

/**
 * MontrerImageCarte..... Montrer le visuel d'une carte Sur le Board
 *
 * @param array $Cartes........... Joueur   
 * @return void
 */
function ShowCardOnBoard(){
  $str ='';
  $cardBoard = $this->get_CardsOnBoard();
  if(count($cardBoard) > 0 ){
      foreach ($cardBoard as  $key => $card) {
          $str .= '<label class="cardBoard "for="'. $card->get_id() .'">';
          if ($card->get_etat() == true ){
              $str .=  '<input type="radio" name="Board" id="'.$card->get_id().'" value='.$card->get_id() . '>';
              $str .= '<div class="back_card" style ="outline:2px solid green;">';
          } else {
            $str .= '<div class="back_card">';
          }
          $str .= '     <img class="picture_card" src="'.$card->get_picture().'" alt=""/>
                        <img class="type_of_card" src="'.$card->get_url_background_pion().'" alt=""/>  
                        <p class="name_card orange_color calango"> '.$card->get_name().' </p>    
                        <p  class="hp_cardBoard beige calango"> '.$card->get_hp().' </p>
                        <p  class="pa_cardBoard beige calango">'.$card->get_pa().' </p>     
                    </div>
                </label>
                  ';
      }
  } 
  return $str;  
}

/**
 * ShowCardOnBoardAdv..... Montrer le visuel d'une carte Sur le Board ADV
 *
 * @param array $Cartes........... Joueur   
 * @return void
 */
function ShowCardOnBoardAdv(){
  $str ='';
  $cardBoard = $this->get_CardsOnBoard();
  if(count($cardBoard) > 0 ){
      foreach ($cardBoard as  $key => $card) {          
          $str .= '<label class="cardBoard "for="'. $card->get_id() .'">
            <div class="back_card">
              <img class="picture_card" src="'.$card->get_picture().'" alt=""/>
              <img class="type_of_card" src="'.$card->get_url_background_pion().'" alt=""/>  
              <p class="name_card orange_color calango"> '.$card->get_name().' </p>    
              <p  class="hp_cardBoard beige calango"> '.$card->get_hp().' </p>
              <p  class="pa_cardBoard beige calango">'.$card->get_pa().' </p>     
            </div>
          </label>
                  ';
      }
  } 
  return $str;  
}

/**
 * ShowCardOnHand..... Montrer le visuel d'une carte en Main
 *
 * @param array $Cartes........... Joueur   
 * @return void
 */
function ShowCardInHand(){
  $str ='';
  $cardBoard = $this->get_CardsInHand();
  if(count($cardBoard) > 0 ){
      foreach ($cardBoard as  $key => $card) {
 
          if ($card->get_card() == 2) {
            $str .= ' <label class="cardGame" for="card-'. $card->get_id() .'">
                        <input type="radio" name="Hand[]" id="card-'.$card->get_id() .'" value='.$card->get_id() . '>
                        <div class ="card-face">
                          <div class="back_card">
                            <img class="picture_card" src="'.$card->get_picture().'" alt=""/>
                            <img class="type_of_card" src="'.$card->get_url_background_card().'" alt=""/>   
                            <p class="name_card_sort orange_color calango"> '.$card->get_name().' </p> 
                            <p class="mana_card_sort beige calango"> '.$card->get_mana().' </p> 
                            <q class="quote_card_sort beige"> '.$card->get_quote().' </q>
                            <p class="descrip_card_sort beige"> '.$card->get_description().' </p>       
                          </div>
                        </div>
                      </label>
                    ';
          } else {
            $str .= '<label  class="cardGame" for="card-'. $card->get_id() .'">
                        <input type="radio" name="Hand[]" id="card-'.$card->get_id() .'" value='.$card->get_id() . '>
                        <div class ="card-face">
                          <div class="back_card">
                            <img class="picture_card" src="'.$card->get_picture().'" alt=""/>
                            <img class="type_of_card" src="'.$card->get_url_background_card().'" alt=""/>     
                            <p class="name_card orange_color calango"> '.$card->get_name().' </p>     
                            <p  class="hp_card beige calango"> '.$card->get_hp().' </p>
                            <p  class="pa_card beige calango">'.$card->get_pa().' </p>
                            <p class="mana_card beige calango"> '.$card->get_mana().' </p> 
                            <q class="quote_card beige"> '.$card->get_quote().' </q>
                            <p class="descrip_card beige"> '.$card->get_description().' </p>     
                          </div>
                        </div>
                    </label>
                    ';
          }
           

      }
  } 
  return $str;  
}

/**
 * ShowCardOnHand..... Montrer le visuel d'une carte en Main de Dos 
 *
 * @param array $Cartes........... Joueur   
 * @return void
 */
function ShowCardInHandBack(){
  $str ='';
  $cardBoard = $this->get_CardsInHand();
  if(count($cardBoard) > 0 ){
      foreach ($cardBoard as  $key => $card) {  
          $str .= '
          <div class="cardGame"> 
            <label for="'. $card->get_id() .'">
            <div class ="card-face">
              <div class="back_card">
                <img class="background_card" src="'.$card->get_background_card().'" alt=""/>    
              </div>
            </div>
          </label>
        </div>
          ';
      }
  } 
  return $str;  
}

/**
 * ShowCardBack..... Montrer le visuel d'une carte  de Dos 
 *  
 * @return void
 */
function ShowCardBack(){
  $str ='';
  $cardBoard = $this->get_CardsInHand();
  if(count($cardBoard) > 0 ){
          $str .= ' <label for="'. $cardBoard[0]->get_id() .'">
                      <div class="back_card">
                        <img class="background_card" src="'.$cardBoard[0]->get_background_card().'" alt=""/>    
                      </div>
                  </label>
          ';
      }
  return $str;  
}




/**
 * ShowCardForSwap..... Montrer le visuel d'une carte en Main Pour le Swap
 *
 * @param array $Cartes........... Cartes à montrer         
 * @return void
 */
function ShowCardForSwap() {
  $cardBoard = $this->get_CardsInHand();
  $str ='<div style="flex-direction:row; background:#F5F5DC"> <h2> Phase de Changement de Carte <br> vous pouvez selectionner les cartes que vous voulez changer </h2> </div>';
  if(count($cardBoard) > 0 ){
      foreach ($cardBoard as $card) {
        if ($card->get_card() == 2) {
          $str .= '<input type="checkbox" name="Hand[]" id="'.$card->get_id() .'" value='.$card->get_id() . '>
          <label for="'. $card->get_id() .'">
          <label for="'. $card->get_id() .'">
          <div class="back_card">
            <img class="picture_card" src="'.$card->get_picture().'" alt=""/>
            <img class="type_of_card" src="'.$card->get_url_background_card().'" alt=""/>   
            <p class="name_card orange_color calango"> '.$card->get_name().' </p> 
            <p class="mana_card_sort beige calango"> '.$card->get_mana().' </p> 
            <q class="quote_card_sort beige"> '.$card->get_quote().' </q>
            <p class="descrip_card_sort beige"> '.$card->get_description().' </p>       
        
          </div>
          </label>

                  ';
        } else {
         $str .=  '<input type="checkbox" name="Hand[]" id="'.$card->get_id() .'" value='.$card->get_id(). '>
                  <label for="'. $card->get_id() .'">
                  <div class="back_card">
                    <img class="picture_card" src="'.$card->get_picture().'" alt=""/>
                    <img class="type_of_card" src="'.$card->get_url_background_card().'" alt=""/>            
                    <p class="name_card orange_color calango"> '.$card->get_name().' </p>   
                    <p  class="hp_card beige calango"> '.$card->get_hp().' </p>
                    <p  class="pa_card beige calango">'.$card->get_pa().' </p>
                    <p class="mana_card beige calango"> '.$card->get_mana().' </p> 
                    <q class="quote_card beige"> '.$card->get_quote().' </q>
                    <p class="descrip_card beige"> '.$card->get_description().' </p>       
          </div>
                  </label>  ';
        }
      }
  } 
  $str .= '<div class="content-btn" >
              <button name="Swap" value="true" type="submit" class="btn btn-primary">
                  Valider 
              </button> 
          </div>' ;
  return $str;  
}


}