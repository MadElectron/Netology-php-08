<?php  
    require_once 'functions.php';

    session_start();

    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        echo 'Вход только для авторизованных пользователей!';
        exit;
    }
    elseif (!$_SESSION['is_admin']) {
        http_response_code(403);
        echo 'Вход только для администраторов!';
        exit;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Загрузка файла</title>
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
        <h1>Загрузка теста</h1>
        <nav>
            <ul>
                <li>Загрузка теста</li>
                <li><a href="list.php" title="Список тестов">Список тестов</a></li>
            </ul>
        </nav>
        <hr>
        
        <form action="admin.php" enctype="multipart/form-data" method="post">
            <label>Выберите файл</label>
            <input type="file" name="file"><br>
            <input type="submit" name="submit" value="Загрузить">
        </form>

        <?php 
            if (isset($_FILES['file'])) {
                $file = submitTestUpload();
                if ($file) {
                    move_uploaded_file($file, 'tests/tests.json');
                    header("Location: list.php");
                }
            }
        ?>
    </div>
</body>
</html>