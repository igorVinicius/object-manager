# object-manager
## PHP project to improve OOP (Object-Oriented Programming).

## Description
This class help us to use real OOP on PHP. It stores objects within the session and maintains the state in memory.

## IMPORTANT
If other objects is attributes of the object that you're extending with ObjectManager class, they automatically will be save too.

Other thing: Objects that use File Handlers won't must be saved, so take care!

## Usage

1. Extends this class inside the class that you want to persist.
2. Use that constructor structure:
```
public function __construct($params, $objName)
{
    // Your code here
    
    foreach( $params as $name => $value ){
        $this->$name = $value;
    }
    parent::__construct($objName);
}
```

3. Use that destructor structure:
```
public function __destruct()
{
       // Your code here
       parent::__destruct();
}
```
4. To create object, use 
```
$myVar = MyClass::new('myVarName');
```
Where 'myVarName' is 'myVar' string on '$myVar'

5. To kill object, use 

```
myClass::kill($myObject)
```
## Example

This is an simple example to understand the usage of Object Manager.

```
//import Object Manager
require_once('object_manager.php');

function index(){

    //Kid Parameters
    $params = array(
        'name'      : 'Andy',
        'happiness' : 5
    );

    //Create kid    
    $andy = Kid::new( $params,'cuteKid' );
    
    //Create toys
    $woody = new Toy( 'Woody', '2' );
    $buzz = new Toy( 'Buzz Lightyear', '2' );
    
    //Add Andy toys
    $andy->addToy( $woody );
    $andy->addToy( $buzz );
    
    //Show happiness before play
    echo 'Before Play: ' . $andy->getHappiness();
    
    $andy->play();
    
    //Show happiness after play
    echo 'After play: ' . $andy->getHappiness();
    
    //Note 1: Happiness will increase after each
    //Note 2: Toys 'woody' and 'buzz' is attributes of Kid, so it means that the state will be saved automaticaly
}

/**
* Class description
*/
class Kid extends ObjectManager
{

    /**
    * @var string  
    */
    private $name;

    /**
    * @var array Object  
    */
    private $toys;
    
    /**
    * @var int  
    */
    private $happiness;
    
    /**
    * Class constructor
    */
    public function __construct( $name, $happiness, $objName )
    {
        $this->toys = array();
        parent::__construct($objName);
    }
    
    /**
    * Class destructor
    */    
    public function __destruct()
    {
        parent::__destruct();
    }
    
    /**
    * Add toys
    */
    public function addToy( $toy ){
        array_push( $this->toys, $toy );
    }
    
    /**
    * Play with toy
    */
    public function play(){
        $toys = $this->toys;
        
        foreach( $toys as $toy ){
            $this->happiness += $toy->getHappiness;
        }
    }
    
    /**
    * Show happiness
    */
    public function getHappiness(){
        echo $this->happiness;
    }
}

/**
* Class description
*/

class Toy 
{
    /**
    * @var string  
    */
    private $name;
    
    /**
    * @var int
    */
    private $amountOfHappiness;
    
    /**
    * Class constructor
    */
    public function __construct( $name, $amountOfHappiness)
    {
        $this->name = $name;
        $this->amountOfHappiness = $amountOfHappiness;
    }
    
    /**
    * Get happiness produced by toy
    * @return int
    */
    public function getHappiness(){
        return $this->amountOfHappiness;
    }
}
    
```
