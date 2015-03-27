<h3>Library</h3>
<?php
if ($_POST) {
    if (isset($_FILES)) {
        try {
            include 'models/Upload.php';

            $file = $_FILES['image'];
            $allowedExts = array('jpg', 'png');
            $uploadsDirecotry = "resources/uploads/";
            $maxSize = 4000000;
            $upload = new Upload($file, $allowedExts, $uploadsDirecotry, $maxSize);
            $upload->uploadFiles();
            echo $upload->getFileUrl();
        } catch (Exception $exc){
            $exc->getMessage();
        }
    }
} else {
    include 'veiws/addNewFile.php';
    echo '<div class="clear"></div>';
    echo '<hr>';

    $uploadsDirecotry = "resources/uploads/";
    $dir = scandir($uploadsDirecotry);
    $scdir = array_diff($dir, array('..', '.'));

    echo '<div class="imglib"';
    foreach ($scdir as $value) {
        echo "<img src='$uploadsDirecotry$value' />";
    }
    echo '</div>';
}
?>
