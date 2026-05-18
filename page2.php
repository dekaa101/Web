<?php
function get_headers_list() {
    $headers = [];
    foreach ($_SERVER as $key => $value) {
        if (strpos($key, 'HTTP_') === 0) {
            $name = str_replace('_', '-', substr($key, 5));
            $headers[$name] = $value;
        }
    }
    return $headers;
}
 
$headers = get_headers_list();
 
$output = '';
foreach ($headers as $key => $value) {
    $output .= $key . ': ' . $value . "\n";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Lab2</title>
</head>
<body>
 
<header>
  <img src="images/logo3.png" alt="МосПолитех" height="60">
  <h2 style="text-align:center">Заголовки запроса</h2>
</header>
 
<hr>
 
<main>
  <h3>Результат get_headers():</h3>
  <textarea rows="15" cols="60"><?php echo htmlspecialchars($output); ?></textarea>
</main>
 
<hr>
 
<footer>
  <p>Задание для самостоятельной работы: Feedback Form</p>
</footer>
 
</body>
</html>