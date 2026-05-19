<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Мой блог' ?></title>
    <link rel="stylesheet" href="/styles/styles.css">
</head>
<body>
 
<table class="layout">
    <tr>
        <td colspan="2" class="header">
            Мой блог
        </td>
    </tr>
    <tr>
        <td>
            <?= $content ?>
        </td>
 
        <td width="300px" class="sidebar">
            <div class="sidebarHeader">Меню</div>
            <ul>
                <li><a href="/">Главная страница</a></li>
                <li><a href="/about-me">Обо мне</a></li>
            </ul>
            <div class="sidebarHeader" style="margin-top:20px;">Попробовать</div>
            <ul>
                <li><a href="/hello/Mr.Beast">Поздороваться с Mr.Beast</a></li>
                <li><a href="/bye/Mr.Beast">Попрощаться с Mr.Beast</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="footer" colspan="2">Все права защищены (c) Мой блог</td>
    </tr>
</table>
 
</body>
</html>