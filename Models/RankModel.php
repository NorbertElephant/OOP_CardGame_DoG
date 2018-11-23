<?php
/**
 *  CRUD -- Create / Read / Update / Delete 
 */


class RankModel extends CoreModel {



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
  *                                       CREATE  
****************************************************************************************************/

/***************************************************************************************************
  *                                       READ 
****************************************************************************************************/
    /**
     * Read All ______ Lire tout les RANGS de la BDD 
     *
     * @return array  $Users............. Tableau d'objet Rank 
     */
    public function ReadAll($power) {
        try {
            if(($this->_requete = $this->_db->prepare('SELECT *
                                                    FROM `RANK`
                                                    WHERE `RAN_power` >= :power 
                                                    ')) !==false) 
                if($this->_requete->bindValue('power', $power)){
                    if($this->_requete->execute()) {
                        while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
                
                         $ranks[] =new Rank($data);
                        }
                    }   return $ranks;
            } return false ;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
            }
    }

/***************************************************************************************************
  *                                       UPDATE 
****************************************************************************************************/

/***************************************************************************************************
  *                                       DELETE 
****************************************************************************************************/

}