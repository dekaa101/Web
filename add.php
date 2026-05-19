<?php
// add.php — модуль добавления новой записи
 
function getAddForm(): string {
    $message = '';
    $row = [
        'surname' => '', 'name' => '', 'lastname' => '',
        'gender'  => '', 'date' => '', 'phone'    => '',
        'location'=> '', 'email'=> '', 'comment'  => '',
    ];
    $button = 'Добавить';
 
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button']) && $_POST['button'] === 'Добавить') {
        // Получаем данные из формы
        foreach ($row as $key => $_) {
            $row[$key] = trim($_POST[$key] ?? '');
        }
 
        if (empty($row['surname']) || empty($row['name'])) {
            $message = '<p class="error">Ошибка: фамилия и имя обязательны.</p>';
        } else {
            try {
                $pdo = getDB();
                $stmt = $pdo->prepare(
                    "INSERT INTO contacts (surname, name, lastname, gender, date, phone, location, email, comment)
                     VALUES (:surname, :name, :lastname, :gender, :date, :phone, :location, :email, :comment)"
                );
                $stmt->execute([
                    ':surname'  => $row['surname'],
                    ':name'     => $row['name'],
                    ':lastname' => $row['lastname'],
                    ':gender'   => $row['gender'],
                    ':date'     => $row['date']     ?: null,
                    ':phone'    => $row['phone'],
                    ':location' => $row['location'],
                    ':email'    => $row['email'],
                    ':comment'  => $row['comment'],
                ]);
                $message = '<p class="success">Запись добавлена.</p>';
                // Сбрасываем поля после успешного добавления
                foreach ($row as $key => $_) $row[$key] = '';
            } catch (PDOException $e) {
                $message = '<p class="error">Ошибка: запись не добавлена.</p>';
            }
        }
    }
 
    ob_start();
    ?>
    <?= $message ?>
    <form name="form_add" method="post">
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
                <input type="date" name="date" value="<?= htmlspecialchars($row['date']) ?>"></div>
            <div class="add"><label>Телефон</label>
                <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($row['phone']) ?>"></div>
            <div class="add"><label>Адрес</label>
                <input type="text" name="location" placeholder="Адрес" value="<?= htmlspecialchars($row['location']) ?>"></div>
            <div class="add"><label>Email</label>
                <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($row['email']) ?>"></div>
            <div class="add"><label>Комментарий</label>
                <textarea name="comment" placeholder="Краткий комментарий"><?= htmlspecialchars($row['comment']) ?></textarea></div>
            <button type="submit" name="button" value="Добавить" class="form-btn">Добавить</button>
        </div>
    </form>
    <?php
    return ob_get_clean();
}