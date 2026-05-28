<?php

require_once __DIR__ . '/../core/Database.php';

class ArticlesController
{
    public function show(string $id): array
    {
        $db = Database::getConnection();

        // Запрос 1: получаем статью
        $stmt = $db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            return [
                'title'   => 'Статья не найдена',
                'content' => '<p>Такой статьи не существует.</p>'
            ];
        }

        // Запрос 2: получаем автора по user_id из статьи
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$article['user_id']]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);

        ob_start(); ?>
        <h2><?= htmlspecialchars($article['title']) ?></h2>
        <p class="author">Автор: <strong><?= htmlspecialchars($author['nickname']) ?></strong></p>
        <p><?= htmlspecialchars($article['content']) ?></p>
        <a href="/article/<?= $article['id'] ?>/edit" class="btn">Редактировать</a>
        <?php
        return [
            'title'   => $article['title'],
            'content' => ob_get_clean()
        ];
    }

// Показать форму редактирования
public function edit(string $id): array
{
    $db = Database::getConnection();

    $stmt = $db->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        return [
            'title'   => 'Статья не найдена',
            'content' => '<p>Такой статьи не существует.</p>'
        ];
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$article['user_id']]);
    $author = $stmt->fetch(PDO::FETCH_ASSOC);

    ob_start(); ?>
    <h2>Редактировать статью</h2>
    <form method="POST" action="/article/<?= $article['id'] ?>/edit">
        <div class="form-group">
            <label>Заголовок:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>">
        </div>
        <div class="form-group">
            <label>Текст:</label>
            <textarea name="content"><?= htmlspecialchars($article['content']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Автор:</label>
            <input type="text" name="author" value="<?= htmlspecialchars($author['nickname']) ?>">
        </div>
        <button type="submit" class="btn">Сохранить</button>
        <a href="/articles/<?= $article['id'] ?>" class="btn btn-cancel">Отмена</a>
    </form>
    <?php
    return [
        'title'   => 'Редактирование: ' . $article['title'],
        'content' => ob_get_clean()
    ];
}

// Сохранить изменения
public function update(string $id): void
{
    $db      = Database::getConnection();
    $title   = $_POST['title']   ?? '';
    $content = $_POST['content'] ?? '';
    $author  = $_POST['author']  ?? '';

    // Обновляем статью
    $stmt = $db->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
    $stmt->execute([$title, $content, $id]);

    // Обновляем nickname автора
    $stmt = $db->prepare("SELECT user_id FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $userId = $stmt->fetchColumn();

    $stmt = $db->prepare("UPDATE users SET nickname = ? WHERE id = ?");
    $stmt->execute([$author, $userId]);

    header("Location: /articles/$id");
    exit;
}
}