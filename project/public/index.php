<?php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../controllers/ProductsController.php';

$router = new Router();

// Каталог
$router->get('/',      fn() => (new ProductsController())->index());
$router->get('/about', fn() => aboutPage());

// Товар
$router->get('~^/product/(\d+)$~',          fn($id) => (new ProductsController())->show($id));
$router->post('~^/product/(\d+)/comment$~',  fn($id) => (new ProductsController())->addComment($id));

// Удаление комментария
$router->post('~^/comment/(\d+)/delete$~',   fn($id) => (new ProductsController())->deleteComment($id));

// Калькулятор — показать страницу
$router->get('/calc', fn() => (new ProductsController())->simpleCalculator());

// Калькулятор — вычислить (POST)
$router->post('/calc-compute', fn() => (new ProductsController())->computeCalc());

$router->dispatch($_SERVER['REQUEST_URI']);

function aboutPage(): array {
    ob_start(); ?>
    <div class="about-page">
        <h2>О нас</h2>
        <p>TechShop — интернет-магазин электроники. Мы продаём мониторы, наушники, клавиатуры и другую технику с 2020 года.</p>
        <p>У нас вы найдёте широкий ассортимент товаров от ведущих производителей по конкурентным ценам.</p>
        <p>Все товары сертифицированы и имеют официальную гарантию.</p>
        <a href="/" class="btn">Перейти в каталог</a>
    </div>
    <?php
    return ['title' => 'О нас — TechShop', 'content' => ob_get_clean()];
}