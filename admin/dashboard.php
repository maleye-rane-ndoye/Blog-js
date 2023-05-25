<?php

@include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <script src="../js/admin_script.js" defer></script>
</head>
<body>
      <?php @include '../components/admin_header.php';?>


<section class="dashboard">

    <h1 class="heading">dashboard</h1>

    <div class="box-container">

         <div class="box">
            <h3>Welcome</h3>
            <p><?= $fecth_profile['name']; ?></p>
            <a href="update_profile.php" class="btn">Update Profile</a>
         </div>

         <div class="box">
            <?php           
            $select_posts = $conn->prepare("SELECT *FROM `posts` WHERE admin_id = ?");
            $select_posts->execute([$admin_id]);
            $number_of_posts = $select_posts->rowCount();
            ?>
            <h3><?= $number_of_posts; ?></h3>
            <p>total posts added</p>
            <a href="add_posts.php" class="btn">add new post</a>
         </div>

         <div class="box">
            <?php           
            $select_active_posts = $conn->prepare("SELECT *FROM `posts` WHERE admin_id = ? AND status = ?");
            $select_active_posts->execute([$admin_id, 'active']);
            $number_of_active_posts =  $select_active_posts->rowCount();
            ?>
            <h3><?= $number_of_active_posts; ?></h3>
            <p>active posts</p>
            <a href="view_posts.php" class="btn">View posts</a>
         </div>

          <div class="box">
            <?php           
            $select_deactive_posts = $conn->prepare("SELECT *FROM `posts` WHERE admin_id = ? AND status = ?");
            $select_deactive_posts->execute([$admin_id, 'deactive']);
            $number_of_deactive_posts =  $select_active_posts->rowCount();
            ?>
            <h3><?= $number_of_deactive_posts; ?></h3>
            <p>deactive posts</p>
            <a href="view_posts.php" class="btn">View posts</a>
         </div>


         <div class="box">
            <?php           
            $select_users = $conn->prepare("SELECT *FROM `users`");
            $select_users->execute();
            $number_of_users =  $select_users->rowCount();
            ?>
            <h3><?= $number_of_users; ?></h3>
            <p>total users</p>
            <a href="users_accounts.php" class="btn">View users</a>
         </div>

         <div class="box">
            <?php           
            $select_admins = $conn->prepare("SELECT *FROM `admin`");
            $select_admins->execute();
            $number_of_admins =  $select_admins->rowCount();
            ?>
            <h3><?= $number_of_admins; ?></h3>
            <p>total admins</p>
            <a href="admin_accounts.php" class="btn">View admins</a>
         </div>

         <div class="box">
            <?php           
            $select_comments = $conn->prepare("SELECT *FROM `comments` WHERE admin_id = ?");
            $select_comments->execute([$admin_id]);
            $number_of_comments =  $select_comments->rowCount();
            ?>
            <h3><?= $number_of_comments; ?></h3>
            <p>total comments</p>
            <a href="comments.php" class="btn">View comments</a>
         </div>

         <div class="box">
            <?php           
            $select_likes = $conn->prepare("SELECT *FROM `likes` WHERE admin_id = ?");
            $select_likes->execute([$admin_id]);
            $number_of_likes =  $select_likes->rowCount();
            ?>
            <h3><?= $number_of_likes; ?></h3>
            <p>total likes</p>
            <a href="view_posts.php" class="btn">View posts</a>
         </div>


    </div>


</section>

















</body>
</html>