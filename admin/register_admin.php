<?php

@include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
}

if(isset($_POST['submit'])){
    $name  = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $pass = htmlspecialchars(strip_tags(trim($_POST['pass'])));
    $cpass = htmlspecialchars(strip_tags(trim($_POST['cpass'])));


    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
    $select_admin->execute([$name]);

    if($select_admin->rowCount() > 0){
        $message[] = 'user already exist';
    }else{
         if($pass != $cpass){
            $message[] = 'confirm password not matched';
         }else{
            $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password)VALUES(?,?)");
            $insert_admin->execute([$name,$cpass]);
            $message[] = 'new admin registed';
         }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <script src="../js/admin_script.js" defer></script>
</head>
<body>
      <?php @include '../components/admin_header.php';?>

    <?php
         if(isset($message)){
            foreach($message as $message){

                echo'<div class="message">
                        <span>'.$message.'</span>
                        <i class="fa fa-times" onclick="this.parentElement.remove();"></i>
                    </div>';
            }
         }
    ?>


<section class="form-container">

        <form action="" method="POST">
        <h3>register</h3>

        <input type="text" require placeholder="Enter your username" maxlength="20" class="box" 
                name="name" oninput="this.value = this.value.replace(/\s/g, '')">

        <input type="password" require placeholder="Enter your password" maxlength="20" class="box" 
                name="pass" oninput="this.value = this.value.replace(/\s/g, '')">

        <input type="password" require placeholder="confirm your password" maxlength="20" class="box" 
                name="cpass" oninput="this.value = this.value.replace(/\s/g, '')">


        <input type="submit" name="submit" value="register now" class="btn">

        </form>
</section>

















</body>
</html>