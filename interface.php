<?php
 
interface CalculateSquare
{
    public function calculateSquare(): float;
}
 
class Circle implements CalculateSquare
{
    public function __construct(private float $radius) {}
 
    public function calculateSquare(): float
    {
        return M_PI * $this->radius ** 2;
    }
}
 
class Rectangle implements CalculateSquare
{
    public function __construct(private float $width, private float $height) {}
 
    public function calculateSquare(): float
    {
        return $this->width * $this->height;
    }
}
 
// Класс, который НЕ реализует интерфейс
class Triangle
{
    public function __construct(private float $base, private float $height) {}
}
 
function printSquare(object $object): void
{
    if ($object instanceof CalculateSquare) {
        $className = get_class($object);
        echo 'Объект класса ' . $className . '. Площадь: ' . $object->calculateSquare() . PHP_EOL;
    } else {
        echo 'Объект класса ' . get_class($object) . ' не реализует интерфейс CalculateSquare.' . PHP_EOL;
    }
}
 
$circle    = new Circle(5);
$rectangle = new Rectangle(4, 6);
$triangle  = new Triangle(3, 8);
 
printSquare($circle);
printSquare($rectangle);
printSquare($triangle);
 