<?php

session_start();

$mysqli = new mysqli('localhost', 'root', '', 'crud') or die(mysqli_error($mysqli));

if (isset($_POST['save'])){
    $name = $_POST['name'];
    $product = $_POST['product'];
    $price = $_POST['price'];
	$short = $_POST['short'];
	$long = $_POST['long'];

    $mysqli->query("INSERT INTO products (products_reference, products_price) VALUE('$product', '$price')") or
        die($mysqli->error);
		$last_id = $mysqli->insert_id;
	$mysqli->query("INSERT INTO products_description (products_id, languages_id, products_description_name, products_description_short_description, products_description_description) VALUES('$last_id', ".$_SESSION['language_id'].", '$name', '$short', '$long')") or
        die($mysqli->error);
		
        $_SESSION['message'] = "Gemt!";
        $_SESSION['msg_type'] = "success";

        header("location: index.php");
}

if (isset($_GET['delete'])){
    $id = $_GET['delete'];
    $mysqli->query("DELETE products, products_description FROM products INNER JOIN products_description ON products.products_id = products_description.products_id  WHERE products.products_id=$id") or die($mysqli->error);

    $_SESSION['message'] = "Slettet!";
    $_SESSION['msg_type'] = "danger";

    header("location: index.php");
}