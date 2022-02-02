<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Текущая галлерея</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="../Style/gallery_stylesheet.css">
    <style>
        input{
            margin-left: 1.3em;
            width: auto;
        } 
    </style>    
</head>
<body>
<?php
require_once("connect_db.php");
@$user = $_COOKIE['admin_flag'];
@$username = $_COOKIE['username'];
$query = "SELECT * FROM galleries"; //формируем запрос
$buff = $pdo->query($query);
$gallery_id = htmlspecialchars(@$_GET['gallery_id']);//вытягиваем id-шник, кинутый через header
$query = "SELECT * FROM pictures WHERE gallery_id='$gallery_id'";
$buff = $pdo->query($query);
try{
    $catalog = $buff->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e){
    echo "Ошибка выполнения запроса: ".$e->getMessage(); 
}
if (!isset($_REQUEST['Action']))
{?>
    <header>
            <h2 class="title" id="title">
            <span>Изображеия в галерее:</span>
            <?php if($username){ ?>
            <div class="LogOut">
            <input type="text" name="username" value="<?=" Выполнен вход как: ".$username?>" id="LogedAs"><br />
            <input type="submit" name="Action" value="Log out" form="Pictures" style="float: right;">
            <?php } else {?>
            <input type="text" name="username" value="<?=" Вход не выполнен."?>" id="LogedAs" style="float: right;"><br />
            <?php } ?>
            </div>
            </h2>
    </header>
    <form id="Pictures" action = "<?=$_SERVER['SCRIPT_NAME']?>" method="post">
            <input type="submit" name="Action" value="Add new image" form="Pictures" >
            <input type="submit" name="Action" value="Back" form="Pictures" >
            <ul name="ImageList" form="Pictures">
                <?php foreach($catalog as $log):?>
                <li>
                    <a href="comment_picture.php?image_id=<?=$log['id']?>">   
                        <img class="preview" src="<?=$log['image_path']?>" alt="<?=$log['title']?>">
                    </a>
                </li>
                <?php endforeach;?>
                </ul><br />
            <input type="hidden" name="gallery_id" value="<?=htmlspecialchars($_GET['gallery_id']) ?>" form="Pictures"> <!--Перекидываю id-шник из одной обасти видимости в другую. Без этого в коде ниже нельзя было получить значение -->
            <a class="rewind_up" href="#title" title="Наверх"> &#8593 </a>        
    </form>
<?php
}
else
{
    switch($_REQUEST['Action'])
    {
        case 'Add new image':
            $dir = dirname($_SERVER['SCRIPT_NAME']);
            if ($dir=='\\') $dir='';
            $id = $_REQUEST['gallery_id'];
            header("Location: $dir/add_new_image.php?gallery_id=$id"); 
        break;
        case 'Back':
            $dir = dirname($_SERVER['SCRIPT_NAME']);
            if ($dir=='\\') $dir='';
            header("Location: $dir/galleries_list.php");
        break;
        case 'Log out':
            $dir = dirname($_SERVER['SCRIPT_NAME']);
            if ($dir=='\\') $dir='';
            header("Location: $dir/index.php");  
        break;            
    }
}    
?>
</body>
</html>