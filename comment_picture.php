<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Просмотр изображения</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="../Style/gallery_stylesheet.css">
    <style>
        ul{
            border: 2px solid #0d5469;
            border-radius: 1.5mm;
            width: 400px;
            height: 150px;
        }
        textarea{
            border: 2px solid #0d5469;
            border-radius: 3mm;
            width: 400px;
            margin-left: 1.3em;
            resize: none;
        }
        input{
            margin-left: 1.3em;
            width: 100px;
        }
        img{
            margin-left: 1.2em;
        }
    </style>
</head>
<body>
<?php
require_once("connect_db.php");
$image_id = htmlspecialchars(@$_GET['image_id']);
$query = "SELECT image_path FROM pictures WHERE id='$image_id'";
if($_COOKIE['admin_flag'] == 1)
{
    $comment_query = "SELECT * from comments WHERE image_id='$image_id'";
} 
else
{
    $comment_query = "SELECT * from comments WHERE image_id='$image_id' AND approval='1'";
}   
$buff = $pdo->query($query);
$comment = $pdo->query($comment_query);
try{
    $catalog = $buff->fetchAll(PDO::FETCH_ASSOC);
    $comment = $comment->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e){
    echo "Ошибка выполнения запроса: ".$e->getMessage(); 
}
$path = @$catalog['0'];
$path = @$path['image_path'];
if (!isset($_REQUEST['Action']))
{?>
    <form id="CommentPicture" action = "<?=$_SERVER['SCRIPT_NAME']?>" method="post">
            <?='<img src="'. $path .'" alt="No image found"/>'?><br />
            <input type="hidden" name="image_id" value="<?=htmlspecialchars($_GET['image_id']) ?>" form="CommentPicture">
            <textarea name="Comment" value="" placeholder="Оставьте свой комментарий здесь" cols="54" rows="4" form="CommentPicture" ></textarea><br />
        <?php if($_COOKIE['admin_flag'] == '1') //
        {?>    
            <select name="CommentList" form="CommentPicture" multiple>
                <?php foreach($comment as $item):
                if($item['approval'] == 1){?><!-- Админ увидит вообще все комменты, одобренные выделяю зелёным -->
                <option style="color: green;"><?=$item['posted_by']." (".$item['email'].") posted:  ".unserialize($item['content']);?></option>
                <?php }else{?>
                <option ><?=$item['posted_by']." (".$item['email'].") posted:  ".unserialize($item['content']);?></option>
                <?php } endforeach;?>
            </select><br />
            <p><input type="submit" name="Action" value="Post" form="CommentPicture" >
            <input type="submit" name="Action" value="Back" form="CommentPicture" ></p>
            <input type="submit" name="Action" value="Approve comm" form="CommentPicture" >
            <input type="submit" name="Action" value="Delete comm" form="CommentPicture" >
            <input type="submit" name="Action" value="Delete image" form="CommentPicture" >
        <!-- ----------------------------------------------------------------------------------------------- -->    
        <?php } else {?>
            <p><input type="submit" name="Action" value="Post" form="CommentPicture" >
            <input type="submit" name="Action" value="Back" form="CommentPicture" ></p>
            <ul name="CommentList" form="CommentPicture">
                <?php if(empty($comment)){?>
                <li style="margin-bottom: 1em;"><?="Здесь пока нет ни одного комментария. Будьте первым!"?></li>
                <?php }else{ foreach($comment as $item):?>
                <li style="margin-bottom: 1em;"><?=$item['posted_by']." (".$item['email'].") posted:  ".unserialize($item['content']);?></li>
                <?php endforeach;}?>
                </ul><br />
        <?php }?>    
    </form>
    <?php
}
else
{
    switch($_REQUEST['Action'])
    {
        case 'Post':
            $id = $_REQUEST['image_id'];
            $user = $_COOKIE['username'];
            $content = $_REQUEST['Comment'];
            $string = serialize($content);
            $date = date("y.m.d h:m:s");
            $image_id = $_REQUEST['image_id'];
            $query = "SELECT email FROM users WHERE username='$user'";
            $email = $pdo->query($query);
            $email = $email->fetchAll(PDO::FETCH_ASSOC);
            $email = $email['0'];
            $email = $email['email'];
            $query = "INSERT INTO comments (comment_id, posted_by, email, created_at, content, image_id ) VALUES (NULL, '$user', '$email', '$date', '$string', '$image_id')";
            $pdo->exec($query);
            header("location: ". $_SERVER['PHP_SELF']."?image_id=$id");
        break;
        case 'Back':
            $id = $_REQUEST['image_id'];
            $query = "SELECT gallery_id FROM pictures WHERE id='$id'";
            $buff = $pdo->query($query);
            $catalog = $buff->fetchAll(PDO::FETCH_ASSOC);
            $gallery_id = $catalog['0'];
            $gallery_id = $gallery_id['gallery_id'];
            $dir = dirname($_SERVER['SCRIPT_NAME']);
            if ($dir=='\\') $dir='';
            header("Location: $dir/gallery_content.php?gallery_id=$gallery_id");
        break;
        case 'Approve comm':
            $id = $_REQUEST['image_id'];
            $value =  $_REQUEST['CommentList'];
            $value =  $_REQUEST['CommentList'];
            $value = explode(': ',$value);
            $value = serialize($value['1']);
            $query = "UPDATE comments SET approval='1' where content='$value'";
            $pdo->exec($query);
            header("location: ". $_SERVER['PHP_SELF']."?image_id=$id");
        break;    
        case 'Delete comm':
            $id = $_REQUEST['image_id'];
            $value =  $_REQUEST['CommentList'];
            $value = explode(': ',$value);
            $value = serialize($value['1']);
            $query = "DELETE FROM comments where content='$value'";
            $pdo->exec($query);
            header("location: ". $_SERVER['PHP_SELF']."?image_id=$id");
        break;
        case 'Delete image':
            $id = $_REQUEST['image_id'];
            $query = "SELECT gallery_id FROM pictures WHERE id='$id'";
            $buff = $pdo->query($query);
            $catalog = $buff->fetchAll(PDO::FETCH_ASSOC);
            $gallery_id = $catalog['0'];
            $gallery_id = $gallery_id['gallery_id'];
            $query = "DELETE FROM comments where image_id='$id'";
            $pdo->exec($query);//удаляем комментарии (ругается на вторичные ключи, если удалять сразу картинку)
            $query = "DELETE FROM pictures where id='$id'";
            $pdo->exec($query);//удаляем картинку
            header("Location: $dir/gallery_content.php?gallery_id=$gallery_id");
        break;        
    }
}    
?>
</body>
</html>