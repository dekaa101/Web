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
$router->get('~^/article/(\d+)/edit$~', fn($id) => (new ArticlesController())->edit($id));
$router->post('~^/article/(\d+)/edit$~', fn($id) => (new ArticlesController())->update($id));
 
$router->dispatch($_SERVER['REQUEST_URI']);
 
// Вспомогательные страницы 
 
function mainPage(): array {
    require_once __DIR__ . '/../core/Database.php';
    $db = Database::getConnection();
    $stmt = $db->query("SELECT articles.*, users.nickname FROM articles JOIN users ON articles.user_id = users.id");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ob_start(); ?>
    <?php foreach ($articles as $article): ?>
        <h2><?= htmlspecialchars($article['title']) ?></h2>
        <p class="author">Автор: <strong><?= htmlspecialchars($article['nickname']) ?></strong></p>
        <p><?= htmlspecialchars($article['content']) ?></p>
        <a href="/article/<?= $article['id'] ?>/edit">Редактировать</a>
        <hr>
    <?php endforeach; ?>
    <?php
    return ['title' => 'Мой блог', 'content' => ob_get_clean()];
}
 
function aboutPage(): string {
    return '<h2>Обо мне</h2><p>Здесь будет информация об авторе блога.</p>';
}
 