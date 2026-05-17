<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello, World!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .logo img {
            height: 50px;
            width: auto;
            display: block;
        }
        .title {
            font-size: 1.5rem;
            font-weight: bold;
            padding-left: 300px;
            flex-grow: 1;
            margin: 0 1rem;
        }
        main {
            flex: 1;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            text-align: center;
            padding: 1rem;
            font-size: 1rem;
        }
        @media (max-width: 600px) {
            header { flex-direction: column; gap: 0.5rem; }
            .title { margin: 0.5rem 0; }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo3.png" alt="Логотип МосПолитеха">
        </div>
        <div class="title">Lab1</div>
    </header>

    <main>
        <div class="dynamic-content">
            <?php
                // Динамический контент
                $hello = "Hello, World!";
                $currentDateTime = date("d.m.Y H:i:s");
                $randomValue = rand(1, 100);
                
                // Формируем вывод
                echo "<h2>$hello</h2>";
                echo "<p> Текущие дата и время: <strong>$currentDateTime</strong></p>";
                echo "<p> Случайное число от 1 до 100: <strong>$randomValue</strong></p>";
            ?>
        </div>
    </main>

    <footer>
       Задание для самостоятельной работы
    </footer>
</body>
</html>