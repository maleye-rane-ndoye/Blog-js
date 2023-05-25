<?php

@include '../components/connect.php';
session_start();

if(isset($_POST['submit'])){
    $name  = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $pass = htmlspecialchars(strip_tags(trim($_POST['pass'])));

    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
    $select_admin->execute([$name, $pass]);

    if($select_admin->rowCount() > 0){
        $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
        $_SESSION['admin_id'] = $fetch_admin_id['id'];
        header('location:dashboard.php');
    }else{
        $message[] = 'incorrect username or password';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <script src="../js/admin_script.js" defer></script>
</head>
<body style="padding-left: 0;">

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
        <h3>login now</h3>
        <p>default usernam = <span>admin</span> & password = <span>111</span></p>

         <input type="text" require placeholder="Enter your username" maxlength="20" class="box" 
                name="name" oninput="this.value = this.value.replace(/\s/g, '')">

         <input type="password" require placeholder="Enter your password" maxlength="20" class="box" 
                name="pass" oninput="this.value = this.value.replace(/\s/g, '')">

         <input type="submit" name="submit" value="login now" class="btn">

      </form>
</section>















</body>
</html>