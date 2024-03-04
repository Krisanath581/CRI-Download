<?php 

 session_start();
 require_once("config/db_connect");

 if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $img_name = $_POST['img_name'];
    $img_content = $_POST['img_content'];
    $img = $_FILES['images'];

    $img2 = $_POST['img2'];
    $upload = $_FILES['img']['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extension = explode('.', $img['name']);
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt;  // rand function create the rand number 
        $filePath = 'uploads/'.$fileNew;

        if (in_array($fileActExt, $allow)) {
            if ($img['size'] > 0 && $img['error'] == 0) {
               move_uploaded_file($img['tmp_name'], $filePath);
            }
        }

    } else {
        $fileNew = $img2;
    }

    $sql = $conn->prepare("UPDATE stock_images SET img_name = :img_name, img_content = :img_content, images = :images, files = : files,) VALUES (:img_name, :img_content, :images, :files)");
    $sql->bindParam(":img_name", $img_name);
    $sql->bindParam(":img_content", $img_content);
    $sql->bindParam(":images", $fileNew);
    // $sql->bindParam(":files", $fileNew2);
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = "Data has been updated successfully";
        header("location: index.php");
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
        header("location: index.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>CRI Download</title>

    <style>
        .container{
            max-width: 550px;
        }
    </style>


</head>

<body>

    <!-- Add data -->
   
    <div class="container mt-5">
        <h1>แก้ไขข้อมูล</h1>
        <hr> 
        <form action="insert.php" method="post" enctype="multipart/form-data">
                        <?php 
                            if(isset($_GET['id'])) {
                                $id = $_GET['id'];
                                $stmt = $conn->query("SELECT * FROM stock_images WHERE id = $id ");
                                $stmt->execute();
                                $data = $stmt->fetch();
                            }
                        ?>

                        <div class="mb-3">
                        <input type="text" readonly value="<?= $data['id']; ?>" class="form-control" name="id" >
                            <label for="img_name" class="col-form-label">ชื่อโปรแกรม</label>
                            <input type="text" value="<?= $data['img_name']; ?>" class="form-control" name="img_name" >
                            <input type="hidden" value="<?= $data['images']; ?>" class="form-control" name="images">
                        </div>
                        <div class="mb-3">
                            <label for="img_content" class="col-form-label">คำบรรยาย</label>
                            <input type="text" value="<?= $data['img_content']; ?>" class="form-control" name="img_content">
                        </div>
                        <div class="mb-3">
                            <label for="images" class="col-form-label">รูป</label>
                            <input type="file"  class="form-control" id="imgTnput" name="images">
                            <img width="100%" src="upload/<?= $data['images']; ?>" id="previewImg" alt="">
                        </div>
                        <div class="mb-3">
                            <label for="files" class="col-form-label">ไฟล์</label>
                            <input type="file" value="<?= $data['files']; ?>" class="form-control" name="files" >
                        </div>
                        <div class="modal-footer">
                             <a href="index.php" class="btn btn-secondary">ปิด</a>
                            <button type="submit" name="update" class="btn btn-primary">ยืนยัน</button>
                        </div>
                    </form>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"crossorigin="anonymous"></script>
    <script>
        let imgTnput =document.getElementById('imgTnput');
        let previewImg =document.getElementById('previewImg');

        imgTnput.onchange = evt => {
            const [file] = imgTnput.files;
            if(file) {
                previewImg.src = URL.createObjectURL(file);
            }
        }
    </script>

</body>

</html>