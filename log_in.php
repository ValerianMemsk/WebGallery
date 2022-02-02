<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Страница входа</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="../Style/gallery_stylesheet.css">
</head>
<body>
<?php
    require_once("connect_db.php");
    date_default_timezone_set("Europe/Moscow");
    if (!isset($_REQUEST['Action']))
    { ?>
        <form action = "<?=$_SERVER['SCRIPT_NAME']?>">
        <h2>Вход:</h2>
        <p>Логин: <input type="text" name="login" value=""></p>
        <p>Пароль: <input type="password" name="password" value=""></p>
        <input type ="submit" name="Action" value="Log in">
        <input type ="submit" name="Action" value="Register">
        </form>
    <?php 
    } else 
    {
        switch($_REQUEST['Action'])
        {
            case 'Log in':
                $username = $_REQUEST['login'];
                $password =  $_REQUEST['password'];
                $query = "SELECT admin_flag FROM users WHERE username='$username' AND passw='$password'";
                $buff = $pdo->query($query);
                $buff = $buff->fetchAll(PDO::FETCH_ASSOC);
                if($buff)
                {
                    $buff = $buff['0'];
                    $value = $buff['admin_flag'];
                    setcookie('admin_flag', $value);
                    setcookie('username', $username);
                    header("Location: $dir/galleries_list.php");
                }
                else
                    die("Ошибка, такого пользователя не существует.");                   
            break;
            case 'Register':?> <!-- Просто задел на будущее, пока только внешний вид-->
            <form action = "<?=$_SERVER['SCRIPT_NAME']?>">
                Регистрация:<br />
                e-mail: <input type="text" name="login" value=""><br />
                Логин: <input type="text" name="login" value=""><br />
                Пароль: <input type="password" name="password" value=""><br />
                Повторите пароль: <input type="password" name="repeat_password" value=""><br />
                <input type ="submit" name="Action" value="Register">
                <input type ="submit" name="Action" value="Back">
            </form>
    <?php 
            break;
        }    
    }    
?>
</body>
</html>