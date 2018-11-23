<?php 

Class User {
    
/***************************************************************************************************
 *                                       ATTRIBUTS
****************************************************************************************************/
        /**
     * Id de l'user
     *
     * @var int
     */
    private $_id;

    /**
     * Email
     *
     * @var string
     */
    private $_email;

    /**
     * Pseudo
     *
     * @var string
     */
    private $_pseudo;

    /**
     * Mot De passe 
     *
     * @var string
     */
    private $_psw;

    /**
     * Confirmation email ou non 
     *
     * @var Bool
     */
    private $_valid;

    /**
     * Nom de l'user
     *
     * @var string
     */
    private $_name;

    /**
     * prenom de l'user
     *
     * @var string
     */
    private $_firstname;
    
    /**
     * Connecter ou non // utile pour la recherche d'adversaire
     *
     * @var Bool
     */
    private $_connect;

    /**
     * Nombres de parties jouées
     *
     * @var int
     */
    private $_NumGamePlayed_player;


    /**
     * Nombres de parties gagnées
     *
     * @var int
     */
    private $_NumGameWin_player;

    /**
     * Rank_id mais clés étrangères 
     *
     * @var int
     */
    private $_rank_fk;

      /**
     * Nom du Rank
     *
     * @var string
     */
    private $_rank;
       /**
     * Pouvoir en int 
     *
     * @var string
     */
    private $_power;
    
    /**
     * Decks de l'Users
     *
     * @var array
     */
    private $_decks;


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
     * Get id de l'user
     *
     * @return  int
     */ 
    public function get_id(){return $this->_id;}
    /**
     * Get email
     *
     * @return  string
     */ 
    public function get_email(){return $this->_email;}

    /**
     * Get pseudo
     *
     * @return  string
     */ 
    public function get_pseudo(){ return $this->_pseudo;}

    /**
     * Get mot De passe
     *
     * @return  string
     */ 
    public function get_psw(){ return $this->_psw;}

    /**
     * Get confirmation email ou non
     *
     * @return  Bool
     */ 
    public function get_valid(){return $this->_valid;}

    /**
     * Get nom de l'user
     *
     * @return  string
     */ 
    public function get_name(){return $this->_name;}

    /**
     * Get prenom de l'user
     *
     * @return  string
     */ 
    public function get_firstname(){ return $this->_firstname;}

    /**
     * Get connecter ou non // utile pour la recherche d'adversaire
     *
     * @return  Bool
     */ 
    public function get_connect(){return $this->_connect;}

    /**
     * Get nombres de parties jouées
     *
     * @return  int
     */ 
    public function get_NumGamePlayed_player(){return $this->_NumGamePlayed_player;}

    /**
     * Get nombres de parties gagnées
     *
     * @return  int
     */ 
    public function get_NumGameWin_player() {return $this->_NumGameWin_player;}
     /**
     * Get nombres de parties gagnées
     *
     * @return  int
     */ 
    public function get_rank_fk(){return $this->_rank_fk;}
     /**
     * Get nombres de parties gagnées
     *
     * @return  string
     */ 
    public function get_rank(){return $this->_rank;}
          /**
     * Get undocumented variable
     *
     * @return  string
     */ 
    public function get_power(){return $this->_power;}

    /**
     * Get decks de l'Users
     *
     * @return  array
     */ 
    public function get_decks(){return $this->_decks; }


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
    public function set_id(int $_id)
    {
        $this->_id = $_id;

        return $this;
    }
    /**
     * Set email
     *
     * @param  string  $_email  Email
     *
     * @return  self
     */ 
    public function set_email( $_email){
        $this->_email = $_email;

        return $this;
    }

    /**
     * Set pseudo
     *
     * @param  string  $_pseudo  Pseudo
     *
     * @return  self
     */ 
    public function set_pseudo( $_pseudo){
        $this->_pseudo = $_pseudo;

        return $this;
    }

    /**
     * Set mot De passe
     *
     * @param  string  $_psw  Mot De passe
     *
     * @return  self
     */ 
    public function set_psw( $_psw){
        $this->_psw = $_psw;

        return $this;
    }

    /**
     * Set confirmation email ou non
     *
     * @param  Bool  $_valid  Confirmation email ou non
     *
     * @return  self
     */ 
    public function set_valid( $_valid){
        $this->_valid = $_valid;

        return $this;
    }

    /**
     * Set nom de l'user
     *
     * @param  string  $_name  Nom de l'user
     *
     * @return  self
     */ 
    public function set_name( $_name){
        $this->_name = $_name;

        return $this;
    }

    /**
     * Set prenom de l'user
     *
     * @param  string  $_firstname  prenom de l'user
     *
     * @return  self
     */ 
    public function set_firstname( $_firstname){
        $this->_firstname = $_firstname;

        return $this;
    }

    /**
     * Set connecter ou non // utile pour la recherche d'adversaire
     *
     * @param  Bool  $_connect  Connecter ou non // utile pour la recherche d'adversaire
     *
     * @return  self
     */ 
    public function set_connect( $_connect){
        $this->_connect = $_connect;

        return $this;
    }

    /**
     * Set nombres de parties jouées
     *
     * @param  int  $_NumGamePlayed_player  Nombres de parties jouées
     *
     * @return  self
     */ 
    public function set_NumGamePlayed_player( $_NumGamePlayed_player){
        $this->_NumGamePlayed_player = $_NumGamePlayed_player;

        return $this;
    }

    /**
     * Set nombres de parties gagnées
     *
     * @param  int  $_NumGameWin_player  Nombres de parties gagnées
     *
     * @return  self
     */ 
    public function set_NumGameWin_player( $_NumGameWin_player){
        $this->_NumGameWin_player = $_NumGameWin_player;

        return $this;
    }

    /**
     * Set nombres de parties gagnées
     *
     * @param  int  $_rank_fk  Nombres de parties gagnées
     *
     * @return  self
     */ 
    public function set_rank_fk( $_rank_fk){
        $this->_rank_fk = $_rank_fk;

        return $this;
    }

    /**
     * Set nombres de parties gagnées
     *
     * @param    $_rank  Nombres de parties gagnées
     *
     * @return  self
     */ 
    public function set_rank( $_rank) {
        $this->_rank = $_rank;

        return $this;
    }

       /**
     * Set undocumented variable
     *
     * @param    $_power  Undocumented variable
     *
     * @return  self
     */ 
    public function set_power($_power){
        $this->_power = $_power;

        return $this;
    }
     /**
     * Set decks de l'Users
     *
     * @param  array  $_decks  Decks de l'Users
     *
     * @return  self
     */ 
    public function set_decks(array $_decks){ $this->_decks = $_decks;
        return $this;
    }



/***************************************************************************************************
  *                                       Functions
****************************************************************************************************/
    /**
     * ShowTr.................. Sortie Visuel de tous les USERS pour un Tableau
     *
     * @return void
     */
    public function ShowTr () {
        echo'<tr>'; 
        echo '<td> '.$this->get_id().'</td>';
        echo '<td> '.$this->get_name().'</td>';
        echo '<td> '.$this->get_firstname().'</td>';
        echo '<td> '.$this->get_pseudo().'</td>';
        echo '<td> <span class="block-email">  '.$this->get_email().'</span> </td>';
            if ($this->get_valid() == 1 ){
                echo '<td> <span class="status--process"> '.$this->get_valid().' </span></td>';
            } else {
                echo '<td> <span class="status--denied">  '.$this->get_valid().' <span></td>';
            }
        
        echo '<td> '.$this->get_NumGamePlayed_player().'</td>';
        echo '<td> '.$this->get_NumGameWin_player().'</td>';
        echo '<td> '.$this->get_rank().'</td>';
        echo '<td> <div class="table-data-feature">
                    <form method="GET" action="create_user.php">
                        <button type="submit" value="'.$this->get_id().'" name="id" class="item" data-toggle="tooltip" data-placement="top" title="Modifier" data-original-title="Modifier">
                        <i class="zmdi zmdi-edit"></i>
                        </button>
                    </form>
                    <form method="POST">
                    <button type="submit" value="'.$this->get_id().'" name="delete" class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Supprimer">
                        <i class="zmdi zmdi-delete"></i>
                    </button>
                    </form>
                    <button class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="More">
                        <i class="zmdi zmdi-more"></i>
                    </button>
                </div>
            </td> 
            </tr>
            <tr class="spacer"></tr>';

    }


/**
 * NoEmptyDataUser.............. Vérification que les données ne sont pas vides 
 *
 * @param array $data.............. Tableau de tous les POST
 * @return false ou $error......... False = Pas d'erreur , Sinon un code par erreur
 */
    public function NoEmptyDataUser (array $data) {
        if(!empty($data['name'])) {
            if (!empty($data['firstname'])){
                if ( !empty($data['pseudo'])) {
                    if (!empty($data['email'])) {
                        if(!empty($data['psw'])) {
                            return false;
                        } else {
                            return $error = '_err:psw';
                        }
                    } else{
                         return $error = '_err:email';
                    }
                } else {
                   return  $error = '_err:pseudo';
                }
            }else {
                return $error = '_err:firstname';
            }
        } else {
           return  $error = '_err:name';
        } 
    }


    public function ValidDel($target) {
        if ($this->get_id() != $target->get_id()) {
            if($this->get_power() <= $target->get_power()) {
                return true;
            } return '_err:del';
        } return'_err:dels';
        
    }


    public function RejoinGame(  $j1,   $j2) {
        if($j1->get_connect() == true && $j2->get_connect()== true) {
            return true;
        }
        return false;
    }



   
}