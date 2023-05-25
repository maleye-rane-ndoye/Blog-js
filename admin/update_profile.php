<?php

@include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
}

if(isset($_POST['submit'])){

    $name  = htmlspecialchars(strip_tags(trim($_POST['name'])));
    if(!empty($name)){
        $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
        $select_name->execute([$name]);
        if($select_name->rowCount() > 0){
            $message[] = 'username already taken!';
        }else{
            $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
            $update_name->execute([$name, $admin_id]);
            $message[] = 'username updated!' ;
        }
    }
    $empty_pass = '';
    $select_prev_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
    $select_prev_pass->execute([$admin_id]);
    $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
    $prev_pass = $fetch_prev_pass['password'];
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $c_pass = $_POST['c_pass'];
    if($old_pass != $empty_pass){
        if($old_pass != $prev_pass){
            $message[] = 'old password not matched !';
        }elseif($new_pass != $c_pass){
            $message[] = 'confirm password not matched ';
;        }else{
            if($new_pass != $empty_pass){
              
                $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
                $update_pass->execute([$c_pass, $admin_id]);
                $message[] = 'password updated!' ;

            }else{
                $message[] = 'please enter new password';
            }
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
    <title>update profile</title>
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
            <h3>update</h3>

            <input type="text" placeholder="<?= $fecth_profile['name']?>" maxlength="20" class="box" 
                    name="name" oninput="this.value = this.value.replace(/\s/g, '')">

            <input type="password" placeholder="Enter your old password" maxlength="20" class="box" 
                    name="old_pass" oninput="this.value = this.value.replace(/\s/g, '')">

            <input type="password"  placeholder="Enter your new password" maxlength="20" class="box" 
                    name="new_pass" oninput="this.value = this.value.replace(/\s/g, '')">

            <input type="password" placeholder="confirm your new password" maxlength="20" class="box" 
                    name="c_pass" oninput="this.value = this.value.replace(/\s/g, '')">



            <input type="submit" name="submit" value="update now" class="btn">

</form>
</section>

















</body>
</html>