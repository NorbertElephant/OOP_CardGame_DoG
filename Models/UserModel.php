<?php 
/**
 * CRUD -- Create / Read / Update / Delete 
 */
class UserModel extends CoreModel {

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

    public function CreateUser (array $data) {
        try{
            if( ($this->_requete = $this->_db->prepare(' INSERT INTO `USER` (`USE_name`,`USE_firstname`,`USE_pseudo`,`USE_email`,`USE_psw`,`USE_rank_fk`) VALUES (:name, :firstname, :pseudo, :email, :psw, :rank) 
                                                ')) !== false )  {
                if($this->_requete->bindValue('name', $data['name'])&& 
                    $this->_requete->bindValue('firstname', $data['firstname']) &&
                    $this->_requete->bindValue('pseudo', $data['pseudo']) && 
                    $this->_requete->bindValue('email', $data['email']) &&
                    $this->_requete->bindValue('psw', $data['psw']) &&
                    $this->_requete->bindValue('rank', $data['rank'])
                    ) {
                    if($this->_requete->execute()) {
                    return 'L\'user a bien été crée';
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
     * Connection D'un utilisateur_ avec vérification BDD Login / Psw
     *
     * @param String $login................. Pseudo de l'utilisateur 
     * @param String $psw................... Mot de passe de l'utilisateur 
     * @return void
     */
    public function Connect($login,$psw) {
        try{
            if( ($this->_requete = $this->_db->prepare(' SELECT `USER`.*, `RAN_name` AS `USE_rank`, `RAN_power`AS `USE_power`
                                                FROM `USER`
                                                JOIN `RANK` ON `USE_rank_fk` = `RAN_id`
                                                WHERE `USE_pseudo`=:login
                                                AND `USE_psw`=:psw
                                                ')) !== false )  {
                 if($this->_requete->bindValue('login', $login)
                    && $this->_requete->bindValue('psw', $psw)) {
                    if($this->_requete->execute()) {
                        if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                            return  new User($data);
                        } 
                    } 
                } return false;
        }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
            }
    }
    

    /**
     * Read All ______ Lire tout les Users de la BDD 
     *
     * @return array  $Users............. Tableau d'objet User 
     */
    public function ReadAll($power) {
        try {
            if(($this->_requete = $this->_db->prepare('SELECT `USER`.*, `RAN_name` AS `RAN_rank`,  `RAN_power`AS `USE_power`
                                                    FROM `USER`
                                                    JOIN `RANK` ON `USE_rank_fk` = `RAN_id`
                                                    WHERE `RAN_power` >= :power 
                                                    ')) !==false) {
                if($this->_requete->bindValue('power', $power)){
                    $users = array();
                    if($this->_requete->execute()) {
                        while( ($data = $this->_requete->fetch(PDO::FETCH_ASSOC) ) !== false ){
                
                        $users[] =new User($data);
                        }
                    }

                }   return $users;
            } return false ;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
            }
    }

    /**
     * ReadOne____ Ressortir un seul Objet User par rapport a son ID 
     *
     * @param int $id
     * @return Object User 
     */
    public function ReadOne(int $id) {
        try {
            if( ($this->_requete = $this->_db->prepare(' SELECT `USER`.*, `RAN_name` AS `RAN_rank`, `RAN_power`AS `USE_power`
                                                FROM `USER`
                                                JOIN `RANK` ON `USE_rank_fk` = `RAN_id`
                                                WHERE `USE_id`=:id
                                                ')) !== false )  {
                 if($this->_requete->bindValue('id', $id)){
                    if($this->_requete->execute()) {
                        if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                            return  new User($data);
                        } 
                    } 
                } return false;
        }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
            }
    }
    /**
     * ReadOne____ Ressortir un seul Objet User par rapport a son ID 
     *
     * @param int $id
     * @return Object User 
     */
    public function ReadOneByPlayer($id) {
        try {
            if( ($this->_requete = $this->_db->prepare(' SELECT `USER`.*, `RAN_name` AS `RAN_rank`, `RAN_power`AS `USE_power`
                                                FROM `USER`
                                                JOIN `RANK` ON `USE_rank_fk` = `RAN_id`
                                                JOIN `PLAYER` ON `USER`.`USE_id` = `PLAYER`.`PLA_user_fk`
                                                WHERE `PLA_id`=:id
                                                ')) !== false )  {
                 if($this->_requete->bindValue('id', $id)){
                    if($this->_requete->execute()) {
                        if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                            return  new User($data);
                        } 
                    } 
                } return false;
        }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
            }
    }


    /**
     * ValidCreate............... Regarde Si le Pseudo ou Email existe déjà...  
     *
     * @param String $pseudo............................. Pseudo qu'on veut rentrer dans la BDD
     * @param String $email............................. Email qu'on veut rentrer dans la BDD
     * @return Object User S'il existe un User avec le Pseudo ou email demandés 
     *  @return true S'il n'existe pas de User avec le Pseudo ou email demandés
     */
    public function ValidCreate($pseudo, $email) {
        try {
            if( ($this->_requete = $this->_db->prepare(' SELECT `USER`.*, `RAN_name` AS `rank`, `RAN_power`AS `USE_power`
                                                FROM `USER`
                                                JOIN `RANK` ON `USE_rank_fk` = `RAN_id`
                                                WHERE `USE_pseudo`=:pseudo
                                                OR `USE_email`=:email
                                                ')) !== false )  {
                 if($this->_requete->bindValue('pseudo', $pseudo)&&
                 $this->_requete->bindValue('email', $email)){
                    if($this->_requete->execute()) {
                        if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                            return  new User($data);

                        } return true;
                    } 
                } 
            } 
        }catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
            }
    }



    /**
     * ValidUpdate................... Regarde Si le Pseudo ou Email existe déjà dans la BDD a part lui
     *
     * @param User $user................. Objet User que l'on veut Modifier
     ** @return Object User S'il existe un User avec le Pseudo ou email demandés 
     *  @return true S'il n'existe pas de User avec le Pseudo ou email demandés
     */
    public function ValidUpdate(User $user) {
        try {
            if( ($this->_requete = $this->_db->prepare(' SELECT `USER`.*, `RAN_name` AS `RAN_rank`, `RAN_power`AS `USE_power`
                                                FROM `USER`
                                                JOIN `RANK` ON `USE_rank_fk` = `RAN_id`
                                                WHERE `USE_id` != :id
                                                AND (`USE_pseudo`=:pseudo
                                                OR `USE_email`=:email)
                                                ')) !== false )  {
                 if( $this->_requete->bindValue('pseudo', $user->get_pseudo())&&
                     $this->_requete->bindValue('email', $user->get_email()) && 
                     $this->_requete->bindValue('id', $user->get_id())){
                    if($this->_requete->execute()) {
                        if(($data = $this->_requete->fetch(PDO::FETCH_ASSOC))!==false) {
                            return  new User($data);

                        } return true;
                    } 
                } 
            } 
        }catch (PDOException $e) {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
            }
    }


/***************************************************************************************************
  *                                       UPDATE 
****************************************************************************************************/
    /**
     * Undocumented function
     *
     * @param integer $id................ Id de l'user a modifié
     * @param array $data................ Tableau des POST du formulaire
     * @param integer $power............. Si l'user connecté a le pouvoir de le modifié
     * @return void
     */
    public function UpdateUser (int $id, array $data) {
        try{
            if( ($this->_requete = $this->_db->prepare(' UPDATE `USER`  
                                                         SET    `USE_name`=:name ,
                                                                `USE_firstname`=:firstname,
                                                                `USE_pseudo`=:pseudo ,
                                                                `USE_email`=:email,
                                                                `USE_psw`=:psw, 
                                                                `USE_rank_fk` =:rank 
                                                         WHERE `USE_id`=:id
                            
                                                ')) !== false )  {
                if($this->_requete->bindValue('id', $id)&& 
                    $this->_requete->bindValue('name', $data['name'])&& 
                    $this->_requete->bindValue('firstname', $data['firstname']) &&
                    $this->_requete->bindValue('pseudo', $data['pseudo']) && 
                    $this->_requete->bindValue('email', $data['email']) &&
                    $this->_requete->bindValue('psw', $data['psw']) &&
                    $this->_requete->bindValue('rank', $data['rank'])
                    ) {
                    if($this->_requete->execute()) {
                    return 'L\'user a bien été modifé';
                    } 
                } return false;
        }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),99,$e);
            }
    }
    /**
     * UpdateUserConnectOn 
     *
     * @param user $user................ Object User
     * @return void
     */ 
    public function UpdateUserConnectOn (USER  $user) {
        try{
            if( ($this->_requete = $this->_db->prepare(' UPDATE `USER`  
                                                         SET  `USE_connect` = 1
                                                         WHERE `USE_id`=:id
                            
                                                ')) !== false )  {
                if($this->_requete->bindValue('id', $user->get_id())
                    ) {
                    if($this->_requete->execute()) {
                    return true;
                    } 
                } return false;
        }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),99,$e);
            }
    }

    /**
     * UpdateUserConnectOff 
     *
     *  @param Player $player................ Object Player
     * @return void
     */ 
    public function UpdateUserConnectOff (PLAYER  $player) {
        try{
            if( ($this->_requete = $this->_db->prepare(' UPDATE `USER`  
                                                         SET  `USE_connect` = 0
                                                         WHERE `USE_id`=:id
                            
                                                ')) !== false )  {
                if($this->_requete->bindValue('id', $player->get_id())
                    ) {
                    if($this->_requete->execute()) {
                    return true;
                    } 
                } return false;
        }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),99,$e);
            }
    }
    /**
     * UpdateNumGamePlayed..................  Augmente le nombre de partie jouée à la fin de la partiee
     *
     * @param Player $player................ Objet User de fin de partie 
     * @return Bool
     */ 
    public function UpdateNumGamePlayed (Player $player) {
        $NumGame =  $player->get_NumGamePlayed_player() + 1; 
        try{
            if( ($this->_requete = $this->_db->prepare(' UPDATE `USER`  
                                                         SET  `USE_NumGamePlayed_player` =:NumGame
                                                         WHERE `USE_id`=:id
                            
                                                ')) !== false )  {
                if($this->_requete->bindValue('NumGame', $NumGame )
                   && $this->_requete->bindValue('id', $player->get_id() )
                    ) {
                    if($this->_requete->execute()) {
                    return true;
                    } 
                } return false;
        }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(),99,$e);
            }
    }
    /**
     * UpdateNumGamePlayed..................  Augmente le nombre de partie Gagné à la fin de la partiee
     *
     * @param Player $player................ Objet User de fin de partie 
     * @return Bool
     */ 
    public function UpdateNumGameWin (Player $player) {
        $NumGameWin =  $player->get_NumGameWin_player() + 1; 
        try{
            if( ($this->_requete = $this->_db->prepare(' UPDATE `USER`  
                                                         SET  `USE_NumGameWin_player` =:NumGameWin
                                                         WHERE `USE_id`=:id
                            
                                                ')) !== false )  {
                if($this->_requete->bindValue('NumGameWin', $NumGameWin )
                   && $this->_requete->bindValue('id', $player->get_id() )
                    ) {
                    if($this->_requete->execute()) {
                    return true;
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

    public function DeleteUser(int $id) {
        try{
            if( ($this->_requete = $this->_db->prepare(' DELETE FROM `USER`
                                                         WHERE `USE_id` =:id
                                                        ' )) !== false )  {
                if($this->_requete->bindValue('id', $id) ) {
                    if($this->_requete->execute()) {
                        return 'L\'user a bien été supprimé';
                    } 
                }
            } return false;
    } catch (PDOException $e) {
        throw new Exception($e->getMessage(),99,$e);
        }
    }










}


