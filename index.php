<html>
    <head>
        <title>Webshop</title>
            <!-- css -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
            <!-- js -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    </head>
<body>
    <?php require_once 'process.php'?>

    <?php
	$default_language_id = 1; //Default language (1 = Danish)
	if (!isset($_SESSION['language_id'])) {
		$_SESSION['language_id'] = $default_language_id;
	}		
	
    if (isset($_SESSION['message'])):		?>

    <div class="alert alert-<?=$_SESSION['msg_type']?>">

        <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        ?>
    </div>
    <?php endif ?>
    <div class="container">

    <?php 
        $mysqli = new mysqli('localhost', 'root', '', 'crud') or die(mysqli_error($mysqli));
        // Tegnsæt utf8
        mysqli_query($mysqli, "SET NAMES 'utf8'");

        $result = $mysqli->query("SELECT * FROM products INNER JOIN products_description ON products.products_id = products_description.products_id WHERE products_description.languages_id = ".$_SESSION['language_id']."") or die ($mysqli->error);

        // pre_r($result);
        ?>

        <div class="row justify-content-center">
            <table class="table">
                <thead>
                    <tr>
                        <th>Varenavn</th>
                        <th>Pris</th>
                        <th>Varenummer</th>
                    </tr>
                </thead>
                <?php
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td> <?php echo $row['products_description_name']?> </td>
                            <td> <?php echo $row['products_price']?> </td>
                            <td> <?php echo $row['products_reference']?> </td>
                            <td>
                                <a href="index.php?edit=<?php echo $row['products_id']; ?>"
                                class="btn btn-info">Ændre</a>

                                <a href="index.php?delete=<?php echo $row['products_id']; ?>"
                                class="btn btn-danger">Slet</a>

                            </td>
                        </tr>
                    <?php endwhile; ?>
            </table>
        </div>
        <?php
        // pre_r($result->fetch_assoc());

        function pre_r( $array ) {
            echo '<pre>';
            print_r($array);
            echo '</pre>';
        }
    ?>

    <div class="row justify-content-center">
    <form action="process.php" method="POST">
		<div class="form-group">
            <label>Varenavn</label>
            <input type="text" name="name" placeholder="Tilføj varenavn">
        </div>
        <div class="form-group">
            <label>Varenummer</label>
            <input type="text" name="product" placeholder="Tilføj vare">
        </div>
        <div class="form-group">
            <label>Pris</label>
            <input type="text" name="price" placeholder="Tilføj pris">
        </div>
        <div class="form-group">
            <label>Korttekst</label>
            <input type="text" name="short" placeholder="Tilføj korttekst">
        </div>
        <div class="form-group">
            <label>Langtekst</label>
            <input type="text" name="long" placeholder="Tilføj langtekst">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="save">Gem</button>
        </div>
    </form>
    </div>
    </div>
</body>
</html>