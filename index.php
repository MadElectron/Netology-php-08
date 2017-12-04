<?php  
    require_once 'functions.php';

    session_start();

    if ($_POST['login'] ?? '') {
        $_SESSION['user'] = $_POST['login'];

        $user = findUserByName($_POST['login']);

        if($_POST['pass'] ?? '') {
            if(!$user) {
                $errorMsg = "Пользователя с данным логином не существует.\n Введите верное имя пользователя или войдите как гость.";
            } elseif ($user['pass'] == $_POST['pass']) {
                $_SESSION['is_admin'] = 1;
                header('Location:login_redirect.php');
            } else {
                $errorMsg = "Логин и пароль не совпадают.\n Введите верный пароль или войдите как гость.";
            }
        } else {
            $_SESSION['is_admin'] = 0;
            header('Location:login_redirect.php');
        }
    } 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Авторизация</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="login">
            <h2>Авторизация</h2>
            <p class="error"><? nl2br($errorMsg ?? '') ?></p>

            <form action="" method="post" accept-charset="utf-8">
                <input type="text" name="login" value="<?= $_POST['login'] ?? '' ?>" placeholder="Логин" autofocus required>
                <input type="text" name="pass" value="" placeholder="Пароль">
                <input type="submit" name="submit" value="Войти">
            </form>    
        </div>
    </div>
</body>
</html>