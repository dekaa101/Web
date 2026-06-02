<?php

require_once __DIR__ . '/core/Database.php';

$db = Database::getConnection();

$db->exec("DROP TABLE IF EXISTS comments");
$db->exec("DROP TABLE IF EXISTS products");

$db->exec("
    CREATE TABLE IF NOT EXISTS products (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        name        TEXT NOT NULL,
        category    TEXT NOT NULL,
        description TEXT NOT NULL,
        specs       TEXT NOT NULL,
        price       REAL NOT NULL,
        image       TEXT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS comments (
        id          INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id  INTEGER NOT NULL,
        author      TEXT NOT NULL,
        text        TEXT NOT NULL,
        rating      INTEGER DEFAULT 5,
        created_at  TEXT NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id)
    );
");

$products = [
    ['Монитор Samsung 27" 4K', 'Мониторы',
     'Профессиональный 4K монитор с IPS-матрицей. Отличный выбор для работы и игр.',
     "Диагональ: 27 дюймов\nРазрешение: 3840x2160 (4K)\nМатрица: IPS\nЧастота: 144 Гц\nВремя отклика: 1 мс",
     45990,
     '/images/Samsung_27_4K.png'],

    ['Монитор LG UltraWide 34"', 'Мониторы',
     'Ультраширокий монитор для многозадачности и иммерсивного гейминга.',
     "Диагональ: 34 дюйма\nРазрешение: 3440x1440\nМатрица: IPS\nЧастота: 160 Гц\nВремя отклика: 1 мс",
     62990,
     '/images/Монитор_LG_UltraWide_34.png'],

    ['Наушники Sony WH-1000XM5', 'Наушники',
     'Флагманские наушники с лучшим в классе шумоподавлением и высочайшим качеством звука.',
     "Тип: Накладные\nШумоподавление: Да (ANC)\nBluetooth: 5.2\nВремя работы: 30 часов\nМикрофон: Да",
     32990,
     '/images/Sony_WH-1000XM5.png'],

    ['Наушники HyperX Cloud III', 'Наушники',
     'Игровые наушники с объёмным звуком 7.1 и удобной посадкой для долгих сессий.',
     "Тип: Накладные\nИнтерфейс: USB / 3.5мм\nЗвук: Surround 7.1\nМикрофон: Съёмный\nВес: 320г",
     12990,
     '/images/HyperX_Cloud_III.png'],

    ['Клавиатура Logitech MX Keys', 'Клавиатуры',
     'Беспроводная клавиатура для профессионалов с подсветкой и поддержкой нескольких устройств.',
     "Тип: Мембранная\nПодключение: Bluetooth / USB\nПодсветка: Умная подсветка\nКол-во устройств: 3\nАккумулятор: 10 дней",
     12490,
     '/images/Logitech_MX_Keys.png'],

    ['Клавиатура Keychron Q1 Pro', 'Клавиатуры',
     'Механическая беспроводная клавиатура премиум-класса с алюминиевым корпусом.',
     "Тип: Механическая\nПереключатели: Gateron G Pro\nКорпус: Алюминий\nПодключение: Bluetooth / USB-C\nРаскладка: 75%",
     18990,
     '/images/Keychron_Q1_Pro.png'],

    ['Мышь Logitech G Pro X Superlight', 'Мыши',
     'Ультралёгкая игровая мышь весом всего 61г для киберспортсменов.',
     "Вес: 61г\nСенсор: HERO 25K\nDPI: до 25 600\nКнопки: 5\nПодключение: Беспроводное 2.4 ГГц",
     14990,
     '/images/Logitech G Pro X Superlight.png'],

    ['Мышь Razer DeathAdder V3', 'Мыши',
     'Эргономичная игровая мышь с точным сенсором и удобной формой для правой руки.',
     "Вес: 59г\nСенсор: Focus Pro 30K\nDPI: до 30 000\nКнопки: 6\nПодключение: USB",
     8990,
     '/images/Razer_DeathAdder_V3.png'],
];

$stmt = $db->prepare("INSERT INTO products (name, category, description, specs, price, image) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($products as $p) {
    $stmt->execute($p);
}

$comments = [
    [1, 'Алексей',   'Отличный монитор! Цвета яркие, картинка чёткая.', 5],
    [3, 'Дмитрий',   'Лучшие наушники! Шумоподавление просто космос!', 5],
    [5, 'Екатерина', 'Клавиатура удобная, печатать приятно.', 5],
    [7, 'Игорь',     'Мышь лёгкая, не устаёт рука. Идеально!', 5],
];

$stmt = $db->prepare("INSERT INTO comments (product_id, author, text, rating, created_at) VALUES (?, ?, ?, ?, datetime('now'))");
foreach ($comments as $c) {
    $stmt->execute($c);
}

echo "База данных создана! Добавлено " . count($products) . " товаров с фото.\n";