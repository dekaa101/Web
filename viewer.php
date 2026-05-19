<?php
// viewer.php — модуль вывода содержимого базы данных
 
function getViewer(string $sort = 'id', int $currentPage = 1): string {
    $pdo = getDB();
 
    // Допустимые поля сортировки (защита от SQL-инъекций)
    $allowedSorts = ['id', 'surname', 'date'];
    if (!in_array($sort, $allowedSorts)) {
        $sort = 'id';
    }
 
    $perPage = 10;
 
    // Общее количество записей
    $total = (int)$pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
 
    if ($total === 0) {
        return '<p>В базе данных нет записей.</p>';
    }
 
    $totalPages = (int)ceil($total / $perPage);
    if ($currentPage < 1) $currentPage = 1;
    if ($currentPage > $totalPages) $currentPage = $totalPages;
 
    $offset = ($currentPage - 1) * $perPage;
 
    $stmt = $pdo->prepare(
        "SELECT * FROM contacts ORDER BY {$sort} ASC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue(':limit',  $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,  PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    // Заголовки таблицы
    $html  = '<table>';
    $html .= '<thead><tr>';
    foreach (['#', 'Фамилия', 'Имя', 'Отчество', 'Пол', 'Дата рождения', 'Телефон', 'Адрес', 'Email', 'Комментарий'] as $h) {
        $html .= '<th>' . $h . '</th>';
    }
    $html .= '</tr></thead><tbody>';
 
    foreach ($rows as $i => $row) {
        $html .= '<tr>';
        $html .= '<td>' . ($offset + $i + 1) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['surname'])  . '</td>';
        $html .= '<td>' . htmlspecialchars($row['name'])     . '</td>';
        $html .= '<td>' . htmlspecialchars($row['lastname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender'])   . '</td>';
        $html .= '<td>' . htmlspecialchars($row['date'])     . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone'])    . '</td>';
        $html .= '<td>' . htmlspecialchars($row['location']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email'])    . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment'])  . '</td>';
        $html .= '</tr>';
    }
 
    $html .= '</tbody></table>';
 
    // Пагинация
    if ($totalPages > 1) {
        $html .= '<div class="pagination">';
        for ($pg = 1; $pg <= $totalPages; $pg++) {
            $active = ($pg === $currentPage) ? ' class="select"' : '';
            $html .= '<a href="index.php?page=view&sort=' . urlencode($sort) . '&p=' . $pg . '"' . $active . '>' . $pg . '</a>';
        }
        $html .= '</div>';
    }
 
    return $html;
}
 