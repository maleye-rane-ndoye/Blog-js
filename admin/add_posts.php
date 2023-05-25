<?php
session_start();
@include '../components/connect.php';
$admin_id = $_SESSION['admin_id'];

    
    if(isset($_POST['publish'])){
    $name  = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $title  = htmlspecialchars(strip_tags(trim($_POST['title'])));
    $content  = htmlspecialchars(strip_tags(trim($_POST['content'])));
    $category  = htmlspecialchars(strip_tags(trim($_POST['category'])));
    $status = 'active';

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/'.$image;

    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
    $select_image->execute([$image, $admin_id]);
    $select_image->fetchAll();
  
        if(isset($image)){
            if($select_image->rowCount() > 0 AND $image != ''){
                 $message[] = 'image name repeated';
            }elseif($image_size > 2000000){
                 $message[] = 'image size is too large';
            }else{
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        }else{
            $image = '';
        }

        if($select_image->rowCount() > 0 AND $image != ''){
            $message[] = 'please rename your image';
        }else{


            $insert_post = $conn->prepare("INSERT INTO `posts`(`admin_id`, `name`, `title`, `content`, `category`, `image`, `date`, `status`)VALUES(?,?,?,?,?,?,NOW(),?)");
            $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
            $message[] = 'post published';
        }
           
}

    if(isset($_POST['draft'])){
    $name  = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $title  = htmlspecialchars(strip_tags(trim($_POST['title'])));
    $content  = htmlspecialchars(strip_tags(trim($_POST['content'])));
    $category  = htmlspecialchars(strip_tags(trim($_POST['category'])));
    $status = 'deactive';

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/'.$image;

    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
    $select_image->execute([$image, $admin_id]);
    $select_image->fetchAll();
  
        if(isset($image)){
            if($select_image->rowCount() > 0 AND $image != ''){
                 $message[] = 'image name repeated';
            }elseif($image_size > 2000000){
                 $message[] = 'image size is too large';
            }else{
                move_uploaded_file($image_tmp_name, $image_folder);
            }
        }else{
            $image = '';
        }

        if($select_image->rowCount() > 0 AND $image != ''){
            $message[] = 'please rename your image';
        }else{


            $insert_post = $conn->prepare("INSERT INTO `posts`(`admin_id`, `name`, `title`, `content`, `category`, `image`, `date`, `status`)VALUES(?,?,?,?,?,?,NOW(),?)");
            $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
            $message[] = 'draft saved';
        }
           
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add posts</title>
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
      
      <section class="post-editor">
     <h1 class="heading">add post</h1>
      <form action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="name" value="<?= $fecth_profile['name'];?>">
          <p>post title <span>*</span></p>
          <input type="text" name="title" require placeholder="add post title" maxlength="100" class="box">
          <p>post content <span>*</span></p>
          <textarea name="content" require placeholder="write your content..." maxlength="10000" class="box" cols="30" rows="10"></textarea>
          <p>post category <span>*</span></p>
          <select name="category" class="box" require>
                <option value="" desabled selected>--select post category</option>
                <option value="nature">nature</option>
                <option value="education">education</option>
                <option value="pets and animals">pets and animals</option>
                <option value="technologie">technologie</option>
                <option value="fashion">fashion</option>
                <option value="entertainment">entertainment</option>
                <option value="movies and animations">movies and animations</option>
                <option value="gaming">gaming</option>
                <option value="sports">sports</option>
                <option value="music">music</option>
                <option value="news">news</option>
                <option value="travel">travel</option>
                <option value="comedy">comedy</option>
                <option value="design and developpement">design and developpement</option>
                <option value="foot and drinks">foot and drinks</option>
                <option value="lifrstyle">lifestyle</option>
                <option value="personal">personal</option>
                <option value="health and fitness">health and fitness</option>
                <option value="business">business</option>
                <option value="shopping">shopping</option>
                <option value="animations">animations</option>
          </select>  
          <p>post image</p>
          <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
          <div class="flex-btn">
           <input type="submit" name="publish" value="publish post" class="btn">
           <input type="submit" name="draft" value="save draft" class="option-btn"> 

          </div>


      </form>

</section>















</body>
</html>