<?php

/**
* Description: This class help us to use real OOP on PHP. It stores objects
* within the session and maintains the state in memory.
*
* IMPORTANT: If other objects is attributes of the object that you're extending
* with ObjectManager class, they automatically will be save too.
*
* IMPORTANT 2: Objects that use File Handlers won't must be saved, so take care!
*
* Usage: 1. Extends this class inside the class that you want to persist.
*        2. Use that constructor structure:
*               public function __construct($params,$objName)
*               {
*                   // Your code here
*                   foreach( $params as $name => $value ){
*                       $this->$name = $value;
*                   }
*                   parent::__construct($objName);
*               }
*        3. Use that destructor structure:
*                   public function __destruct()
*                   {
*                       // Your code here
*                       parent::__destruct();
*                   }
*        4. To create object, use $myVar = MyClass::new('myVarName');
*           Where 'myVarName' is 'myVar' string on '$myVar'
*
*        5. To kill object, use 'myClass::kill($myObject)'
*
*
*
* @author  Igor Vinicius Reynaldo Tibúrcio @ LCQAr - UFSC <igorvinicius.rt@gmail.com>
* @since   July 01, 2018
* @link
* @version 1.2
*/

namespace GAr;

$config = array(
    'key' => 'my_key_string',
    'preventSessionFixationTime' => 1800,
    'timeout' => 3600
);


abstract class ObjectManager
{
    /**
    * This variable has object name when created
    * @var string
    */
    private $objName;
    
     /**
    * Store to kill object
    * @var boolean
    */
    private $killMe;

    /**
    * Class constructor
    *
    * @param string $objName
    */
    public function __construct($objName)
    {
        $this->objName = $objName;
        $this->preventSessionFixation();
        $this->killMe = false;
        $this->checkTimeout();
    }

    /**
    * Class destructor
    */
    public function __destruct()
    {
        if( !$this->killMe ){
            $this->saveState();
        }
    }

    /**
    * Static class to instanciate last state (if exists) of object
    * @static
    *
    * @param array $params Array of parameters
    * @param string $objName
    */
    public static function new( $params = NULL, $objName )
    {
        $className = get_called_class();
        self::startSession();
        
        return self::getLastState($objName,$className,$params);
    }

    /**
    * Starts new session to store and recover objetcs
    * @static
    */
    private static function startSession()
    {
        $key = $GLOBALS['config']['key'];

        if(isset($_SERVER['HTTP_USER_AGENT'])){
            session_name(md5($key.$_SERVER['REMOTE_ADDR'].strrev($key).
                $_SERVER['HTTP_USER_AGENT'].$key));
        } else {
            session_name(md5($key.$_SERVER['REMOTE_ADDR'].strrev($key).$key));
        }

        session_start();
    }

    /**
    * Find last object state from last session and restore
    * @static
    * @thrwos \Exception if $params is not array
    *
    * @param string $objName   Name of new object
    * @param string $className Class name of object
    */
    private static function getLastState($objName, $className, $params)
    {
        if(isset($_SESSION[$className][$objName])){
            $obj = unserialize($_SESSION[$className][$objName]);
        } else {
            if( is_null($params) ){
                $obj = new $className($objName);
            } else {
                if( is_array($params) ){
                    $obj = new $className($params,$objName);
                } else {
                    throw new \Exception('Parameters must be array.');
                }
            }
            
        }

        return $obj;
    }

    /**
    * Saves actual object's state
    */
    private function saveState()
    {
        $objName = $this->objName;
        $className = get_class($this);
        $_SESSION[$className][$objName] = serialize($this);
    }

    /**
    * Prevent session fixation attacks
    *
    */
    private function preventSessionFixation(){

        $time = $GLOBALS['config']['preventSessionFixationTime'];

        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = time();
        } else if (time() - $_SESSION['CREATED'] > $time) {
            // session started more than 30 minutes ago
            session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
            $_SESSION['CREATED'] = time();  // update creation time
        }
    }

    /**
    * Check session timeout
    *
    * @throws \Exception if session is timed out
    *
    */
    private function checkTimeout(){
        //in seconds
        $time = $GLOBALS['config']['timeout'];

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $time)) {
            throw new Exception('Session Timeout');
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    }
    
    /**
    * Kill object
    * @static
    */
    public static function kill( &$obj ){
        $objName = $obj->objName;
        $className = get_class($obj);
        $obj->killMe = true;

        if(isset($_SESSION[$className][$objName])){
            unset($_SESSION[$className][$objName]);
        }
    }
}

?>
