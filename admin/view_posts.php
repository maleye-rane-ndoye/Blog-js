<?php

@include '../components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
}

if(isset($_POST['delete'])){
    $delete_id = htmlspecialchars(strip_tags(trim($_POST['post_id'])));
    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ?");
    $select_image->execute([$delete_id]);
    $fetch_image = $select_image->fetchAll(PDO::FETCH_ASSOC);
    if($fetch_image['image'] != ''){
        unlink('../uploaded_img/'.$fetch_image['image']);
    }
    $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE post_id = ?");
    $delete_comments->execute([$delete_id]);

    $delete_likes = $conn->prepare("DELETE FROM `likes` WHERE post_id = ?");
    $delete_likes->execute([$delete_id]);

    $delete_post = $conn->prepare("DELETE FROM `posts` WHERE id = ?");
    $delete_post->execute([$delete_id]);

    $message[] = 'post deleted successfull !';
  
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view posts</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <script src="../js/admin_script.js" defer></script>
</head>
<body>
      <?php @include '../components/admin_header.php';?>


<section class="show-posts">

      <h1 class="heading">Your posts</h1>
      <form action="search_page.php" method="POST" class="search-form">
          <input type="text" placeholder="search post ..." require maxlength="100" name="search_box">      
          <button class="fa fa-search" name="search-btn"></button>
      </form>
      <div class="box-container">
        <?php
        $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ?");
        $select_posts->execute([$admin_id]);
        if($select_posts->rowCount() > 0){
             while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                $post_id = $fetch_posts['id'];

                $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE  post_id = ?");
                $count_post_comments->execute([$post_id]);
                $total_post_comments = $count_post_comments->rowCount();

                $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE  post_id = ?");
                $count_post_likes->execute([$post_id]);
                $total_post_likes = $count_post_likes->rowCount();
        ?> 
               <form action="" method="POST" class="box">

                        <input type="hidden" name="post_id" value="<?=$post_id;?>">
                        <div class="status" style="background-color: <?php
                                if($fetch_posts['status'] == 'active'){
                                    echo 'limegreen';
                                }else{
                                    echo 'coral';
                                }
                                
                                ?>;">
                                <?= $fetch_posts['status'] ?>
                        </div>
                        <?php
                            if($fetch_posts['image'] != ''){
                            ?> 
                            <img src="../uploaded_img/<?=$fetch_posts['image']?>" alt="image illustre" class="image">
                        <?php
                            }
                        ?>
                        
                        <div class="post-title"> <?= $fetch_posts['title'] ?></div>
                        <div class="post-content"> <?= $fetch_posts['content'] ?></div>
                        <div class="icons">
                            <div><i class="fa fa-comment"></i><span><?= $total_post_comments?></span></div>
                            <div><i class="fa fa-heart"></i><span><?= $total_post_likes?></span></div>
                        </div>
                        <div class="flex-btn">
                            <a href="edit_post.php?post_id =<?= $post_id;?>" class="option-btn">edit</a>
                            <button type="submit" name="delete" onclick="return confirm('delete this post ?')" class="delete-btn">delete</button>
                        </div>
                        <a href="read_post.php?post_id =<?= $post_id;?>" class="btn">read post</a>
               </form>
        <?php
             }
        }else{
            echo '<p class="empty">no post added yet</p>';
        }
       
        ?>
      </div>

</section>
















</body>
</html>