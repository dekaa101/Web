<?php

class GreetingController
{
    /**
     * Экшн: /hello/{name}
     * Выводит приветствие
     */
    public function sayHello(string $name): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        return "
            <div class=\"greeting-card\">
                <h2>Привет, {$safeName}!</h2>
                <p>Рады видеть тебя в нашем блоге.</p>
                <a href=\"/bye/{$safeName}\" class=\"btn\">Попрощаться →</a>
            </div>
        ";
    }

    /**
     * Экшн: /bye/{name}
     * Выводит прощание
     */
    public function sayBye(string $name): string
    {
        $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        return "
            <div class=\"greeting-card\">
                <h2>Пока, {$safeName}!</h2>
                <p>Надеемся увидеть тебя снова.</p>
                <a href=\"/hello/{$safeName}\" class=\"btn\">Поздороваться снова →</a>
            </div>
        ";
    }
}