<?php //  

Class Card {
    
/***************************************************************************************************
 *                                       ATTRIBUTS
****************************************************************************************************/
    /**
     * _id De la carte 
     *
     * @var Int
     */
    private $_id;

    /**
     * Nom de la Carte
     *
     * @var string
     */
    private $_name;
     
    /**
     * Description de la Carte
     *
     * @var String
     */
    private $_description;

    /**
     *  URL du background de l'image
     *
     * @var String
     */
    private $_background_card;

    /**
     * URL du personnage de la carte
     *
     * @var String
     */
    private $_picture;

    /**
     * Coût en mana de la Carte
     *
     * @var Int
     */
    private $_mana;

    /**
     * Point D'attaque de la Carte
     *
     * @var Int 
     */
    private $_pa;

    /**
     * Nombres de Points de Vie de la Carte
     *
     * @var Int 
     */
    private $_hp;

    /**
     * Positionnement de la Carte 
     *  0 : Decks
     *  1 : Main
     *  2 : Board
     *  3 : Cimetiere
     * @var INT 
     */
    private $_position;

    /**
     * Citation des Cartes 
     *
     * @var String
     */
    private $_quote;

    /**
     * Hero représentant les cartes
     *
     * @var INT
     */
    private $_hero_model_fk;

    /**
     * Nombres de Copy max dans le Decks
     *
     * @var Int
     */
    private $_copy_max;


    /// Attributs en plus avec les Copy de Cartes ! 
    /**
     * Etat de la Carte
     * 0 : En sommeil
     * 1 : Reveil
     * @var Bool
     */
    private $_etat;
    
    /**
     * Id du type de Cartes 
     *
     * @var INT 
     */
    private $_card;

    /**
     * Nom du Type de Carte
     *
     * @var String
     */
    private $_type;

    /**
     * Image du bakcground type de carte 
     *
     * @var String
     */
    private $_url_background_card;

    /**
     * Image Sur Board du background type carte
     *
     * @var String
     */
    private $_url_background_pion;
    

    /**
     * DATETIME de la création de la copie
     *
     * @var string
     */
    private $_card_player;
    
    /**
     * ID de la carte Copié 
     *
     * @var INT
     */
   private $_card_model_fk;

   /**
     * ID de l'user qui l'utilise 
     *
     * @var INT
     */
   private $_player_fk;

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
     * Get _id De la carte
     *
     * @return  Int
     */ 
    public function get_id(){ return $this->_id;}

    /**
     * Get nom de la Carte
     *
     * @return  string
     */ 
    public function get_name(){ return $this->_name;}

    /**
     * Get description de la Carte
     *
     * @return  String
     */ 
    public function get_description(){ return $this->_description;}

    /**
     * Get uRL du background de l'image
     *
     * @return  String
     */ 
    public function get_background_card(){ return $this->_background_card;}

    /**
     * Get uRL du personnage de la carte
     *
     * @return  String
     */ 
    public function get_picture(){ return $this->_picture;}

    /**
     * Get coût en mana de la Carte
     *
     * @return  Int
     */ 
    public function get_mana(){ return $this->_mana;}

    /**
     * Get point D'attaque de la Carte
     *
     * @return  Int
     */ 
    public function get_pa(){ return $this->_pa;}

    /**
     * Get nombres de Points de Vie de la Carte
     *
     * @return  Int
     */ 
    public function get_hp(){ return $this->_hp;}

    /**
     * Get 3 : Cimetiere
     *
     * @return  INT
     */ 
    public function get_position(){ return $this->_position;}

    /**
     * Get citation des Cartes
     *
     * @return  String
     */ 
    public function get_quote(){ return $this->_quote;}
    
    /**
     * Get hero représentant les cartes
     *
     * @return  INT
     */ 
    public function get_hero_model_fk() {return $this->_hero_model_fk;}

    /**
     * Get nombres de Copy max dans le Decks
     *
     * @return  Int
     */ 
    public function get_copy_max(){ return $this->_copy_max;}

    /**
     * Get 1 : Reveil
     *
     * @return  Bool
     */ 
    public function get_etat(){ return $this->_etat;}

    /**
     * Get id du type de Cartes
     *
     * @return  INT
     */ 
    public function get_card(){ return $this->_card;}

    /**
     * Get nom du Type de Carte
     *
     * @return  String
     */ 
    public function get_type(){ return $this->_type;}

    /**
     * Get image du bakcground type de carte
     *
     * @return  String
     */ 
    public function get_url_background_card(){ return $this->_url_background_card;}

    /**
     * Get image Sur Board du background type carte
     *
     * @return  String
     */ 
    public function get_url_background_pion(){ return $this->_url_background_pion;} 
    
     /**
    * Get iD de la carte Copié
    *
    * @return  INT
    */ 
   public function get_card_model_fk(){return $this->_card_model_fk;  }

      /**
    * Get iD de l'user qui l'utilise
    *
    * @return  INT
    */ 
    public function get_player_fk(){ return $this->_player_fk;  }
     /**
     * Get dATETIME de la création de la copie
     *
     * @return  string
     */ 
    public function get_card_player(){return $this->_card_player; }



/***************************************************************************************************
*                                       SETTERS
****************************************************************************************************/
  
    /**
     * Set _id De la carte
     *
     * @param  Int  $_id  _id De la carte
     *
     * @return  self
     */ 
    public function set_id(Int $_id){
        $this->_id = $_id;

        return $this;
    }

    
    /**
     * Set nom de la Carte
     *
     * @param  string  $_name  Nom de la Carte
     *
     * @return  self
     */ 
    public function set_name(string $_name){
        $this->_name = $_name;

        return $this;
    }

    /**
     * Set description de la Carte
     *
     * @param  String  $_descritpion  Description de la Carte
     *
     * @return  self
     */ 
    public function set_description(String $_description){
        $this->_description = $_description;

        return $this;
    }

    /**
     * Set uRL du background de l'image
     *
     * @param  String  $_background_picture  URL du background de l'image
     *
     * @return  self
     */ 
    public function set_background_card(String $_background_picture){
        $this->_background_card = $_background_picture;

        return $this;
    }

    /**
     * Set uRL du personnage de la carte
     *
     * @param  String  $_picture  URL du personnage de la carte
     *
     * @return  self
     */ 
    public function set_picture(String $_picture){
        $this->_picture = $_picture;

        return $this;
    }

    /**
     * Set coût en mana de la Carte
     *
     * @param  Int  $_mana  Coût en mana de la Carte
     *
     * @return  self
     */ 
    public function set_mana(Int $_mana){
        $this->_mana = $_mana;

        return $this;
    }

    /**
     * Set point D'attaque de la Carte
     *
     * @param  Int  $_pa  Point D'attaque de la Carte
     *
     * @return  self
     */ 
    public function set_pa(Int $_pa){
        $this->_pa = $_pa;

        return $this;
    }

    /**
     * Set nombres de Points de Vie de la Carte
     *
     * @param  Int  $_hp  Nombres de Points de Vie de la Carte
     *
     * @return  self
     */ 
    public function set_hp(Int $_hp){
        $this->_hp = $_hp;

        return $this;
    }

    /**
     * Set 3 : Cimetiere
     *
     * @param  INT  $_position  3 : Cimetiere
     *
     * @return  self
     */ 
    public function set_position(INT $_position){
        $this->_position = $_position;

        return $this;
    }

    /**
     * Set citation des Cartes
     *
     * @param  String  $_quote  Citation des Cartes
     *
     * @return  self
     */ 
    public function set_quote(String $_quote){
        $this->_quote = $_quote;

        return $this;
    }
    /**
     * Set hero représentant les cartes
     *
     * @param  INT  $_hero_model_fk  Hero représentant les cartes
     *
     * @return  self
     */ 
    public function set_hero_model_fk(INT $_hero_model_fk){
        $this->_hero_model_fk = $_hero_model_fk;

        return $this;
    }

    /**
     * Set nombres de Copy max dans le Decks
     *
     * @param  Int  $_copy_max  Nombres de Copy max dans le Decks
     *
     * @return  self
     */ 
    public function set_copy_max(Int $_copy_max){
        $this->_copy_max = $_copy_max;

        return $this;
    }

    
    /**
     * Set 1 : Reveil
     *
     * @param  Bool  $_etat  1 : Reveil
     *
     * @return  self
     */ 
    public function set_etat(BOOL $_etat){
        $this->_etat = $_etat;

        return $this;
    }

    /**
     * Set id du type de Cartes
     *
     * @param  INT  $_card  Id du type de Cartes
     *
     * @return  self
     */ 
    public function set_card(INT $_card){
        $this->_card = $_card;

        return $this;
    }

    /**
     * Set nom du Type de Carte
     *
     * @param  String  $_type  Nom du Type de Carte
     *
     * @return  self
     */ 
    public function set_type(String $_type){
        $this->_type = $_type;

        return $this;
    }

    /**
     * Set image du bakcground type de carte
     *
     * @param  String  $_url_background_card  Image du bakcground type de carte
     *
     * @return  self
     */ 
    public function set_url_background_card(String $_url_background_card){
        $this->_url_background_card = $_url_background_card;

        return $this;
    }

    /**
     * Set image Sur Board du background type carte
     *
     * @param  String  $_url_background_pion  Image Sur Board du background type carte
     *
     * @return  self
     */ 
    public function set_url_background_pion(String $_url_background_pion){
        $this->_url_background_pion = $_url_background_pion;

        return $this;
    }
    
     /**
    * Set iD de la carte Copié
    *
    * @param  INT  $_card_model_fk  ID de la carte Copié
    *
    * @return  self
    */ 
   public function set_card_model_fk(INT $_card_model_fk) {
    $this->_card_model_fk = $_card_model_fk;

    return $this;
 }

 /**
    * Set iD de l'user qui l'utilise
    *
    * @param  INT  $_player_fk  ID de l'user qui l'utilise
    *
    * @return  self
    */ 
    public function set_player_fk(INT $_player_fk)
    {
       $this->_player_fk = $_player_fk;
 
       return $this;
    }
    
    /**
     * Set dATETIME de la création de la copie
     *
     * @param  string  $_card_player  DATETIME de la création de la copie
     *
     * @return  self
     */ 
    public function set_card_player(string $_card_player){
        $this->_card_player = $_card_player;

        return $this;
    }

/***************************************************************************************************
  *                                       FUNCTIONS
****************************************************************************************************/
    public function AttackCard(Card $card) {
        $card->set_hp($card->get_hp() - $this->get_pa());
    }

    public function AttackHero(Player $player) {
        $player->get_heroGame()->set_hp($player->get_heroGame()->get_hp() - $this->get_pa() );
        if($player->get_heroGame()->get_hp() <= 0) {
            return false;
        }
        return true;
    }


   
    /**
     * PlaySpellAttackArea........................................ Fonction pour les Cartes Sort qui ont un Effet sur TOUT le Board Adverse
     *
     * @param I/O array $Spell_Card.................................. Carte Sort qui est jouée
     * @param I/O array $Board_Player_DFS............................ Board du Joueur Adverse
     * @return void
     */
    function PlaySpellAttackArea(Player $player ){
        
        foreach ($player->get_CardsOnBoard() as $key => $card) {

            $card->set_hp($card->get_hp()-$this->get_pa()); 
        }
    }


/***************************************************************************************************
  *                                       VIEWS
****************************************************************************************************/

    public function ShowTr () {
        echo'<tr>'; 
        echo '<td> '.$this->get_id().'</td>';
        echo '<td> '.$this->get_name().'</td>';
        echo '<td> '.$this->get_description().'</td>';
        echo '<td><img src='.$this->get_picture().'></td>';
        echo '<td> '.$this->get_mana().'</td>';
        echo '<td> '.$this->get_pa().'</td>';
        echo '<td> '.$this->get_hp().'</td>';
        echo '<td> '.$this->get_position().'</td>';
        echo '<td> '.$this->get_quote().'</td>';
        echo '<td> '.$this->get_hero_model_fk().'</td>';
        echo '<td> '.$this->get_copy_max().'</td>';
        
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


    function ShowCard(){
        
        $str = '<div style="display: flex;flex-direction:row; flex-wrap:wrap">';

        if ($this->get_card() == 2) {
            $str .= '
            <div class="back_card">
            <img class="picture_card" src="'.$this->get_picture().'" alt=""/>
            <img class="type_of_card" src="'.$this->get_url_background_card().'" alt=""/>   
            <p class="name_card orange_color  calango"> '.$this->get_name().' </p> 
            <p class="mana_card_sort beige calango"> '.$this->get_mana().' </p> 
            <q class="quote_card_sort beige barlow  "> '.$this->get_quote().' </q>
            <p class="descrip_card_sort beige barlow  "> '.$this->get_description().' </p>       
        
            </div>
            </div>
                    ';
        } else {
            $str .= '
            <div class="back_card">
            <img class="picture_card" src="'.$this->get_picture().'" alt=""/>
            <img class="type_of_card" src="'.$this->get_url_background_card().'" alt=""/>     
            <p class="name_card orange_color calango"> '.$this->get_name().' </p>     
            <p  class="hp_card beige calango"> '.$this->get_hp().' </p>
            <p  class="pa_card beige calango">'.$this->get_pa().' </p>
            <p class="mana_card beige calango"> '.$this->get_mana().' </p> 
            <q class="quote_card beige barlow "> '.$this->get_quote().' </q>
            <p class="descrip_card beige barlow  "> '.$this->get_description().' </p>       
        
            </div>
            </div>
                    ';
        }
    
        return $str; 
    
    }

}