<?php

session_start();

$mysqli = new mysqli('localhost', 'root', '', 'crud') or die(mysqli_error($mysqli));
mysqli_query($mysqli, "SET NAMES 'utf8'");

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
    
    $language_result = $mysqli->query("SELECT * FROM languages WHERE languages_id != ".$_SESSION['language_id']."") or die ($mysqli->error);
	while ($row = $language_result->fetch_assoc()){
        $mysqli->query("INSERT INTO products_description (products_id, languages_id, products_description_name, products_description_short_description, products_description_description) VALUES('$last_id', ".$row['languages_id'].", 'UNKNOWN', 'UNDEFINED', 'UNDEFINED')") or
        die($mysqli->error);
    }
		
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

if (isset($_POST['edit'])){
    $name = $_POST['name'];
    $product = $_POST['product'];
    $price = $_POST['price'];
	$short = $_POST['short'];
    $long = $_POST['long'];
    $products_id = $_POST['id'];

    $mysqli->query("UPDATE products SET products_reference = '$product', products_price = '$price' WHERE products_id = $products_id") or
        die($mysqli->error);
		$last_id = $mysqli->insert_id;
	$mysqli->query("UPDATE products_description SET products_description_name = '$name', products_description_short_description = '$short', products_description_description = '$long' WHERE products_id = $products_id AND languages_id = ".$_SESSION['language_id']."") or
        die($mysqli->error);
		
        $_SESSION['message'] = "Gemt!";
        $_SESSION['msg_type'] = "success";

        header("location: index.php");
}