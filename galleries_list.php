<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Список галлерей</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="../Style/gallery_stylesheet.css"> <!-- Баловался css'ом, так или иначе пригодится ещё. Береги глаза, там пиздец. -->
</head>
<body>
<?php
require_once("connect_db.php");//скрипт с коннектом бд
@$user = $_COOKIE['admin_flag'];
@$username = $_COOKIE['username'];
$query = "SELECT * FROM galleries"; //формируем запрос
$buff = $pdo->query($query);
try{
    $catalog = $buff->fetchAll(PDO::FETCH_ASSOC); //получаем ответ
} catch (PDOException $e){
    echo "Если Вы это видите - произошла какая-то хрень. Ошибка выполнения запроса: ".$e->getMessage(); 
}
if (!isset($_REQUEST['Action']))
{
    ?>
    <header>
            <h2 class="title" id="title">
            <span>Галлереи:</span>
            <?php if($username){ ?>
            <div class="LogOut">
            <input type="text" name="username" value="<?=" Выполнен вход как: ".$username?>" id="LogedAs"><br />
            <input type="submit" name="Action" value="Log out" form="Galleries" style="float: right;">
            <?php } else {?>
            <input type="text" name="username" value="<?=" Вход не выполнен."?>" id="LogedAs" style="float: right;"><br />
            <?php } ?>
            </div>
            </h2>
    </header>        
    <form id="Galleries" action = "<?=$_SERVER['SCRIPT_NAME']?>" method="post">
        <?php if($user == '1') // решил таким образом отделить админский функционал от обычного. Топорно, но работает.
            {?>
            <select name="GalleryList" form="Galleries" multiple>
                <?php foreach($catalog as $log):?>
                <option><?=$log['title']."<br />";?></option>
                <?php endforeach;?>
            </select><br />
            <p><input type="submit" name="Action" value="Open gallery" form="Galleries" >
            <input type="submit" name="Action" value="Create new gallery" form="Galleries"></p>
            <input type="submit" name="Action" value="Delete gallery" form="Galleries" >
    <!-- ----------------------------------------------------------------------------------------------- -->        
        <?php } else {?> <!-- Здесь начинается пользовательсткий -->
            <input type="submit" name="Action" value="Create new gallery" form="Galleries" >
            <ul name="GalleryList" form="Galleries">
                <?php foreach($catalog as $log):?> <!-- А вот и ссылочки. Превьюшек для галлерей пока нет, так что там везде alt'ы будут -->
                <li><a href="gallery_content.php?gallery_id=<?=$log['id']?>"><img src="<?=$log['image_path']?>" alt="<?=$log['title']?>" height="150" width="200"></a></li>
                <?php endforeach;?>
            </ul><br />
            <a class="rewind_up" href="#title" title="Наверх"> &#8593 </a>
        <?php } ?>  
    </form>
    <p id="footer">
      &copy; 2021, Gallery web app<br>
      All trademarks and registered trademarks appearing on this site are 
      the property of their respective owners.
    </p>
    <?php
}
else
{
    $gallery = @$_REQUEST['GalleryList'];
    $query = "SELECT id FROM galleries WHERE title='$gallery'";
    $gallery = $pdo->query($query);
    $gallery = $gallery->fetchAll(PDO::FETCH_ASSOC); //ответ приходит в виде двумерного массива, не нашёл/не вспомнил,  
    $gallery = $gallery['0'];                       //как его адекватно разобрать, поэтому так.
    $gallery = $gallery['id'];
    switch($_REQUEST['Action']) 
    {
        case 'Open gallery': 
            if(!$gallery)
                echo "Вы не выбрали галлерею для открытия.";
            else
            {   
                $dir = dirname($_SERVER['SCRIPT_NAME']);
                if ($dir=='\\') $dir='';
                header("Location: $dir/gallery_content.php?gallery_id=$gallery");  
            }    
        break;
        case 'Create new gallery':
            $dir = dirname($_SERVER['SCRIPT_NAME']);
            if ($dir=='\\') $dir='';
            header("Location: $dir/create_new_gallery.php");  
        break;
        case 'Delete gallery':
            $value =  $_REQUEST['GalleryList'];
            $query = "SELECT id FROM pictures where gallery_id='$gallery'";//вытаскиваем id'шик галлереи
            $buff = $pdo->query($query);
            $catalog = $buff->fetchAll(PDO::FETCH_ASSOC);
            foreach($catalog as $picture)//удаляем все комменты под каждой фоткой (иначе всё падает из-за ключей)
            {
                $id = $picture['id'];
                echo $id." ";
                $query = "DELETE FROM comments where image_id='$id'";
                $pdo->exec($query);
            }
            $query = "DELETE FROM pictures where gallery_id='$gallery'";
            $pdo->exec($query);//удаляем все фотки
            $query = "DELETE FROM galleries where title='$value'";
            $pdo->exec($query);//удаляем саму галерею
            header("location: ". $_SERVER['PHP_SELF']);
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