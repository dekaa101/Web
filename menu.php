<?php
// menu.php — модуль формирования меню
 
function getMenu(string $activePage = 'view'): string {
    $items = [
        'view'   => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи',
    ];
 
    $html = '';
    foreach ($items as $key => $label) {
        $class = ($activePage === $key) ? ' class="select"' : '';
        $html .= '<a href="index.php?page=' . $key . '"' . $class . '>' . $label . '</a>';
    }
    return $html;
}
 