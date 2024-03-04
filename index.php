<?php 

 session_start();
 require_once("config/db_connect");

 if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    // ใช้ prepare statement เพื่อป้องกัน SQL Injection
    $deletestmt = $conn->prepare("DELETE FROM stock_images WHERE id = :delete_id");
    $deletestmt->bindParam(":delete_id", $delete_id);
    $deletestmt->execute();

    if ($deletestmt) {
        echo "<script>alert('Data has been deleted successfully');</script>";
        $_SESSION['success'] = "Data has been deleted succesfully";
        header("refresh:1; url=index.php");
    } else {
        echo "<script>alert('Failed to delete data');</script>";
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
</head>

<body>

    <!-- Add data -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <!-- enctype="multipart/form-data" เป็นการเพิ่มรูปภาพ -->
                    <form action="insert.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="img_name" class="col-form-label">ชื่อโปรแกรม</label>
                            <input type="text" class="form-control" name="img_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="img_content" class="col-form-label">คำบรรยาย</label>
                            <input type="text" class="form-control" name="img_content">
                        </div>
                        <div class="mb-3">
                            <label for="images" class="col-form-label">รูป</label>
                            <input type="file" class="form-control" id="imgTnput" name="images" required>
                            <img width="100%"  id="previewImg" alt="">
                        </div>
                        <div class="mb-3">
                            <label for="files" class="col-form-label">ไฟล์</label>
                            <input type="file" class="form-control" name="files" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="submit" name="submit" class="btn btn-primary">ยืนยัน</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>CRI</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">เพิ่ม</button>
            </div>
        </div>
        <?php if(isset($_SESSION['success'])) { ?>
            <div class="alert alert-success my-3">
                    <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
            </div>
        <?php } ?>
        <?php if(isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
            </div>
        <?php } ?>

        <table class="table table-striped mt-5">
  <thead>
    <tr>
      <th scope="col">ลำดับ</th>
      <th scope="col">ชื่อโปรแกรม</th>
      <th scope="col">รายละเอียด</th>
      <th scope="col">รูป</th>
      <th scope="col">ไฟล์</th>
      <th scope="col">เครื่องมือ</th>
    </tr>
  </thead>
  <tbody>
    <?php 
        $stmt = $conn->query("SELECT * FROM stock_images");
        $stmt->execute();
        $stocks = $stmt->fetchAll();

        if(!$stocks) {
            echo "<tr><td colspan='6' class='text-center'>ไม่มีข้อมูล</td></tr>";
        }else{
            foreach($stocks as $stock_images) {
    ?>
                <tr>
                <th scope="row"><?= $stock_images['id'];?></th>
                <td><?= $stock_images['img_name'];?></td>
                <td><?= $stock_images['img_content'];?></td>
                <td width="250px"><img width="50%" src="upload/<?=$stock_images['images'];?>" class="rounded" alt=""></td>
                <td><?= $stock_images['files'];?></td>
               <td>
                <a href="edit.php?id=<?= $stock_images['id']; ?>" class="btn btn-warning">แก้ไข</a>
                <a href="?delete=<?= $stock_images['id']; ?>" onclick="return confirm('คุณต้องการลบใช่หรือไม่ ?');" class="btn btn-danger">ลบ</a>

            </td>
              </tr>
          <?php  }
        }   ?>

  </tbody>
</table>

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