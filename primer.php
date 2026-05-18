<?php
/**
 * Вариант: 22 * X = 220
 * Программа определяет оператор и расположение неизвестной переменной, затем находит значение X.
 */
// 1. Исходное уроынение
$equation = "22 * X = 220";

echo "  Решение уравнения\n";
echo "\n";
echo "Уравнение: $equation\n\n";
 
// 2. Парсинг уровнения
 
/* Определяет оператор в уравнении */
function detectOperator(string $leftPart): string {
    if (strpos($leftPart, '*') !== false) return '*';
    if (strpos($leftPart, '/') !== false) return '/';
    if (strpos($leftPart, '+') !== false) return '+';
    if (strpos($leftPart, '-') !== false) return '-';
    return '?';
}
 
/**
 * Определяет расположение переменной X
 * Возвращает: 'left' если X слева от оператора, 'right' если справа
 */
function detectXPosition(string $leftPart, string $operator): string {
    $parts = explode($operator, $leftPart);
    $leftOperand  = trim($parts[0]);
    $rightOperand = trim($parts[1]);
 
    if (strtoupper($leftOperand) === 'X') {
        return 'left';
    } elseif (strtoupper($rightOperand) === 'X') {
        return 'right';
    }
    return 'unknown';
}
 
/* Решает уравнение вида: A op X = C  или  X op A = C */
function solveEquation(float $a, string $operator, float $c, string $xPosition): float|string {
    if ($xPosition === 'right') {
        // A op X = C  =>  X = ?
        switch ($operator) {
            case '*': return ($a != 0) ? $c / $a : 'Ошибка: деление на ноль';
            case '/': return $a * $c;
            case '+': return $c - $a;
            case '-': return $a - $c;
        }
    } elseif ($xPosition === 'left') {
        // X op A = C  =>  X = ?
        switch ($operator) {
            case '*': return ($a != 0) ? $c / $a : 'Ошибка: деление на ноль';
            case '/': return ($a != 0) ? $c * $a : 'Ошибка: деление на ноль';
            case '+': return $c - $a;
            case '-': return $c + $a;
        }
    }
    return 'Ошибка: неизвестная позиция X';
}
 
// 3. Основ логика 

// Разбиваем уравнение на левую и правую часть
$sides = explode('=', $equation);
$leftPart  = trim($sides[0]);   // "22 * X"
$rightPart = trim($sides[1]);   // "220"
$C = (float)$rightPart;
 
// Определяем оператор
$operator = detectOperator($leftPart);
 
// Определяем позицию X
$xPosition = detectXPosition($leftPart, $operator);
 
// Получаем числовой операнд
$parts = explode($operator, $leftPart);
$leftOperand  = trim($parts[0]);
$rightOperand = trim($parts[1]);
$A = (strtoupper($leftOperand) === 'X') ? (float)$rightOperand : (float)$leftOperand;
 
// Решаем уравнение
$result = solveEquation($A, $operator, $C, $xPosition);
 
// 4. Выводим результаты
 
$operatorNames = [
    '*' => 'умножение',
    '/' => 'деление',
    '+' => 'сложение',
    '-' => 'вычитание',
];
 
$positionNames = [
    'left'  => 'слева от оператора',
    'right' => 'справа от оператора',
];
 
echo " Анализ уравнения \n";
echo "Левая часть  : $leftPart\n";
echo "Правая часть : $rightPart\n\n";
 
echo " Определение оператора \n";
echo "Оператор     : '$operator' ({$operatorNames[$operator]})\n\n";
 
echo " Определение позиции X \n";
echo "Переменная X : {$positionNames[$xPosition]}\n\n";
 
echo " Решение \n";
if (is_numeric($result)) {
    echo "Формула решения: X = C / A = $C / $A\n";
    echo "Ответ: X = $result\n\n";
 
    // Проверка
    $check = ($xPosition === 'right') ? ($A . " $operator $result") : ("$result $operator $A");
    echo "--- Проверка ---\n";
    echo "$check = $C\n";
    echo "Проверка пройдена: " . (eval("return $check;") == $C ? "ДА ✓" : "НЕТ ✗") . "\n";
} else {
    echo "Результат: $result\n";
}
 