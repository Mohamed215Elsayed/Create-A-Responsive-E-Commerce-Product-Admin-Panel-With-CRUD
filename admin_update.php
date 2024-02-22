<?php
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    $id = $_GET['edit'];
    if(isset($_POST['update_product'])){
        $product_name = htmlspecialchars($_POST['product_name']);
        $product_price = $_POST['product_price'];
        $product_image = $_FILES['product_image']['name'];
        $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
        $product_image_folder = 'uploaded_img/'.$product_image;
        if(empty($product_name) || empty($product_price) || empty($product_image)){
            $message[] = 'please fill out all!';    
        }
        else{
            $update_data = "UPDATE `products` SET name='$product_name', price='$product_price', image='$product_image'  WHERE id = '$id'";
            $upload = $conn->prepare($update_data);
            $upload->execute();
            if($upload){
                move_uploaded_file($product_image_tmp_name, $product_image_folder);
                header('location:admin_page.php');
            }
            else{
                $$message[] = 'please fill out all!'; 
            }
        }
    };
        ?>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<span class="message">' . $message . '</span>';
        }
    }
    ?>

    <div class="container">
        <div class="admin-product-form-container centered">
            <?php
            $select = "SELECT * FROM `products` WHERE id = :id";
            $stmt = $conn->prepare($select);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $products = $stmt->fetchAll();
            foreach ($products as $row) { ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <h3 class="title">update the product</h3>
                    <input type="text" class="box" name="product_name" value="<?php echo $row['name']; ?>" placeholder="enter the product name">
                    <input type="number" min="0" class="box" name="product_price" value="<?php echo $row['price']; ?>" placeholder="enter the product price">
                    <input type="file" class="box" name="product_image" accept="image/png, image/jpeg, image/jpg">
                    <input type="submit" value="update product" name="update_product" class="btn">
                    <a href="admin_page.php" class="btn">go back!</a>
                </form>
            <?php }; ?>
        </div>
    </div>
</body>
</html>