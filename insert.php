<?php

session_start();
require_once("config/db_connect");

if (isset($_POST['submit'])) {
    $img_name = $_POST['img_name'];
    $img_content = $_POST['img_content'];
    $images = $_FILES['images'];
    $files = $_FILES['files'];

    // Handle file upload for 'files'
    $allow2 = array('pdf', 'exe');
    $extension2 = explode('.', $files['name']);
    $fileActExt2 = strtolower(end($extension2));
    $fileNew2 = $files['name'];
    $filesPath2 = "upload_files/" . $fileNew2;

    // Handle file upload for 'images'
    $allow = array('jpg', 'jpeg', 'png');
    $extension = explode('.', $images['name']);
    $fileActExt = strtolower(end($extension));
    $fileNew = $_FILES['images'] . "." . $fileActExt;
    $filePath = "upload/" . $fileNew;

    if (in_array($fileActExt, $allow)) {
        if ($images['size'] > 0 && $images['error'] == 0) {
            if (move_uploaded_file($images['tmp_name'], $filePath) && move_uploaded_file($files['tmp_name'], $filesPath2)) {
                // Prepare and execute SQL query
                $sql = $conn->prepare("INSERT INTO stock_images (img_name, img_content, images, files) VALUES (:img_name, :img_content, :images, :files)");
                $sql->bindParam(":img_name", $img_name);
                $sql->bindParam(":img_content", $img_content);
                $sql->bindParam(":images", $fileNew);
                $sql->bindParam(":files", $fileNew2);
                $sql->execute();

                // Check if the query was successful
                if ($sql) {
                    $_SESSION["success"] = "เพิ่มสำเร็จแล้ว";
                    header("location: index.php");
                    exit;
                } else {
                    $_SESSION["error"] = "เพิ่มไม่สำเร็จ";
                    header("location: index.php");
                    exit;
                }
            }
        }
    }
}

// Add any additional cleanup or redirection logic here if needed

?>
