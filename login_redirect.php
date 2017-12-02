<?php 
    session_start();

    if ($_SESSION['is_admin']) {
        echo 'Вы авторизовались как администратор. Перемещаю на страницу загрузки тестов...';
        header('Refresh:2 Url="admin.php"');
    } else {
        echo 'Вы авторизовались как гость. Перемещаю на список тестов...';
        header('Refresh:2; Url="list.php"');
    }

