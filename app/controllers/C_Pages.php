<h3>Pages</h3>
<h2><a href="?page=Pages&action=add">Add New Page</a></h2>
<?php
if ($_POST OR @$_GET['action']) {

    if (isset($_GET['action']) && $_GET['action'] == "add") {

        if (isset($_POST['submit']) && $_POST['submit'] == "Add") {
            //`pages` :: `id`, `page_name`, `page_content`, `page_status`, `page_visits`, `sectionId`, `page_image`, `page_date`, `createdBy`

            $newPage['page_name'] = $_POST['page_name'];
            $newPage['page_content'] = $_POST['page_content'];
            $newPage['page_status'] = $_POST['page_status'];
            $newPage['page_visits'] = 0;
            $newPage['sectionId'] = $_POST['sectionId'];

            $newPage['page_date'] = $_POST['page_date'];
            $newPage['createdBy'] = $_SESSION['username'];


            if (!empty($_FILES['page_image']['name'][0])) {
                try {
                    include 'models/Upload.php';
                    $file = $_FILES['page_image'];
                    $allowedExts = array('jpg', 'png', 'gif', 'jpeg');
                    $uploadsDirecotry = "resources/uploads/";
                    $maxSize = 4000000;
                    $upload = new Upload($file, $allowedExts, $uploadsDirecotry, $maxSize);
                    $upload->uploadFiles();
                    $newPage['page_image'] = $uploadsDirecotry . $upload->getFileUrl();
                } catch (Exception $exc) {
                    $exc->getMessage();
                }
            }
            else
            {
                $newPage['page_image'] = 'images/logo.png';
            }

            $tabename = "pages";

            try {
                include 'models/Awebarts.php';
                include 'models/Add.php';
                $addNewPage = new Add($newPage, $tabename);
                if ($addNewPage) {
                    echo '<script type="text/javascript"> alert("The New Page was Created !"); history.back();</script>';
                }
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        } else {
            try {
                //$PageDataDisplay;
                include 'models/Awebarts.php';
                include 'models/Display.php';
                $tablename = "sections";
                $dsiSections = new Display($tablename);
                $PageDataDisplay = $dsiSections->getAllData();
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }

            include 'veiws/addNewPage.php';
        }
    }

    // Delete:
    if (isset($_GET['action']) AND $_GET['action'] == "delete") {

        try {
            include 'models/Awebarts.php';
            include 'models/Delete.php';
            $tablename = "pages";
            $id = $_GET['id'];

            $deSec = new Delete($tablename);
            $deSec->deletRecordByID($id);
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    //edit
    if (isset($_GET['action']) AND $_GET['action'] == "edit") {

        $id = $_GET['id'];
        $tablename = "pages";

        include 'models/Awebarts.php';
        include 'models/Display.php';

        $displaypagedata = new Display($tablename);
        $pagedata = $displaypagedata->getRecordByID($id);

        $tablename = "sections";
        $dsiSections = new Display($tablename);
        $PageDataDisplay = $dsiSections->getAllData();

        include 'veiws/editPage.php';

        if (isset($_POST['submit']) && $_POST['submit'] == "Edit") {
            $editPage['page_name'] = $_POST['page_name'];
            $editPage['page_content'] = $_POST['page_content'];
            $editPage['page_status'] = $_POST['page_status'];
            $editPage['sectionId'] = $_POST['sectionId'];
            $editPage['page_image'] = 'images/logo.png';     // must be reviewed                            

            $tabename = "pages";
            $id = $_GET['id'];
            try {
                include 'models/Update.php';

                $updatePage = new Update($editPage, $tabename);
                $updatedPage = $updatePage->editData($id);

                if ($updatedPage) {
                    echo '<script type="text/javascript"> alert("The New Page was updated !"); history.back();</script>';
                }
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
    }
} else {
    include 'models/Awebarts.php';
    include 'models/Display.php';
    $tablename = "pages";

    $displaypages = new Display($tablename);
    $PagesDataDisplay = $displaypages->getAllData();

    include 'veiws/pages.php';
}
?>