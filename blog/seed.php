<?php

require_once __DIR__ . '/core/Database.php';

$db = Database::getConnection();

// Создаём таблицы
$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY,
        nickname TEXT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS articles (
        id INTEGER PRIMARY KEY,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        user_id INTEGER,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );
");

// Заполняем тестовыми данными
$db->exec("
    INSERT OR IGNORE INTO users (id, nickname) VALUES (1, 'Mr.Beast');
    INSERT OR IGNORE INTO users (id, nickname) VALUES (2, 'Иван');

    INSERT OR IGNORE INTO articles (id, title, content, user_id)
    VALUES (1, 'Статья 1', 'Текст первой статьи', 1);

    INSERT OR IGNORE INTO articles (id, title, content, user_id)
    VALUES (2, 'Статья 2', 'Текст второй статьи', 2);
");

echo "База данных создана и заполнена!";