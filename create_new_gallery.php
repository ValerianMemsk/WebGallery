<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Добавление новой галлереи</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="../Style/gallery_stylesheet.css">
</head>
<body>
<?php
require_once("connect_db.php");
if (!isset($_REQUEST['SubAction2']))
{
?>
<form id="AddNewGallery" action = "<?=$_SERVER['SCRIPT_NAME']?>" method="post">
    <h2>Введите имя и путь для иконки новой галлереи:</h2><br />
    <p>Имя:<input type="text" name="title" value="" form="AddNewGallery"></p>
    <p>Иконка:<input type="text" name="path" value="" form="AddNewGallery" placeholder="Вводите путь используя '/'"></p>
    <input type="submit" name="SubAction2" value="Add" form="AddNewGallery">
    <input type="submit" name="SubAction2" value="Cancel" form="AddNewGallery" >
</form>
<?php
}
else
{
switch($_REQUEST['SubAction2'])
{
    case 'Add':
        $data = date("y.m.d");
        $title = $_REQUEST['title'];
        $path = $_REQUEST['path'];
        $query = "INSERT INTO galleries VALUE (NULL, '$title', '$path', '$data')";
        $pdo->exec($query);
        $query = "SELECT id FROM galleries WHERE title='$title'";
        $gallery_id = $pdo->query($query);
        $gallery_id = $gallery_id->fetchAll(PDO::FETCH_ASSOC);//то же самое, колхозное извлечение нужного мне значения из многомерного массива
        $gallery_id = $gallery_id['0'];
        $gallery_id = $gallery_id['id'];
        $dir = dirname($_SERVER['SCRIPT_NAME']);
        if ($dir=='\\') $dir='';
        header("Location: $dir/gallery_content.php?gallery_id=$gallery_id");
    break;
    case "Cancel":
        $dir = dirname($_SERVER['SCRIPT_NAME']);
        if ($dir=='\\') $dir='';
        header("Location: $dir/galleries_list.php");   
}
}              
?>
</body>
</html>