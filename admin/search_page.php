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
    <title>research page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <script src="../js/admin_script.js" defer></script>
</head>
<body>
      <?php @include '../components/admin_header.php';?>


<section class="show-posts">

            <h1 class="heading">Search page</h1>
            <form action="search_page.php" method="POST" class="search-form">
                <input type="text" placeholder="search post ..." require maxlength="100" name="search_box">      
                <button class="fa fa-search" type="submit" name="search_btn"></button>
            </form>
            <div class="box-container">
            <?php

            if(isset($_POST['search_box']) or isset($_POST['search_btn'])){
            $search_box = $_POST['search_box'];
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ? AND title LIKE '%{$search_box}%'");
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
                echo '<p class="empty">no post found !</p>';
            }
        }
            ?>
            </div>

</section>
















</body>
</html>