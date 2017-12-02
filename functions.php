<?php 
    function getUsers()
    {
        $path = __DIR__.'/users.json';
        $json = file_get_contents($path);
        $data = json_decode($json, true);

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