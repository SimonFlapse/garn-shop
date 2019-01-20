<html>
    <head>
        <title>Webshop</title>
            <!-- css -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
            <!-- js -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    </head>
<body>
    <?php require_once 'process.php'?>

    <?php
	$default_language_id = 1; //Default language (1 = Danish)
	if (!isset($_SESSION['language_id'])) {
		$_SESSION['language_id'] = $default_language_id;
	} elseif (isset($_GET['language'])){
		$_SESSION['language_id'] = $_GET['language'];
	}
	
    if (isset($_SESSION['message'])):?>

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
                    while ($row = $result->fetch_assoc()):
                        
                        $result_default = $mysqli->query("SELECT * FROM products INNER JOIN products_description ON products.products_id = products_description.products_id WHERE products_description.languages_id = $default_language_id AND products.products_id = ".$row['products_id']."") or die ($mysqli->error);
                        $row_default = $result_default->fetch_assoc()
                    ?>
                        <tr>
                            <td> <?php echo $row['products_description_name']?> </td>
                            <td> <?php echo $row['products_price']?> </td>
                            <td> <?php echo $row['products_reference']?> </td>
                            <td>

                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal<?php echo $row['products_id']?>">Rediger</button>
                                <div id="myModal<?php echo $row['products_id']?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Redigere vare</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row justify-content-center">
                                                    <form action="process.php" method="POST">
                                                    <div class="form-group">
                                                        <label>Varenummer</label>
                                                        <input type="text" name="product" value="<?php echo $row['products_reference']?>">
                                                    </div> 
                                                    <div class="form-group">
                                                        <label>Pris</label>
                                                        <input type="text" name="price" value="<?php echo $row['products_price']?>">
                                                    </div>
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-sm">
                                                                <div class="form-group">
                                                                    <label>Varenavn</label>
                                                                    <input type="hidden" name="id" value="<?php echo $row['products_id']?>">
                                                                    <input type="text" name="name" value="<?php echo $row['products_description_name']?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Korttekst</label> <br>
                                                                    <textarea rows="4" cols="40" style="resize: none" type="text" name="short"><?php echo $row['products_description_short_description']?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Langtekst</label> <br>
                                                                    <textarea rows="8" cols="40" style="resize: none" type="text" name="long"><?php echo $row['products_description_description']?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm">
                                                                <div class="form-group">
                                                                    <label>Varenavn</label>
                                                                    <input type="text" value="<?php echo $row_default['products_description_name']?>" disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Korttekst</label> <br>
                                                                    <textarea rows="4" cols="40" style="resize: none" type="text" disabled><?php echo $row_default['products_description_short_description']?></textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Langtekst</label> <br>
                                                                    <textarea rows="8" cols="40" style="resize: none" type="text" disabled><?php echo $row_default['products_description_description']?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary" name="edit">Gem</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#slet<?php echo $row['products_id']?>">Slet</button>
                                <div id="slet<?php echo $row['products_id']?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Bekræft sletning</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row justify-content-center">
                                                    <a href="index.php?delete=<?php echo $row['products_id']; ?>" class="btn btn-danger" style="margin-left: 2px; margin-right: 2px; margin-bottom: 2px; margin-top: 2px;">Bekræft</a>
                                                    <button type="submit" class="btn btn-primary" data-dismiss="modal" style="margin-left: 2px; margin-right: 2px; margin-bottom: 2px; margin-top: 2px;">Annuller</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
            </table>
        </div>

        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#nyVare">Ny vare</button>
        <div id="nyVare" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ny vare</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <form action="process.php" method="POST">
                            <div class="form-group">
                                <label>Varenummer</label>
                                <input type="text" name="product" placeholder="Tilføj vare">
                            </div>
                            <div class="form-group">
                                <label>Pris</label>
                                <input type="text" name="price" placeholder="Tilføj pris">
                            </div>
                            <div class="form-group">
                                <label>Varenavn</label>
                                <input type="text" name="name" placeholder="Tilføj varenavn">
                            </div>
                            <div class="form-group">
                                <label>Korttekst</label> <br>
                                <textarea rows="4" cols="50" style="resize: none" type="text" name="short" placeholder="Tilføj korttekst"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Langtekst</label> <br>
                                <textarea rows="8" cols="50" style="resize: none" type="text" name="long" placeholder="Tilføj langtekst"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="save">Gem</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

	<div class="footer fixed-bottom">
	<div class="row justify-content-center">
	<?php
	$language_result = $mysqli->query("SELECT * FROM languages") or die ($mysqli->error);
	while ($row = $language_result->fetch_assoc()):
	?>
	
	<a href="index.php?language=<?php echo $row['languages_id']?>" class="btn btn-warning" style="margin-left: 2px; margin-right: 2px; margin-bottom: 2px; margin-top: 2px;"><?php echo $row['languages_name']?></a>
	
	<?php endwhile;?>
	</div>
	</div>
    </div>	
</body>
</html>