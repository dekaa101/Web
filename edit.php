<?php
// edit.php — модуль редактирования записи

function getEditForm(): string {
    $pdo = getDB();
    $message = '';

    // Получаем список всех контактов для боковой панели
    $allContacts = $pdo->query("SELECT id, surname, name FROM contacts ORDER BY surname, name")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($allContacts)) {
        return '<p>В базе данных нет записей для редактирования.</p>';
    }

    // Определяем текущую запись
    $currentId = isset($_GET['id']) ? (int)$_GET['id'] : $allContacts[0]['id'];

    // Обработка POST-запроса (сохранение изменений)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button']) && $_POST['button'] === 'Сохранить') {
        $postId = (int)($_POST['id'] ?? 0);
        $fields = ['surname','name','lastname','gender','date','phone','location','email','comment'];
        $data = [];
        foreach ($fields as $f) {
            $data[$f] = trim($_POST[$f] ?? '');
        }

        if (empty($data['surname']) || empty($data['name'])) {
            $message = '<p class="error">Ошибка: фамилия и имя обязательны.</p>';
        } else {
            try {
                $stmt = $pdo->prepare(
                    "UPDATE contacts SET surname=:surname, name=:name, lastname=:lastname,
                     gender=:gender, date=:date, phone=:phone, location=:location,
                     email=:email, comment=:comment WHERE id=:id"
                );
                $stmt->execute([
                    ':surname'  => $data['surname'],
                    ':name'     => $data['name'],
                    ':lastname' => $data['lastname'],
                    ':gender'   => $data['gender'],
                    ':date'     => $data['date'] ?: null,
                    ':phone'    => $data['phone'],
                    ':location' => $data['location'],
                    ':email'    => $data['email'],
                    ':comment'  => $data['comment'],
                    ':id'       => $postId,
                ]);
                $message = '<p class="success">Запись обновлена.</p>';
                $currentId = $postId;
                // Обновляем список контактов
                $allContacts = $pdo->query("SELECT id, surname, name FROM contacts ORDER BY surname, name")->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $message = '<p class="error">Ошибка: запись не обновлена.</p>';
            }
        }
    }

    // Загружаем текущую запись
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->execute([':id' => $currentId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Если запись не найдена — берём первую
    if (!$row) {
        $currentId = $allContacts[0]['id'];
        $stmt->execute([':id' => $currentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    ob_start();
    ?>
    <div style="display:flex; justify-content:center; gap:30px; align-items:flex-start; margin-top:20px;">
        <!-- Список контактов -->
        <div class="div-edit">
            <?php foreach ($allContacts as $contact): ?>
                <div class="<?= $contact['id'] == $currentId ? 'currentRow' : '' ?>">
                    <a href="index.php?page=edit&id=<?= $contact['id'] ?>">
                        <?= htmlspecialchars($contact['surname'] . ' ' . $contact['name']) ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Форма редактирования -->
        <div>
            <?= $message ?>
            <form name="form_edit" method="post" action="index.php?page=edit&id=<?= $currentId ?>">
                <input type="hidden" name="id" value="<?= $currentId ?>">
                <div class="column">
                    <div class="add"><label>Фамилия</label>
                        <input type="text" name="surname" placeholder="Фамилия" value="<?= htmlspecialchars($row['surname']) ?>"></div>
                    <div class="add"><label>Имя</label>
                        <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($row['name']) ?>"></div>
                    <div class="add"><label>Отчество</label>
                        <input type="text" name="lastname" placeholder="Отчество" value="<?= htmlspecialchars($row['lastname']) ?>"></div>
                    <div class="add"><label>Пол</label>
                        <select name="gender">
                            <option value="">— выберите —</option>
                            <option value="мужской" <?= $row['gender']==='мужской' ? 'selected' : '' ?>>мужской</option>
                            <option value="женский" <?= $row['gender']==='женский' ? 'selected' : '' ?>>женский</option>
                        </select></div>
                    <div class="add"><label>Дата рождения</label>
                        <input type="date" name="date" value="<?= htmlspecialchars($row['date'] ?? '') ?>"></div>
                    <div class="add"><label>Телефон</label>
                        <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($row['phone']) ?>"></div>
                    <div class="add"><label>Адрес</label>
                        <input type="text" name="location" placeholder="Адрес" value="<?= htmlspecialchars($row['location']) ?>"></div>
                    <div class="add"><label>Email</label>
                        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($row['email']) ?>"></div>
                    <div class="add"><label>Комментарий</label>
                        <textarea name="comment" placeholder="Краткий комментарий"><?= htmlspecialchars($row['comment']) ?></textarea></div>
                    <button type="submit" name="button" value="Сохранить" class="form-btn">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    return ob_get_clean();
}