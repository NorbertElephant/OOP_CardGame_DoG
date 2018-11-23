<?php 

Abstract class CoreModel {

/***************************************************************************************************
 *                                       ATTRIBUTS
****************************************************************************************************/ 
  
    /** Login / mdp pour connection a la BDD 
     * @var string $_db
     */
    protected $_db; 

    /** Login / mdp pour connection a la BDD 
     * @var string $_db
     */
    protected $_requete; 


/*****************************************************************************************************
 *                                       MAGIC
****************************************************************************************************/
    public function __construct($instance){
        /** DSN : Data Source Name */
        $dsn="mysql:host=127.0.0.1;
        dbname=DuelofGiants;
        charset=utf8mb4;";
        /**  nom de l'user de la BDD  */
        $user_name="root";
        /** Mdp du l'user pour la BDD */
        $user_psw=""; 
        try{
            $this->_db= $instance;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
        }   
    }


    public function __destruct(){
        if(!empty($this->_requete)){
            $this->_requete->closecursor();
        }
    }



}
