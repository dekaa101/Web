<?php

require_once __DIR__ . '/../core/Database.php';

class ProductsController
{
    public function index(): array
    {
        $db = Database::getConnection();
        $category = $_GET['category'] ?? '';
        $search   = $_GET['search']   ?? '';
        $sql    = "SELECT * FROM products WHERE 1=1";
        $params = [];
        if ($category) { $sql .= " AND category = ?"; $params[] = $category; }
        if ($search) { $sql .= " AND (name LIKE ? OR description LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
        $sql .= " ORDER BY id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $categories = $db->query("SELECT DISTINCT category FROM products ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
        ob_start(); ?>
        <div class="page-header"><h2>Каталог товаров</h2></div>
        <form method="GET" action="/" class="filter-form">
            <input type="text" name="search" placeholder="Поиск товара..." value="<?= htmlspecialchars($search) ?>">
            <select name="category">
                <option value="">Все категории</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $category ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Найти</button>
            <?php if ($category || $search): ?><a href="/" class="btn btn-cancel">Сбросить</a><?php endif; ?>
        </form>
        <div class="products-grid">
            <?php if (empty($products)): ?>
                <p>Товары не найдены.</p>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="product-card">
                        <a href="/product/<?= $p['id'] ?>">
                            <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="product-img">
                        </a>
                        <div class="product-card-body">
                            <div class="product-category"><?= htmlspecialchars($p['category']) ?></div>
                            <h3><a href="/product/<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></a></h3>
                            <p class="product-desc"><?= htmlspecialchars(substr($p['description'], 0, 70)) ?>...</p>
                            <div class="product-footer">
                                <span class="product-price"><?= number_format($p['price'], 0, '.', ' ') ?> ₽</span>
                                <a href="/product/<?= $p['id'] ?>" class="btn">Подробнее</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
        return ['title' => 'TechShop — Каталог', 'content' => ob_get_clean()];
    }

    public function show(string $id): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) return ['title' => 'Товар не найден', 'content' => '<p>Такого товара не существует.</p>'];
        $stmt = $db->prepare("SELECT * FROM comments WHERE product_id = ? ORDER BY created_at DESC");
        $stmt->execute([$id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ob_start(); ?>
        <div class="product-detail">
            <a href="/" class="back-link">← Назад в каталог</a>
            <div class="product-detail-header">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-detail-img">
                <div class="product-detail-info">
                    <span class="product-category"><?= htmlspecialchars($product['category']) ?></span>
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <p class="product-full-desc"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <div class="product-specs">
                        <strong>Характеристики:</strong>
                        <p><?= nl2br(htmlspecialchars($product['specs'])) ?></p>
                    </div>
                    <div class="product-buy-box">
                        <div class="big-price"><?= number_format($product['price'], 0, '.', ' ') ?> ₽</div>
                        <a href="/calc" class="btn">Калькулятор</a>
                    </div>
                </div>
            </div>
            <div class="comments-section">
                <h3>Отзывы (<?= count($comments) ?>)</h3>
                <form method="POST" action="/product/<?= $product['id'] ?>/comment" class="comment-form">
                    <div class="form-group"><input type="text" name="author" placeholder="Ваше имя" required></div>
                    <div class="form-group"><textarea name="text" placeholder="Ваш отзыв..." required></textarea></div>
                    <div class="form-group">
                        <label>Оценка:</label>
                        <select name="rating">
                            <option value="5">⭐⭐⭐⭐⭐ Отлично</option>
                            <option value="4">⭐⭐⭐⭐ Хорошо</option>
                            <option value="3">⭐⭐⭐ Нормально</option>
                            <option value="2">⭐⭐ Плохо</option>
                            <option value="1">⭐ Ужасно</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Оставить отзыв</button>
                </form>
                <?php if (empty($comments)): ?>
                    <p class="no-comments">Отзывов пока нет. Будьте первым!</p>
                <?php else: ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="comment">
                            <div class="comment-header">
                                <strong><?= htmlspecialchars($c['author']) ?></strong>
                                <span class="comment-rating"><?= str_repeat('⭐', (int)$c['rating']) ?></span>
                                <span class="comment-date"><?= $c['created_at'] ?></span>
                                <form method="POST" action="/comment/<?= $c['id'] ?>/delete" style="margin-left:auto;">
                                    <button type="submit" class="btn-delete">Удалить</button>
                                </form>
                            </div>
                            <p><?= nl2br(htmlspecialchars($c['text'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ['title' => $product['name'], 'content' => ob_get_clean()];
    }

    public function addComment(string $id): void
    {
        $db = Database::getConnection();
        $author = trim($_POST['author'] ?? '');
        $text   = trim($_POST['text']   ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        if ($author && $text) {
            $stmt = $db->prepare("INSERT INTO comments (product_id, author, text, rating, created_at) VALUES (?, ?, ?, ?, datetime('now'))");
            $stmt->execute([$id, $author, $text, $rating]);
        }
        header("Location: /product/$id");
        exit;
    }

    public function deleteComment(string $commentId): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT product_id FROM comments WHERE id = ?");
        $stmt->execute([$commentId]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($comment) {
            $db->prepare("DELETE FROM comments WHERE id = ?")->execute([$commentId]);
            header("Location: /product/" . $comment['product_id']);
        } else {
            header("Location: /");
        }
        exit;
    }

    public function simpleCalculator(): array
    {
        $result = isset($_GET['result']) ? htmlspecialchars($_GET['result']) : null;
        ob_start(); ?>
        <div class="calc-wrap">
            <h2>Калькулятор</h2>
            <div class="display-wrap">
                <div class="result-line" id="result-line"><?= $result ? '= ' . $result : '' ?></div>
                <input type="text" id="display" readonly value="<?= $result ?? '0' ?>">
            </div>
            <form id="calc-form" method="POST" action="/calc-compute">
                <input type="hidden" name="expression" id="expression-input">
            </form>
            <div class="calc-grid">
                <button class="cbtn cl" onclick="clearAll()">AC</button>
                <button class="cbtn cl" onclick="deleteLast()">⌫</button>
                <button class="cbtn op" onclick="ins('(')">(</button>
                <button class="cbtn op" onclick="ins(')')">)</button>
                <button class="cbtn" onclick="ins('7')">7</button>
                <button class="cbtn" onclick="ins('8')">8</button>
                <button class="cbtn" onclick="ins('9')">9</button>
                <button class="cbtn op" onclick="ins('/')">÷</button>
                <button class="cbtn" onclick="ins('4')">4</button>
                <button class="cbtn" onclick="ins('5')">5</button>
                <button class="cbtn" onclick="ins('6')">6</button>
                <button class="cbtn op" onclick="ins('*')">×</button>
                <button class="cbtn" onclick="ins('1')">1</button>
                <button class="cbtn" onclick="ins('2')">2</button>
                <button class="cbtn" onclick="ins('3')">3</button>
                <button class="cbtn op" onclick="ins('-')">−</button>
                <button class="cbtn" onclick="ins('0')">0</button>
                <button class="cbtn" onclick="ins('.')">.</button>
                <button class="cbtn op" onclick="insPercent()">%</button>
                <button class="cbtn op" onclick="ins('+')">+</button>
                <button class="cbtn eq" onclick="calculate()">=</button>
            </div>
        </div>
        <script>
        const display = document.getElementById('display');
        const resultLine = document.getElementById('result-line');
        let fresh = <?= $result ? 'true' : 'false' ?>;
        function ins(val) {
            if (fresh) {
                if (!isNaN(val) || val === '.' || val === '(') { display.value = val; }
                else if (['+','-','*','/'].includes(val)) { display.value = display.value + val; }
                else { display.value += val; }
                fresh = false; resultLine.textContent = ''; return;
            }
            if (display.value === '0' && !isNaN(val) && val !== '.') display.value = val;
            else display.value += val;
        }
        function insPercent() {
            let val = display.value;
            let match = val.match(/^(.*[\+\-\*\/])([\d\.]+)$/);
            if (match) {
                try {
                    let leftVal = Function('"use strict"; return (' + match[1].slice(0,-1) + ')')();
                    display.value = match[1] + (leftVal * parseFloat(match[2]) / 100);
                } catch(e) {}
            } else {
                try { display.value = String(parseFloat(display.value) / 100); } catch(e) {}
            }
            fresh = false; resultLine.textContent = '';
        }
        function clearAll() { display.value = '0'; resultLine.textContent = ''; fresh = false; }
        function deleteLast() {
            if (fresh || display.value.length <= 1) { display.value = '0'; fresh = false; }
            else display.value = display.value.slice(0, -1);
            resultLine.textContent = '';
        }
        function calculate() {
            let expr = display.value;
            if (!expr || expr === '0') return;
            expr = expr.replace(/÷/g, '/').replace(/×/g, '*');
            document.getElementById('expression-input').value = expr;
            document.getElementById('calc-form').submit();
        }
        document.addEventListener('keydown', function(e) {
            if (e.key >= '0' && e.key <= '9') { ins(e.key); return; }
            if (e.key === 'Enter' || e.key === '=') { e.preventDefault(); calculate(); }
            else if (e.key === 'Backspace') deleteLast();
            else if (e.key === 'Escape') clearAll();
            else if (['+','-','*','/','.','(', ')'].includes(e.key)) ins(e.key);
            else if (e.key === '%') insPercent();
        });
        </script>
        <?php
        return ['title' => 'Калькулятор', 'content' => ob_get_clean()];
    }

    public function computeCalc(): void
    {
        if (!isset($_POST['expression'])) { header('Location: /calc?result=' . urlencode('Ошибка: нет данных')); exit; }
        $raw = trim($_POST['expression']);
        if (!preg_match('/^[0-9\.\+\-\*\/\(\)\s]+$/', $raw)) { header('Location: /calc?result=' . urlencode('Ошибка: недопустимые символы')); exit; }
        $expression = preg_replace('/\s+/', '', $raw);
        $pos = 0;
        try {
            $result = $this->parseExpr($expression, $pos);
            if ($pos !== strlen($expression)) throw new Exception('Ошибка: некорректное выражение');
            $output = (fmod($result, 1.0) == 0.0)
                ? number_format($result, 0, '.', '')
                : rtrim(number_format($result, 10, '.', ''), '0');
            header('Location: /calc?result=' . urlencode($output));
        } catch (Exception $e) { header('Location: /calc?result=' . urlencode($e->getMessage())); }
        exit;
    }

    private function parseExpr(string $str, int &$pos): float {
        $result = $this->parseTerm($str, $pos);
        while ($pos < strlen($str) && ($str[$pos] === '+' || $str[$pos] === '-')) {
            $op = $str[$pos++];
            $result = $op === '+' ? $result + $this->parseTerm($str, $pos) : $result - $this->parseTerm($str, $pos);
        }
        return $result;
    }
    private function parseTerm(string $str, int &$pos): float {
        $result = $this->parseUnary($str, $pos);
        while ($pos < strlen($str) && ($str[$pos] === '*' || $str[$pos] === '/')) {
            $op = $str[$pos++]; $right = $this->parseUnary($str, $pos);
            if ($op === '/') { if ($right == 0) throw new Exception('Ошибка: деление на ноль'); $result /= $right; } else $result *= $right;
        }
        return $result;
    }
    private function parseUnary(string $str, int &$pos): float {
        if ($pos < strlen($str) && $str[$pos] === '-') { $pos++; return -$this->parseUnary($str, $pos); }
        if ($pos < strlen($str) && $str[$pos] === '+') { $pos++; return $this->parseUnary($str, $pos); }
        return $this->parseFactor($str, $pos);
    }
    private function parseFactor(string $str, int &$pos): float {
        $len = strlen($str);
        if ($pos < $len && $str[$pos] === '(') {
            $pos++; $result = $this->parseExpr($str, $pos);
            if ($pos < $len && $str[$pos] === ')') $pos++;
            else throw new Exception('Ошибка: не закрыта скобка');
            return $result;
        }
        return $this->parseNumber($str, $pos);
    }
    private function parseNumber(string $str, int &$pos): float {
        $start = $pos;
        while ($pos < strlen($str) && (ctype_digit($str[$pos]) || $str[$pos] === '.')) $pos++;
        if ($pos === $start) throw new Exception("Ошибка: ожидалось число на позиции $pos");
        return (float) substr($str, $start, $pos - $start);
    }
}