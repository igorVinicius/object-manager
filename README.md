# object-manager
## PHP project to improve OOP (Object-Oriented Programming).

##Description
This class help us to use real OOP on PHP. It stores objects within the session and maintains the state in memory.

##IMPORTANT
If other objects is attributes of the object that you're extending with ObjectManager class, they automatically will be save too.

##IMPORTANT 2 Objects that use File Handlers won't must be saved, so take care!

##Usage

1. Extends this class inside the class that you want to persist.
2. Use that constructor structure:
```
public function __construct($objName)
{
    // Your code here
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
