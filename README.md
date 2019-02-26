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
myClass::kill($myObject);
```
## Example

This is an simple example to understand the usage of Object Manager. For each refresh, Andy will play with Buzz and Andy and it will increase his happiness.

I know it seems too simple, but for complex projects this code is is usefull.

```
<?php

//import Object Manager
require_once('object_manager.php');

index();

function index(){

    //Create toys
    $woody = new Toy( 'Woody', '2' );
    $buzz = new Toy( 'Buzz Lightyear', '2' );

    //Kid Parameters
    $params = array(
        'name'      => 'Andy',
        'happiness' => 5,
        'toys'      => array(
            $woody, $buzz
        )
    );

    //Create kid
    $andy = Kid::new( $params,'cuteKid' );

    //Show happiness before play
    echo '<p>Before Play: ' . $andy->getHappiness() . '</p>';

    $andy->play();

    //Show happiness after play
    echo '<p>After play: ' . $andy->getHappiness() . '</p>';

    if( $andy->getHappiness() >= 100 ){
        Kid::kill( $andy );
    }

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
    public function __construct( $params, $objName )
    {
        $this->toys = array();

        foreach ($params as $name => $value) {
            $this->$name = $value;
        }
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
    * Play with toy
    */
    public function play(){
        $toys = $this->toys;

        foreach( $toys as $toy ){
            $this->happiness += $toy->getHappiness();
        }
    }

    /**
    * Show happiness
    */
    public function getHappiness(){
        return $this->happiness;
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

?>
    
```
