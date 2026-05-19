<?php
// db.php — подключение к базе данных
 
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'notebook');
 
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die('Ошибка подключения к базе данных: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}
 
// Создание таблицы при первом запуске
try {
    $pdo = getDB();
    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id       INT AUTO_INCREMENT PRIMARY KEY,
        surname  VARCHAR(100) NOT NULL,
        name     VARCHAR(100) NOT NULL,
        lastname VARCHAR(100),
        gender   VARCHAR(10),
        date     DATE,
        phone    VARCHAR(30),
        location VARCHAR(255),
        email    VARCHAR(100),
        comment  TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
} catch (PDOException $e) {
    // Тихо игнорируем — таблица уже существует или нет доступа
}