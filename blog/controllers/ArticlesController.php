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
        <?php
        return [
            'title'   => $article['title'],
            'content' => ob_get_clean()
        ];
    }
}