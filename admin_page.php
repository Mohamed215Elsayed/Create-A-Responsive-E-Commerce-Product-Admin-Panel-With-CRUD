<?php
ob_start();
@include("connect.php");
?>
<?php
    if (isset($_POST['add_product'])) {
        $product_name = htmlspecialchars($_POST['product_name']);
        $product_price = htmlspecialchars($_POST['product_price']);
        $product_image = $_FILES['product_image']['name'];
        $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
        $product_image_folder = 'uploaded_img/' . $product_image;
        if (empty($product_name) || empty($product_price) || empty($product_image)) {
            $message[] = 'please fill out all';
        }
        else {
            $insert = "INSERT INTO `products`(name, price, image) VALUES(:product_name, :product_price, :product_image)";
            $stmt = $conn->prepare($insert);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':product_price', $product_price);
            $stmt->bindParam(':product_image', $product_image);
            if($stmt->execute()){
                move_uploaded_file($product_image_tmp_name, $product_image_folder);
                $message[] = 'new product added successfully';
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
            else{
                $message[] = 'could not add the product';
            }
        }

    };

    if(isset($_GET['delete'])){
        $id = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM `products` WHERE `id` = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('location:admin_page.php');
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<span class="message">' . $message . '</span>';
        }
    }
    ?>
    <div class="container">
        <div class="admin-product-form-container">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <h3>add a new product</h3>
                <input type="text" placeholder="enter product name" name="product_name" class="box">
                <input type="number" placeholder="enter product price" name="product_price" class="box">
                <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box">
                <input type="submit" class="btn" name="add_product" value="add product">
            </form>
        </div>
<!-- ======================================================================== -->
        <?php
        $query = "SELECT * FROM `products` ";
        $statement = $conn->query($query);
        $statement->execute();
        $products =  $statement->fetchAll();
        ?>
        <div class="product-display">
            <table class="product-display-table">
                <thead>
                    <tr>
                        <th>product image</th>
                        <th>product name</th>
                        <th>product price</th>
                        <th>action</th>
                    </tr>
                </thead>
                    <?php
                    foreach ($products as $row) { ?>
                        <tr>
                            <td><img src="uploaded_img/<?php echo $row['image']; ?>" height="100" alt=""></td>
                            <td><?php echo $row['name']; ?></td>
                            <td>$<?php echo $row['price']; ?></td>
                            <td>
                                <a href="admin_update.php?edit=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-edit"></i> edit </a>
                                <a href="admin_page.php?delete=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-trash"></i> delete </a>
                            </td>
                        </tr>
                    <?php } ?>
        </table>
    </div>
    </div>
</body>

</html>
<?php ob_end_flush(); ?>