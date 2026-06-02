<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Kursach' ?></title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="/" class="logo">TechShop</a>
        <nav>
            <a href="/">Каталог</a>
            <a href="/calc">Калькулятор</a>
            <a href="/about">О нас</a>
        </nav>
    </div>
</header>

<main class="main-content">
    <?= $content ?>
</main>

<footer class="site-footer">
    <p>© 2025 TechShop — Магазин электроники. Все права защищены.</p>
</footer>

</body>
</html>