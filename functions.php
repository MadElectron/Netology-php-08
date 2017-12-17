<?php 
    function getUsers()
    {
        $path = __DIR__.'/users.json';
        $json = file_get_contents($path);
        $data = json_decode($json, true);

        if (!$data)
            return [];

        return $data;
    }

    function findUserByName($name)
    {
        $users = getUsers();

        if($users) {
            foreach($users as $user) {
                if($user['name'] == $name) {
                    return $user;
                }
            }
        }

        return null;
    }

    function checkTestsErrors($filename) {

        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        $errCount = 0;

        if (!count($data)) {
            echo '<p class="alert">Список вопросов пуст!</p>';
        }

        // Checking test consistncy
        $testParams = ['id' => 'Номер', 'name' => 'Название', 'questions' => 'Список вопросов'];

        foreach ($data as $testNum => $test) {
            if ($testNum) {
                echo '<hr>';
            }

            $missingParams = array_diff(array_keys($testParams), array_keys($test));
   
            if ($missingParams) {
                $errCount++;
                echo '<p class="alert">'.$testNum.'-й тест не имеет следующих параметров:</p><ul>';

                foreach($missingParams as $param) {
                    echo '<li>'.$testParams[$param].'</li>';
                }

                echo '</ul>';
                continue;
            }

            if (!count($test['questions'])) {
                $errCount++;
                echo '<p class="alert">Список вопросов пуст.</p>';
                continue;
            }

            // Checking question consistncy
            $questionParams = ['id' => 'Номер', 'content' => 'Содержание вопроса', 'answers' => 'Ответы'];

            foreach($test['questions'] as $qNum => $question) {
                $missingParams = array_diff(array_keys($questionParams), array_keys($question));

                if ($missingParams) {
                    $errCount++;
                    echo '<p class="alert">'.$qNum.'-й вопрос не имеет следующих параметров</p><ul>';

                    foreach($missingParams as $param) {
                        echo "<li>$questionParams[$param]</li>";
                    }

                    echo '</ul>';
                }

                if (!count($question['answers'])) {
                    $errCount++;
                    echo '<p class="alert">Список ответов пуст.</p>';
                    continue;
                }

                // Checking answer consistncy
                $answerParams = ['content' => 'Содержание ответа', 'right' => 'Правильность'];

                foreach($question['answers'] as $aNum => $answer) {
                    $missingParams = array_diff(array_keys($answerParams), array_keys($answer));

                    if ($missingParams) {
                        $errCount++;
                        echo '<p class="alert">'.$aNum.'-й ответ не имеет следующих параметров</p><ul>';

                        foreach($missingParams as $param) {
                            echo "<li>$answerParams[$param]</li>";
                        }

                        echo '</ul>';
                        continue;
                    }
                }

                // Checking presence of right answers
                $rights = array_column($question['answers'], 'right');
                if(!in_array(true, $rights)) {
                    echo '<p class="alert">Для вопроса не указано ни одного правильного ответа</p>';
                }
            }
        }

        if($errCount) {
            echo '<p class="alert"><strong>Ошибок в файле: '.$errCount.'</strong></p>';
        }

        return $errCount;
    }

    function submitTestUpload() {
            $type = $_FILES['file']['type'];
            $tmpName = $_FILES['file']['tmp_name'];
            $name = $_FILES['file']['name'];
            $extension = array_pop(explode('.',$name));

            if ($type == "application/json" // for POSIX-compatible
                || 
                $extension == "json"        // for Windows
                ) {

                $errCount = checkTestsErrors($tmpName);

                if (!$errCount) {
                    return $tmpName;
                }
                else {
                    echo '<p class="alert">Файл '.$name.' не загружен. Ошибок в файле:'.$errCount.'</p>';
                }
            }
            else {
                echo '<p class="alert">Файл неверного типа или не выбран! Допускаются только файлы в формате json.</p>';
            }

        return false;
       
    }

function setAppCookie($name, $value)
{
   if(!isset($_COOKIE[$name])) {
        setcookie($name, $value);
        $_COOKIE[$name] = $value;
    } 
}
