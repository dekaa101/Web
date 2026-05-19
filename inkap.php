<?php
 
class Cat
{
    private string $name;
    private string $color;
 
    public function __construct(string $name, string $color)
    {
        $this->name  = $name;
        $this->color = $color;
    }
 
    public function getColor(): string
    {
        return $this->color;
    }
 
    public function sayHello(): string
    {
        return 'Мяу! Меня зовут ' . $this->name . '. Я ' . $this->color . ' кошка.';
    }
}
 
$cat1 = new Cat('Rex', 'рыжая');
$cat2 = new Cat('Best', 'белая');
 
echo $cat1->sayHello() . PHP_EOL;
echo $cat2->sayHello() . PHP_EOL;
 
echo 'Цвет кошки: ' . $cat1->getColor() . PHP_EOL;
 