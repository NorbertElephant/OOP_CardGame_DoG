<?php


class Rank {

/***************************************************************************************************
 *                                       ATTRIBUTS
****************************************************************************************************/
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $_name;

    /**
     * Undocumented variable
     *
     * @var int
     */
    private $_id;
    /**
     * Undocumented variable
     *
     * @var int
     */
    private $_power;


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
 *                                    GETTERS
****************************************************************************************************/

    /**
     * Get undocumented variable
     *
     * @return  [type]
     */ 
    public function get_name(){ return $this->_name;}

   
    /**
     * Get undocumented variable
     *
     * @return  [type]
     */ 
    public function get_id() { return $this->_id;}

      /**
     * Get undocumented variable
     *
     * @return  int
     */ 
    public function get_power(){return $this->_power;}


/***************************************************************************************************
 *                                     SETTERS
****************************************************************************************************/
 /**
     * Set undocumented variable
     *
     * @param  [type]  $_name  Undocumented variable
     *
     * @return  self
     */ 
    public function set_name( $_name)
    {
        $this->_name = $_name;

        return $this;
    }


    /**
     * Set undocumented variable
     *
     * @param  [type]  $_id  Undocumented variable
     *
     * @return  self
     */ 
    public function set_id( $_id)
    {
        $this->_id = $_id;

        return $this;
    }

    /**
     * Set undocumented variable
     *
     * @param  int  $_power  Undocumented variable
     *
     * @return  self
     */ 
    public function set_power(int $_power)
    {
        $this->_power = $_power;

        return $this;
    }



/***************************************************************************************************
  *                                       Functions
****************************************************************************************************/
    /**
     * ShowOption ................. Sortie Visuel de tous les Rangs pour un Select
     *
     * @param int $rank_id............... L'id du rang
     * @return void
     */
    public function ShowOption (int $rank_id) {
       
        if ( $rank_id == $this->get_id() )  {
            echo '<option value="'. $this->get_id().'" selected> '.$this->get_name() .'</option>'; 
        } else{
            echo '<option value="'. $this->get_id().'">'.$this->get_name() .'</option>';
        }  
    }

  

    
}