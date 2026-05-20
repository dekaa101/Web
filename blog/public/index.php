<?php
 
require_once __DIR__ . '/../core/router.php';
require_once __DIR__ . '/../controllers/GreetingController.php';
require_once __DIR__ . '/../controllers/ArticlesController.php';
 
$router = new Router();
 
// Роуты
$router->get('/',           fn() => mainPage());
$router->get('/about-me',   fn() => aboutPage());
$router->get('/hello/{name}', fn($name) => (new GreetingController())->sayHello($name));
$router->get('/bye/{name}',   fn($name) => (new GreetingController())->sayBye($name));
$router->get('/articles/{id}', fn($id) => (new ArticlesController())->show($id));
 
$router->dispatch($_SERVER['REQUEST_URI']);
 
// Вспомогательные страницы 
 
function mainPage(): string {
    ob_start(); ?>
    <h2>Печенья</h2>
    <p>Всем хелоу, я сегодня съел Oreo</p>
    <hr>
    <h2>Статья 2</h2>
    <p>Текс для второй статьи</p>
    <?php return ob_get_clean();
}
 
function aboutPage(): string {
    return '<h2>Обо мне</h2><p>Здесь будет информация об авторе блога.</p>';
}
 