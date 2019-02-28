<?php
    function dbConnect($usertype, $connectionType = 'mysqli') {
        $host = 'localhost';
        $db = 'quodlibet';
        if ($usertype == 'read') {
            $user = 'qdread';
            $pwd = '1a2c85cc945e6e81de8a3f59a1ba085607e4229025218e46c95dc1084a189e42';
        } elseif ($usertype == 'write') {
            $user = 'qdwrite';
            $pwd = '3b4ef3fe7db5a625757ec9c3011858bc0d23a013f5a8a92ca5da15dbb43fc28d';
        } else {
            exit('Unrecognized user');
        }
        
        if ($connectionType == 'mysqli') {
            $conn = @ new mysqli($host, $user, $pwd, $db);
            if ($conn->connect_error) {
                exit($conn->connect_error);
            }
            return $conn;
        } else {
            try {
                return new PDO("mysql:host=$host;dbname$db", $user, $pwd);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        
    }
?>