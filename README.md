# PSR-11 Simple container
A simple psr-11 compatible container.  
By Florian B. (Alias fkeloks)

## Installation :

With [Composer](https://getcomposer.org/):
```
composer require fkeloks/simple-container
```

## How to use it :

### Declare configuration:

Example:
```php
$configuration = [
    FakeClassA::class => FakeClassA::class,
    FakeClassB::class => FakeClassA::class,
    'FakeC'           => FakeClassC::class,
    'FakeD'           => [
        'class'  => FakeClassD::class,
        'params' => ['A', 'B']
    ]
]

/*
 * get(FakeClassA::class) will return FakeClassA class
 * |-> But this statement is useless, the container will understand automatically.
 *
 * get(FakeClassB::class) will return FakeClassA class
 * |-> Because the configuration overrides the name of the class.
 *
 * get('FakeC') will return FakeClassC class
 *
 * get('FakeD') will return FakeClassD class withs constructor parameters 'A' and 'B'
 */
```

***

### Creating the container from the containerBuilder:

*We consider that $configuration has been instantiated above.*

```php
use SimpleContainer\ContainerBuilder;

ContainerBuilder::build($configuration);
$container = ContainerBuilder::getContainer();
```

```php
use SimpleContainer\ContainerBuilder;

// Short syntax
$container = ContainerBuilder::build($configuration)::getContainer();
```

Once the constructor is built, the container can be retrieved anywhere with:
```php
use SimpleContainer\ContainerBuilder;

$container = ContainerBuilder::getContainer();
// The container will be configured with the previous `ContainerBuilder::build()`
```

If necessary, the container has a `destroy ()` method to destroy the previous `build ()`:
```php
ContainerBuilder::destroy();
```

***

### Get and make methods:

#### Get:
The `get` function will return the desired instance.  
At the first call, the instance will be cached.  
If the instance is present in the cache, the cached instance will be returned.

Examples:
```php
$classA = $container->get('FakeA');
$classA = $container->get(FakeClassB::class);
```

### Make:
The `make` function returns the desired instance.  
This function does not take into account the cache in the return of the instance.  
However, the returned instance will still be cached for the next `get ()`

Examples:
```php
$classB = $container->make('FakeB');
$classC = $container->make(FakeClassC::class);
```

## Examples:

Example 1:
```php
class Hello() {

    private $name;
    private $pseudo;

    public function __construct($name, $pseudo) {
        $this->name   = $name;
        $this->pseudo = $pseudo;
    }

    public function sayHello() {
        return "Hello {$this->pseudo} ({$this->name}) !";
    }
}

SimpleContainer\ContainerBuilder::build([
    'helloClass' => [
        'class'  => Hello::class,
        'params' => ['James', 'Jojo']
    ]
])

$container = SimpleContainer\ContainerBuilder::getContainer();

$helloClass = $container->get('helloClass');
echo $helloClass->sayHello();

/*
 * The result will be: "Hello Jojo (James) !"
 */
```

Example 2:
```php
SimpleContainer\ContainerBuilder::build([
    'classA' => ClassA::class
])

$container = SimpleContainer\ContainerBuilder::getContainer();

$classA = $container->get('classA'); // A new instance of ClassA is generated
$classA = $container->get('classA'); // Class A has been cached, so the same instance is returned

$classA = $container->make('classA');
// |-> The make function does not take the cache into account, so a new instance of A is generated
```

## Further information

In the kind of configuration:
```php
SimpleContainer\ContainerBuilder::build([
    'helloClass' => [
        'class'  => Hello::class,
        'params' => ['James', 'Jojo']
    ]
])
```
The constructor will receive 2 distinct variables. ('James', and 'Jojo')  
Whatever type of variable to pass to the constructor, `params` must be an array.

Examples:
```php
'params' => ['yeah']     // string
'params' => [true]       // boolean
'params' => [['A', 'B']] // array
'params' => [123456789]  // integer
```