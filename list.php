<?php  
    session_start();

    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        echo 'Вход только для авторизованных пользователей!';
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Список тестов</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <header id="header">
        <div class="container">
            <p>Вы вошли как <?= $_SESSION['user'] ?>(<?= $_SESSION['is_admin'] ? 'Адмнистратор' : 'Гость' ?>) | 
                <a href="logout.php">Выйти</a>
            </p>
        </div>
    </header>
    <div class="container">
        <h1>Список тестов</h1>
        <nav>
            <ul>
                <?php if($_SESSION['is_admin']) : ?>
                    <li><a href="admin.php" title="Загрузка теста">Загрузка теста</a></li>
                <?php endif; ?>
                <li>Список тестов</li>
            </ul>
        </nav>
        <hr>
        <form action="" method="post" accept-charset="utf-8">
            <input type="hidden" name="purge" value="1">
            <input type="submit" name="submit" value="Удалить тесты" onclick="alert('Вы действительно хоите удалить все тесты?')">
        </form>
        <hr>

        <?php 
            $testsPath = 'tests/tests.json';

            if($_POST['purge'] ?? '') {
                unlink($testsPath);
                header('Location: admin.php');
            }

            if (file_exists($testsPath)) {
                $json = file_get_contents($testsPath);
                $data = json_decode($json, true);

                foreach ($data as $test) {
                    echo '<p><a href="test.php?id='.$test['id'].'">'.
                        $test['id'].'. '.$test['name'].'</a></p>';
                }
            }
            else {
                echo '<p class="alert">Список тестов пуст!</p>';
            }
        ?>
    </div>
</body>
</html>