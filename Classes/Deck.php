<?php 


class Deck  {
    
/***************************************************************************************************
 *                                       ATTRIBUTS
****************************************************************************************************/
    CONST Max_cards = 20;

    /**
     * Id Du Decks
     *
     * @var INT 
     */
    private $_id;

    /**
     * Nom du Decks donnée par le player
     *
     * @var String
     */
    private $_name;

    /**
     * Date de Création du Deck
     *
     * @var String
     */
    private $_creation;

    /**
     * User qui l'a crée 
     *
     * @var array
     */
    private $_user;

    /**
     * Tableau des cartes qui composes le decks 
     *
     * @var array
     */
    private $_cards;


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
    $this->hydrate($data);
  }

/***************************************************************************************************
 *                                       GETTERS
****************************************************************************************************/
/**
     * Get id Du Decks
     *
     * @return  INT
     */ 
    public function get_id(){ return $this->_id; }

    /**
     * Get nom du Decks donnée par le player
     *
     * @return  String
     */ 
    public function get_name(){return $this->_name; }

    /**
     * Get date de Création du Deck
     *
     * @return  String
     */ 
    public function get_creation(){return $this->_creation;}

    /**
     * Get user qui l'a crée
     *
     * @return  array
     */ 
    public function get_user(){return $this->_user;}

    /**
     * Get tableau des cartes qui composes le decks
     *
     * @return  array
     */ 
    public function get_cards(){return $this->_cards; }

/***************************************************************************************************
*                                       SETTERS
****************************************************************************************************/
    /**
     * Set id Du Decks
     *
     * @param  INT  $_id  Id Du Decks
     *
     * @return  self
     */ 
    public function set_id(INT $_id){
        $this->_id = $_id;

        return $this;
    }

    /**
     * Set nom du Decks donnée par le player
     *
     * @param  String  $_name  Nom du Decks donnée par le player
     *
     * @return  self
     */ 
    public function set_name(String $_name){
        $this->_name = $_name;

        return $this;
    }

    /**
     * Set date de Création du Deck
     *
     * @param  String  $_creation  Date de Création du Deck
     *
     * @return  self
     */ 
    public function set_creation(String $_creation){
        $this->_creation = $_creation;

        return $this;
    }

    /**
     * Set user qui l'a crée
     *
     * @param  array  $_user  User qui l'a crée
     *
     * @return  self
     */ 
    public function set_user(array $_user){
        $this->_user = $_user;

        return $this;
    }

    /**
     * Set tableau des cartes qui composes le decks
     *
     * @param  array  $_cards  Tableau des cartes qui composes le decks
     *
     * @return  self
     */ 
    public function set_cards(array $_cards){
        $this->_cards = $_cards;

        return $this;
    }    

/***************************************************************************************************
  *                                       FUNCTIONS
****************************************************************************************************/

  public function Shuffle_deck(){
    $decks = $this->_cards;
    shuffle ($decks);
    $this->set_cards($decks);
  }

  public function Get_first_cards(){
    $this->_cards[0]->set_postion(1);
  }
   
   

   

    


}