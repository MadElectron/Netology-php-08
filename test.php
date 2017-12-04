<?php 
    session_start();

    if(!$_SESSION['user']) {
        http_response_code(403);
        echo 'Вход только для авторизованных пользователей!';
        exit;
    }

    $testId = $_GET['id'];

    $json = file_get_contents('tests/tests.json');
    $data = json_decode($json, true);

    $test = null;

    // Searching test in array by 'id' field
    foreach ($data as $datum) {
        if ($datum['id'] == $testId) {
            $test = $datum;
            break;
        }
    }

    if(!$test) {
        http_response_code(404);
        exit;
    }

    $name = $test['name'];

?>  
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Тест</title>
    <link rel="stylesheet" type="text/css" href="css/style.css?v1.00001">
</head>
<body>
    <div class="container">
         <header id="header">
            <div class="container">
                <p>Вы вошли как <?= $_SESSION['user'] ?>(<?= $_SESSION['is_admin'] ? 'Адмнистратор' : 'Гость' ?>) | 
                    <a href="logout.php">Выйти</a>
                </p>
            </div>
        </header> 
        <h1>Тест <?php echo $testId.' .'.$name ?></h1>
        <nav>
            <ul>
                <li><a href="admin.php" title="Загрузка теста">Загрузка теста</a></li>
                <li><a href="list.php" title="Список тестов">Список тестов</a></li>
                <li>Тест <?php echo $testId.'. '.$name ?></li>
            </ul>
        </nav>
        <hr>
        
        <?php
            // After form is submitted
        
            if(isset($_POST['submit'])) :
                $result = array_sum($_POST);
                $qCount = count($test['questions']);
                $username = $_SESSION['user'];
                $imagePath = "certificate.php?result=$result&qcount=$qCount&username=$username";

        ?>
            <a href="<?php echo $imagePath ?>" title="Скачать" download>
                <img class="certificate" src="<?php echo $imagePath ?>" alt="Сертификат">
            </a>
            <p><strong>Результат: <?php echo $result.' из '.$qCount; ?>.</p></strong>
            <p><a href="">Пройти заново</a></p>
        
        <?php 
            exit;
            endif;
        ?>
        

        <form action="test.php?id=<?php echo $testId; ?>" method="post">
            <?php foreach ($test['questions'] as $qNum => $question) { ?>  
                <p><strong><?php echo $question['id'].'. '.$question['content']; ?></strong></p>
                <?php foreach ($question['answers'] as $aNum => $answer) {  
                    $answerId = 'q'.$qNum.'a'.$aNum;
                    $questionId = 'q'.$qNum;
                ?>
                <label for="<?php echo $answerId; ?>">
                    <input type="radio" name="<?php echo $questionId; ?>" value="<?php echo $answer['right']; ?>" id="<?php echo $answerId; ?>"><?php echo $answer['content']; ?>
                </label>
            <?php } ?>
        <?php } ?>
        <br>
        <hr>
        <input type="submit" name="submit" value="Ответить">
        </form>
        <br>

        <?php 
        ?>
    </div>
</body>
</html>