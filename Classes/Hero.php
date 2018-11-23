<?php 
 // 
class Hero {
/***************************************************************************************************
 *                                       CONSTANT
****************************************************************************************************/

Const GAIN_MANA_TURN = 1;
Const EARLY_DRAW_CARD = 3;
Const CARD_MAX_HAND = 10;

/***************************************************************************************************
 *                                       ATTRIBUTS
****************************************************************************************************/
    /**
     * Id du Héro de la partie
     *
     * @var int
     */
    private $_id;


    /**
     * Nom du Héro incarner
     *
     * @var string
     */
    private $_name;
    
    /**
     * Image du héro 
     *
     * @var string
     */
    private $_picture;

    /**
     * Image du héro pour la Game
     *
     * @var string
     */
    private $_pion;

    /**
     * Les points de vie du Héro
     *
     * @var int
     */

    private $_hp;

    /**
     * Son tour pour jouer ou non
     *
     * @var bool
     */
    private $_turn;

    /**
     * S'il a a pu changer ses cartes au Tour 0
     *
     * @var bool
     */
    private $_swap;

    /**
     * Mana du Héro
     *
     * @var int
     */
    private $_mana;

     /**
     * Mana Max du Héro
     *
     * @var int
     */
    private $_mana_max;

     /**
     * ID de la Faction qu'il représente
     *
     * @var int
     */
    private $_factionID;

    /**
     * Nom de la Faction qu'il représente
     *
     * @var string
     */
    private $_factionName;

    /**
     * Compteur du nombres de tours dans la game
     * 
     *  @var int
     */
    private $_num_turn;


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
     * Get id du Héro de la partie
     *
     * @return  int
     */ 
    public function get_id(){ return $this->_id; }

    /**
     * Get nom du Héro incarner
     *
     * @return  int
     */ 
    public function get_name(){ return $this->_name; }

    /**
     * Get image du héro
     *
     * @return  string
     */ 
    public function get_picture() {return $this->_picture;  }
    
     /**
      * Get the value of _hp
      */ 
      public function get_hp(){ return $this->_hp;  }

    /**
     * Get son tour pour jouer ou non
     *
     * @return  bool
     */ 
    public function get_turn() {return $this->_turn;  }

    /**
     * Get s'il a a pu changer ses cartes au Tour 0
     *
     * @return  bool
     */ 
    public function get_swap(){return $this->_swap;}
    
    /**
     * Get mana du Héro
     *
     * @return  int
     */ 
    public function get_mana(){
        return $this->_mana;
    }

    /**
     * Get mana Max du Héro
     *
     * @return  int
     */ 
    public function get_mana_max() {return $this->_mana_max; }

    /**
     * Get iD de la Faction qu'il représente
     *
     * @return  int
     */ 
    public function get_factionID(){ return $this->_factionID; }

    /**
     * Get nom de la Faction qu'il représente
     *
     * @return  string
     */ 
    public function get_factionName(){return $this->_factionName; }
    
    /**
     * Get image du héro pour la Game
     *
     * @return  string
     */ 
    public function get_pion(){return $this->_pion;}

    
    /**
     * Get compteur du nombres de tours dans la game
     *
     * @return  int
     */ 
    public function get_num_turn(){ return $this->_num_turn; }

/*****************************************************************************************************
*                                       SETTERS
****************************************************************************************************/

    /**
     * Set id du Héro de la partie
     *
     * @param  int  $_id  Id du Héro de la partie
     *
     * @return  self
     */ 
    public function set_id(int $_id)
    {
        $this->_id = $_id;

        return $this;
    }

    /**
     * Set nom du Héro incarner
     *
     * @param  string  $_name  Nom du Héro incarner
     *
     * @return  self
     */ 
    public function set_name(string $_name)
    {
        $this->_name = $_name;

        return $this;
    }

    /**
     * Set image du héro
     *
     * @param  string  $_picture  Image du héro
     *
     * @return  self
     */ 
    public function set_picture(string $_picture)
    {
        $this->_picture = $_picture;

        return $this;
    }
    /**
     * Set image du héro pour la Game
     *
     * @param  string  $_pion  Image du héro pour la Game
     *
     * @return  self
     */ 
    public function set_pion(string $_pion){
        $this->_pion = $_pion;

        return $this;
    }
    
      /**
      * Set the value of _hp
      *
      * @return  self
      */ 
      public function set_hp($_hp){
        $this->_hp = $_hp;
        return $this;
   }

    /**
     * Set son tour pour jouer ou non
     *
     * @param  bool  $_turn  Son tour pour jouer ou non
     *
     * @return  self
     */ 
    public function set_turn(bool $_turn)
    {
        $this->_turn = $_turn;

        return $this;
    }

    /**
     * Set s'il a a pu changer ses cartes au Tour 0
     *
     * @param  bool  $_swap  S'il a a pu changer ses cartes au Tour 0
     *
     * @return  self
     */ 
    public function set_swap(bool $_swap)
    {
        $this->_swap = $_swap;

        return $this;
    }

    /**
     * Set mana du Héro
     *
     * @param  int  $_mana  Mana du Héro
     *
     * @return  self
     */ 
    public function set_mana(int $_mana)
    {
        $this->_mana = $_mana;

        return $this;
    }

    /**
     * Set mana Max du Héro
     *
     * @param  int  $_mana_max  Mana Max du Héro
     *
     * @return  self
     */ 
    public function set_mana_max(int $_mana_max)
    {
        $this->_mana_max = $_mana_max;

        return $this;
    }

    /**
     * Set iD de la Faction qu'il représente
     *
     * @param  int  $_factionID  ID de la Faction qu'il représente
     *
     * @return  self
     */ 
    public function set_factionID(int $_factionID)
    {
        $this->_factionID = $_factionID;

        return $this;
    }

    /**
     * Set nom de la Faction qu'il représente
     *<
     * @param  string  $_factionName  Nom de la Faction qu'il représente
     *
     * @return  self
     */ 
    public function set_factionName(string $_factionName)
    {
        $this->_factionName = $_factionName;

        return $this;
    }
    
    
    /**
     * Set compteur du nombres de tours dans la game
     *
     * @param  int  $_num_turn  Compteur du nombres de tours dans la game
     *
     * @return  self
     */ 
    public function set_num_turn(int $_num_turn) {
        $this->_num_turn = $_num_turn;

        return $this;
    }

/***************************************************************************************************
  *                                       Functions
****************************************************************************************************/
   
   

    


   



}