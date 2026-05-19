<?php
// delete.php — модуль удаления записи

function getDeletePage(): string {
    $pdo = getDB();
    $message = '';

    // Обработка удаления
    if (isset($_GET['del'])) {
        $delId = (int)$_GET['del'];
        $stmt = $pdo->prepare("SELECT surname FROM contacts WHERE id = :id");
        $stmt->execute([':id' => $delId]);
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rec) {
            try {
                $pdo->prepare("DELETE FROM contacts WHERE id = :id")->execute([':id' => $delId]);
                $message = '<p class="success">Запись с фамилией ' . htmlspecialchars($rec['surname']) . ' удалена.</p>';
            } catch (PDOException $e) {
                $message = '<p class="error">Ошибка при удалении записи.</p>';
            }
        } else {
            $message = '<p class="error">Запись не найдена.</p>';
        }
    }

    $contacts = $pdo->query("SELECT id, surname, name, lastname FROM contacts ORDER BY surname, name")->fetchAll(PDO::FETCH_ASSOC);

    ob_start();
    echo $message;

    if (empty($contacts)) {
        echo '<p>В базе данных нет записей.</p>';
    } else {
        echo '<div class="div-edit" style="margin:20px auto; width:300px; text-align:left;">';
        foreach ($contacts as $c) {
            // Инициалы: первая буква имени + первая буква отчества
            $initials = mb_substr($c['name'], 0, 1) . '.';
            if (!empty($c['lastname'])) {
                $initials .= mb_substr($c['lastname'], 0, 1) . '.';
            }
            $label = htmlspecialchars($c['surname'] . ' ' . $initials);
            echo '<div><a href="index.php?page=delete&del=' . $c['id'] . '" '
               . 'onclick="return confirm(\'Удалить запись ' . $label . '?\')">'
               . $label . '</a></div>';
        }
        echo '</div>';
    }

    return ob_get_clean();
}