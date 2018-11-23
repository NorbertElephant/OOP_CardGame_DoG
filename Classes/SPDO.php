<?php 
/**
 * ------------------------------------------------------------
 * SINGLETON REQUEST
 * ------------------------------------------------------------
**/

class SPDO {

 /**
     * --------------------------------------------------
     * STATICS
     * --------------------------------------------------
    **/
    private static $_instance;
    /**
     * --------------------------------------------------
     * PROPERTIES
     * --------------------------------------------------
    **/
    private $_db;



    /**
     * --------------------------------------------------
     * MAGIC METHODS
     * --------------------------------------------------
    **/
    /**
     * __construct - Class constructor
     * @param   string  $host
     *          string  $dbname
     *          string  $login
     *          string  $pass
     *          string  $charset
     *          string  $collate
     * @return  
    **/
    private function __construct( $host, $dbname, $login, $pass, $charset, $collate ) {
        try {
            $this->_db = new PDO( 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=' . $charset, $login, $pass, array( PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES ' . $charset . ' COLLATE ' . $collate, PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION ) );
        } catch( PDOException $e ) {
            throw $e;
        }
    }



    /**
     * --------------------------------------------------
     * STATIC METHODS
     * --------------------------------------------------
    **/
    /**
     * getInstance - 
     * @param   [string     $host]
     *          [string     $dbname]
     *          [string     $login]
     *          [string     $pass]
     *          [string     $charset]
     *          [string     $collate]
     * @return  
    **/
    public static function getInstance( $host = null, $dbname = null, $login = null, $pass = null, $charset = 'utf8', $collate = 'utf8_general_ci' ) {
        try {
            if( !isset( self::$_instance ) )
                self::$_instance = new SPDO( $host, $dbname, $login, $pass, $charset, $collate );

            return self::$_instance;
        } catch( Exception $e ) {
            throw $e;
        }
    }



    /**
     * --------------------------------------------------
     * GETTERS
     * --------------------------------------------------
    **/
    /**
     * getPDO - 
     * @param   
     * @return  
    **/
    public function getPDO() {
        return $this->_db;
    }
}