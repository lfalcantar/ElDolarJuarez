<?php
    session_start();
    require_once("../php/connection.php");
    $connection = new connection();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">

    <link href="../css/boxes.css" rel="stylesheet">
    <!-- BOOTSTRAP -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header" id="navbar-id">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">El Dólar Juárez</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="CasasDeCambio">Casas de Cambio</a>
                    </li>
                    <li>
                        <a href="Bancos">Bancos</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
	<div class="container">
		<h3 class="center">UPDATE DATABASE</h3>
		<?php
			// Test the session to see if is_auth flag was set (meaning they logged in successfully)
			// If test fails, send the user to login.php and prevent rest of page being shown.
			if (!isset($_SESSION["is_auth"])) {
				header("location: ../loginEntrar.php");
			}else{

                $postArray =  array();
                $itChanged =  array();

                $count=0;
                echo '<div class="form-style-5">';
                    echo '<form method="POST"  action="'.$_SERVER['PHP_SELF'].'">';
                    $rows =  $connection->updateRows();
                    while($row = $rows->fetch_assoc()){

                        if($count % 2 == 0){
                            if($count != 0){
                                echo '</div>';
                            }
                            echo '<div class="row top-buffer">';
                        }
                        $i = $row["clave"];
                        echo '<div id="'.$i.'"  class=" unique-'.$i.' custoim_box custom_border col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div id="parent-'.$i.'" class="center">
                                <p>Disponible <input id="disponible-'.$i.'" type="checkbox" '.($row["disponible"] == 1 ? 'checked' : '').'></p>
                                <p>Nombre <input type="text" id="nombre-'.$i.'" name="nombre'.$i.'" value="'.$row["nombre"].'"></p>
                                <p>Clave <input  disabled type="text" id="clave-'.$i.'" name="clave_'.$i.'" value="'.$row["clave"].'"></p>
                                <p>Compra <input type="text" id="compra-'.$i.'" name="compra_'.$i.'" value="'.$row["compra"].'"></p>
                                <p>Venta <input type="text" id="venta-'.$i.'" name="venta_'.$i.'" value="'.$row["venta"].'"></p>
                                <p>Direccion <input type="text" id="direccion-'.$i.'" name="direccion'.$i.'" value="'.$row["direccion"].'"></p>
                                <p>Zona <input type="text" id="zona-'.$i.'" name="zona'.$i.'" value="'.$row["zona"].'"></p>
                                <p>Latitude <input type="text" id="latitude-'.$i.'" name="latitude'.$i.'" value="'.$row["latitude"].'"></p>
                                <p>Longitude <input type="text" id="longitude-'.$i.'" name="longitude'.$i.'" value="'.$row["longitude"].'"></p>
                                <p>Horarios <input type="text" id="horarios-'.$i.'" name="horarios'.$i.'" value="'.$row["horario"].'"></p>
                                <i><strong>note:</strong>separa horarios con "|"</i>
                                <input id="'.$i.'" value="Update"  type="button" onClick="submitCasa(this.id)">
                                <input id="'.$i.'" type="button" value="Delete" onClick="deleteCasa(this.id)">

                                </div>
                              </div>';

                        $count++;
                    }

                    echo'<div id="addingForm"  onclick="addNewform()" class="newform col-lg-6 col-md-6 col-sm-12 col-xs-12"">
                        <div class="cssCircle plusSign">
                          &#43;
                        </div>
                    </div>';
                echo '</form></div>';
            }
		?>
	</div>
	<!-- scripts-->
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script>document.write('<script src="../js/update.js" type="text/javascript"><\/script>')</script>

</body>
</html>

