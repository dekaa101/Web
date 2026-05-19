<?php
// index.php — единственный загружаемый в браузер документ
 
require_once 'db.php';
require_once 'menu.php';
require_once 'viewer.php';
require_once 'add.php';
require_once 'edit.php';
require_once 'delete.php';
 
$page = isset($_GET['page']) ? $_GET['page'] : 'view';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$p    = isset($_GET['p'])    ? (int)$_GET['p'] : 1;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записная книжка</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lemonada:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
 
<header>
    <?= getMenu($page) ?>
</header>
 
<?php if ($page === 'view'): ?>
<div class="submenu">
    <a href="index.php?page=view&sort=id"   class="<?= $sort === 'id'   ? 'select' : '' ?>">По порядку добавления</a>
    <a href="index.php?page=view&sort=surname" class="<?= $sort === 'surname' ? 'select' : '' ?>">По фамилии</a>
    <a href="index.php?page=view&sort=date" class="<?= $sort === 'date' ? 'select' : '' ?>">По дате рождения</a>
</div>
<?php endif; ?>
 
<main>
<?php
switch ($page) {
    case 'view':
        echo getViewer($sort, $p);
        break;
    case 'add':
        echo getAddForm();
        break;
    case 'edit':
        echo getEditForm();
        break;
    case 'delete':
        echo getDeletePage();
        break;
    default:
        echo getViewer($sort, $p);
}
?>
</main>
 
<footer></footer>
</body>
</html>
 