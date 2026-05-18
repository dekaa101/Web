<?php
// calc.php — принимает POST['expression'], вычисляет, возвращает результат
 
// 1. Получение и валидация входных данных

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['expression'])) {
    http_response_code(400);
    echo 'Ошибка: нет данных';
    exit;
}
 
$raw = trim($_POST['expression']);
 
// Разрешённые символы: цифры, . + - * / ( ) пробел
if (!preg_match('/^[0-9\.\+\-\*\/\(\)\s]+$/', $raw)) {
    echo 'Ошибка: недопустимые символы';
    exit;
}
 
// Убираем пробелы
$expression = preg_replace('/\s+/', '', $raw);
 
if ($expression === '') {
    echo 'Ошибка: пустое выражение';
    exit;
}
 
 
$pos = 0; // глобальный указатель на текущий символ
 
function parseExpr(string $str, int &$pos): float {
    $result = parseTerm($str, $pos);
    while ($pos < strlen($str) && ($str[$pos] === '+' || $str[$pos] === '-')) {
        $op = $str[$pos++];
        $right = parseTerm($str, $pos);
        $result = add_sub($result, $right, $op);
    }
    return $result;
}
 
function parseTerm(string $str, int &$pos): float {
    $result = parseFactor($str, $pos);
    while ($pos < strlen($str) && ($str[$pos] === '*' || $str[$pos] === '/')) {
        $op = $str[$pos++];
        $right = parseFactor($str, $pos);
        $result = mul_div($result, $right, $op);
    }
    return $result;
}
 
function parseFactor(string $str, int &$pos): float {
    // Унарный минус
    if ($pos < strlen($str) && $str[$pos] === '-') {
        $pos++;
        return negate(parseFactor($str, $pos));
    }
    // Скобки
    if ($pos < strlen($str) && $str[$pos] === '(') {
        $pos++; // пропускаем '('
        $result = parseExpr($str, $pos);
        if ($pos < strlen($str) && $str[$pos] === ')') {
            $pos++; // пропускаем ')'
        } else {
            throw new Exception('Ошибка: не закрыта скобка');
        }
        return $result;
    }
    // Число
    return parseNumber($str, $pos);
}
 
function parseNumber(string $str, int &$pos): float {
    $start = $pos;
    while ($pos < strlen($str) && (ctype_digit($str[$pos]) || $str[$pos] === '.')) {
        $pos++;
    }
    if ($pos === $start) {
        throw new Exception("Ошибка: ожидалось число на позиции $pos");
    }
    return (float) substr($str, $start, $pos - $start);
}
 
// 3. Пользовательские функции для операций
 
function add_sub(float $a, float $b, string $op): float {
    if ($op === '+') return $a + $b;
    return $a - $b;
}
 
function mul_div(float $a, float $b, string $op): float {
    if ($op === '*') return $a * $b;
    if ($b == 0) throw new Exception('Ошибка: деление на ноль');
    return $a / $b;
}
 
function negate(float $a): float {
    return -$a;
}
 
// 4. Вычисление и вывод результата
try {
    $result = parseExpr($expression, $pos);
 
    if ($pos !== strlen($expression)) {
        throw new Exception('Ошибка: некорректное выражение');
    }
 
    // Форматируем: убираем лишние нули у дробей
    $output = (fmod($result, 1.0) == 0.0)
        ? number_format($result, 0, '.', '')
        : rtrim(number_format($result, 10, '.', ''), '0');
 
    // Вернуть результат (через GET в URL можно при редиректе,
    // но для fetch-запроса проще отдать прямо в body)
    echo $output;
 
} catch (Exception $e) {
    echo $e->getMessage();
}