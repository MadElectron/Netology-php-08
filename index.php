<?php  
    require_once 'functions.php';

    session_start();

    // setcookie('restricted',0, time()-1000);
    // setcookie('tries',0, time()-1000);
    // unset($_SESSION['captcha']);
    // echo $_SESSION['captcha'], $_POST['captcha'];

    if(isset($_COOKIE['restricted']))
    {
        echo 'Вход закрыт, попробуйте позже.';
        exit;
    }

    setAppCookie('tries', 3, time()+60*60);


    if ($_POST['login'] ?? '') {
        $_SESSION['user'] = $_POST['login'];

        $user = findUserByName($_POST['login']);

        if($_POST['pass'] ?? '') {
            if(!$user) {
                $errorMsg = "Пользователя с данным логином не существует.\n Введите верное имя пользователя или войдите как гость.";
            } elseif ($user['pass'] == $_POST['pass']) {

                if (isset($_SESSION['captcha']) && $_SESSION['captcha'] != $_POST['captcha']) {
                    setcookie('tries', $_COOKIE['tries']-1);
                    $errorMsg = "Введённые цифры не совпадают с цифрами на картинке.";
                } else {
                    $_SESSION['is_admin'] = 1;
                    header('Location:login_redirect.php');
                }                  
            } else {
                setcookie('tries', $_COOKIE['tries']-1);
                $errorMsg = "Логин и пароль не совпадают.\n Введите верный пароль или войдите как гость.";
            }
        } else {
            $_SESSION['is_admin'] = 0;
            setcookie('tries',0, time()-1000);
            unset($_SESSION['captcha']);

            header('Location:login_redirect.php');
        }
    } 

    if($_COOKIE['tries'] <= 0) {
        $_SESSION['captcha'] = mt_rand(10000, 99999);
    }

    if($_COOKIE['tries'] <= -300)
    {
        setcookie('restricted', 1,  time()+60*60);
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
            <p class="error"><?= nl2br($errorMsg ?? '') ?></p>

            <form action="" method="post" accept-charset="utf-8">
                <input type="text" name="login" value="<?= $_POST['login'] ?? '' ?>" placeholder="Логин" autofocus required>
                <input type="password" name="pass" value="" placeholder="Пароль">
                <input type="submit" name="submit" value="Войти">

                <?php if($_COOKIE['tries'] <= 0) : ?>
                    <label>Введите цифры, показанные на рисунке</label>
                    <img src="captcha.php?text=<?= $_SESSION['captcha'] ?>" alt="Captcha">
                    <input type="text" name="captcha" value="" required>
                <?php endif ?>      
            </form>            
        </div>
    </div>
</body>
</html>