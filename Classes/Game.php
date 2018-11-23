<?php 

class Game {
    /***************************************************************************************************
 *                                       ATTRIBUTS
****************************************************************************************************/
    /**
     * Id de la Game
     *
     * @var INT 
     */
    private $_id;

    /**
     * Début en DATETIME de la début de Partie
     *
     * @var STRING
     */
    private $_earlygame;
    
    /**
     * FIn en DATETIME de la Fin de partie 
     *
     * @var String
     */
    private $_endgame;

    /**
     * Id du plaayer créateur de la Game
     *
     * @var Int
     */
    private $_player_fk;

    /**
     * URL Image du Board 
     *
     * @var String
     */
    private $_board;

    /**
     * Tableau Objets 
     * @var array
     */
    private $_players;


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
     * Get id de la Game
     *
     * @return  INT
     */ 
    public function get_id(){ return $this->_id;}

    /**
     * Get début en DATETIME de la début de Partie
     *
     * @return  STRING
     */ 
    public function get_earlygame(){ return $this->_earlygame;}

    /**
     * Get fIn en DATETIME de la Fin de partie
     *
     * @return  String
     */ 
    public function get_endgame(){ return $this->_endgame;}

       /**
     * Get id du plaayer créateur de la Game
     *
     * @return  Int
     */ 
    public function get_player_fk(){ return $this->player_fk;}


    /**
     * Get uRL Image du Board
     *
     * @return  String
     */ 
    public function get_board(){ return $this->_board;}

    /**
     * Get tableau Objets des 2 Players de la partie
     *
     * @return  array
     */ 
    public function get_players(){ return $this->_players;}
    
 


/***************************************************************************************************
*                                       SETTERS
****************************************************************************************************/
    /**
     * Set id de la Game
     *
     * @param  INT  $_id  Id de la Game
     *
     * @return  self
     */ 
    public function set_id(INT $_id){
        $this->_id = $_id;

        return $this;
    }

    /**
     * Set début en DATETIME de la début de Partie
     *
     * @param  STRING  $_earlygame  Début en DATETIME de la début de Partie
     *
     * @return  self
     */ 
    public function set_earlygame(STRING $_earlygame){
        $this->_earlygame = $_earlygame;

        return $this;
    }

    /**
     * Set fIn en DATETIME de la Fin de partie
     *
     * @param  String  $_endgame  FIn en DATETIME de la Fin de partie
     *
     * @return  self
     */ 
    public function set_endgame( $_endgame){
        $this->_endgame = $_endgame;

        return $this;
    }

    /**
     * Set uRL Image du Board
     *
     * @param  String  $_board  URL Image du Board
     *
     * @return  self
     */ 
    public function set_board(String $_board){
        $this->_board = $_board;

        return $this;
    }

    /**
     * Set tableau Objets des 2 Players de la partie ?? 
     *
     * @param  array  $_players  Tableau Objets des 2 Players de la partie
     *
     * @return  self
     */ 
    public function set_players(array $_players){
        $this->_players = $_players;

        return $this;
    }
    
    /**
     * Set id du plaayer créateur de la Game
     *
     * @param  Int  $player_fk  Id du plaayer créateur de la Game
     *
     * @return  self
     */ 
    public function set_player_fk(Int $player_fk){
      $this->player_fk = $player_fk;

      return $this;
  }
   

/***************************************************************************************************
  *                                       FUNCTIONS
****************************************************************************************************/
 

   


 
}