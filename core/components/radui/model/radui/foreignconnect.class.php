<?php


/**
 * Implementation of connection class that more closely follows the singleton pattern.
 */
class ForeignConnect {
    /**
     * @var (Array) of db_dns => (Object) the xPDO instance
     */
    private static $instance = array();
    
    /**
     * private constructor - called from static method getInstance
     */
    private function __construct($database_dsn, $username, $password){
        
    }
    public function __destruct(){
        $this->close();
    }
    /**
     * This static method creates an instance of the class if no instance already exists.
     * @param (String) $database_dsn
     * @param (String) $username
     * @param (String) $password
    */
    static public function getInstance($database_dsn, $username, $password){
        //global $modx;
        //$modx->log(xPDO::LOG_LEVEL_ERROR, 'getInstance');
        //instance must be static in order to be referenced here
        if(!isset(self::$instance[$database_dsn]) ){
            // new connection
            //$modx->log(xPDO::LOG_LEVEL_ERROR, 'New Connection getInstance DB: '.$database_dsn);
            self::$instance[$database_dsn] = new xPDO($database_dsn,
                $username,
                $password );
            
        } 
        //$modx->log(xPDO::LOG_LEVEL_ERROR, 'Return Connection');
        return self::$instance[$database_dsn];
    }
    /**
     * Close the instance
     */
    public function close(){
        self::$instance = array();
    }
}

/***
 * END CLASS
 */
 