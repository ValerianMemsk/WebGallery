<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Добавление нового изображения</title>
    <meta charset='utf-8'>
    <link rel="stylesheet" href="../Style/gallery_stylesheet.css">
</head>
<body>
<?php
require_once("connect_db.php");
if (!isset($_REQUEST['SubAction2']))
{
?>
<form id="AddNewImage" action = "<?=$_SERVER['SCRIPT_NAME']?>" method="post" enctype="multipart/form-data">
    <p><h2>Выберите изображение для добавления:</h2></p>
    <p><input type="file" id="FileToUpload" name="FileToUpload"></p>
    <input type="hidden" name="gallery_id" value="<?=htmlspecialchars($_GET['gallery_id']) ?>" form="AddNewImage">
    <input type="submit" name="SubAction2" value="Add" form="AddNewImage">
    <input type="submit" name="SubAction2" value="Cancel" form="AddNewImage">
</form>
<?php
}
else
{
switch($_REQUEST['SubAction2'])
{
    case 'Add':
        //---------------------------------------------------------------------------------------
        //экспериментальный кусок для загрузки изображеий нормальным способом
        $target_dir = "Images/";
        $target_file = $target_dir . basename($_FILES["FileToUpload"]["name"]);
        $uploadOK = 1;
        $image_filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        //print_r($_FILES);
        $check = getimagesize($_FILES["FileToUpload"]["tmp_name"]);
        if($check !== false)
        {
            if(!(file_exists($target_file)))
            {
                $check['mime'] . ".";
                $uploadOK = 1;
            }
            else
            {
                $uploadOK = 0;
                echo "Файл с таким именем уже существует.";
            }    
        }
        else
        {
            $uploadOK = 0;
        }
        if($image_filetype != "jpg" and $image_filetype != "png" and $image_filetype != "jpeg" and $image_filetype != "gif")
        {
            $uploadOK = 0;
            echo "Неправильный формат файла.";
        }
        if($uploadOK == 1)
        {
            if(!(move_uploaded_file($_FILES["FileToUpload"]["tmp_name"], $target_file)))
                echo "Возникла ошибка при загрузке файла.";    
        }
        //---------------------------------------------------------------------------------------
        $data = date("y.m.d");
        $title = $_FILES["FileToUpload"]["name"];
        $path = $target_file;
        $gallery_id = $_REQUEST['gallery_id'];
        $query = "INSERT INTO pictures VALUES (NULL, '$title', '$path', '$data', '$gallery_id')";
        $pdo->exec($query);
        $dir = dirname($_SERVER['SCRIPT_NAME']);
        if ($dir=='\\') $dir='';
        header("Location: $dir/gallery_content.php?gallery_id=$gallery_id");
    break;
    case "Cancel":
        $dir = dirname($_SERVER['SCRIPT_NAME']);
        if ($dir=='\\') $dir='';
        $id =  $_REQUEST['gallery_id'];
        header("Location: $dir/gallery_content.php?gallery_id=$id");
    break;       
}
}              
?>
</body>
</html>