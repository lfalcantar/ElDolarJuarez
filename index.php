<?php
    session_start();
    if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }

	require_once("php/connection.php");
	$connection = new connection();

    // Check if there is a session store
    $resultTable = $connection->allRows();
    $arr_rows = array();
    while($row = $resultTable->fetch_assoc()){
        $arr_rows[$row['clave']] = array('NOMBRE'=>$row['nombre'],'VENTA' => $row['venta'],'COMPRA' =>  $row['compra'],'ZONA' => $row['zona'],'LAT' => $row['latitude'],'LONG' =>  $row['longitude']);
    }
    $_SESSION["rows"] = $arr_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	  (adsbygoogle = window.adsbygoogle || []).push({
	    google_ad_client: "ca-pub-2271584820398288",
	    enable_page_level_ads: true
	  });
	</script>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
   	<title>El Dólar Juarez, La Mejor Compra y Venta de Dólares</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta property="og:title" content="El Dólar Juárez"/>
	<meta property="og:description" content="Hablamos sobre el dólar en nuestra ciudad, hacemos mapas de casas de cambios y sus mejores tarifas día a día. Un gusto poderles compartir para que su dinero tenga mejor rendimiento"/>
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://eldolarjuarez.com"/>
	<meta property="og:image" content="https://eldolarjuarez.com/imgs/og-img.png"/>
	<meta name="description" content="Encuentra el mejor precio para vender y comprar dólares en Cd. Juárez. Usando tu ubicación  encuentra la casa de cambio con mejor venta y compra cerca de ti." />
	<meta name="ROBOTS" content="INDEX">
	<meta name="revisit-after" content="1 days">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <link rel="icon" href="imgs/favicon.ico">

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/one-page-v2.css" rel="stylesheet">

    <!-- DATA TABLE  -->
   	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
</head>

<body>

    <!-- Navigation -->
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
                        <a href="acerca.php">Acerca </a>
                    </li>
                    <li>
                        <a href="index.php#services"> <span class="glyphicon glyphicon-map-marker"></span> Mapa </a>
                    </li>
                    <li>
                        <a href="index.php#acerca"><span class="glyphicon glyphicon-send"></span> Contacto </a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

<!--    <!-- Full Width Image Header -->
<!--    <header class="header-image">-->
<!--        <div class="headline">-->
<!--            <div class="container">-->
<!--                <h3>Espacio Publicitario</h3>-->
<!--            </div>-->
<!--        </div>-->
<!--    </header>-->

    <!-- Page Content -->
    <div class="container">
        <!-- Mejor Compra y Venta Featurette -->
        <div class="featurette" id="mejorVenta">
			<div class="row">
	            <div class="topbuffer ">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <img class="logo" src="imgs/LogoV.png" alt="logo" >
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                            <h5 class="featurette-heading"><span class="text-muted ">Mejor Venta y Compra
                                 de dólares</span> En Todo Juarez
                            </h5>
                        </div>
                    </div>
                </div>
		        <!-- Mejor VENTA-COMPRA -->
                <?php $rowSell = $connection->bestSell(); $rowBuy = $connection->bestBuy();?>
                <div class="topbuffer col-lg-12 col-md-12 col-sm-12 col-xs-12">
		        	<div class="row">
                        <a href="https://maps.google.com/?q=0,0" target="_blank" >
                            <div class=" venta col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="border-venta row">
                                    <div class="vertical-text col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        VENTA
                                    </div>
                                    <div class="monto col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        $<?php echo $rowSell['VENTA'];?>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="https://maps.google.com/?q=0,0" target="_blank" >
                            <div class=" compra col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="border-compra row">
                                    <div class="vertical-text col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        COMPRA
                                    </div>
                                    <div class="monto col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        $<?php echo $rowBuy['COMPRA'];?>
                                    </div>
                                </div>
                            </div>
                        </a>
					</div>
	            </div>
                <div class="row center faceupdate">
                    <div class=" col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="facebook ">
                                <span > <a href="https://www.facebook.com/eldolarjuarez/"> Síguenos en Facebook</a>
                                <div  class="fb-like " data-href="https://www.facebook.com/eldolarjuarez/" data-width="100px" data-layout="button" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="ultima-actualizacion">
                            Ultima actualización - <?php $rowLastUpdate = $connection->lastUpdate();echo $rowLastUpdate['LAST_UPDATE'];?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="featurette-divider removeTopMargin">

        <!-- MAP Featurette -->
        <div class="featurette" id="services">
            <!--Google Map-->
            <h2 class="featurette-heading"><span class="text-muted">Mejor Venta y Compra
                de dólares</span> Cerca De Ti.
            </h2>
            <p class="lead"> Encuentra cuál es la mejor compra y venta de dolares, utilizando tu ubicación. Solo haz click y te mandamos con la ruta, directo a la casa de cambio.</p>
            <div class="row">

            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            		<a href="#mejorventacerca"> <div class="center action-button shadow animate green" onclick="verVenta()" id="mejorventacerca">
            			Mejor Venta Cerca
            		</div></a>
            	</div>
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            		<a href="#mejorcompracerca"> <div class="center action-button shadow animate green" onclick="verCompra()" id="mejorcompracerca">
            			Mejor Compra Cerca
            		</div></a>
            	</div>
            </div>
            <div id="mapCanvas" class="map"></div>
			<div class="alert alert-info" id="message">
  				<strong>Cargando Ubicacion</strong> Prende tu ubicación y recarga la página.
	        </div>

		<!-- ESPACIO DISPONIBLE -->
<!--		  <header class="header-image">-->
<!--		        <div class="headline-small">-->
<!--		                <h4>Espacio Publicitario</h4>-->
<!--		        </div>-->
<!--		   </header>-->
        <hr>
        <div class="featurette" id="acerca">
    			<h3 class="center">
    				<span><a href="mailto:contact@eldolarjuarez.com">Para cualquier información: contact@eldolarjuarez.com</a></span>
    			</h3>
        </div>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; El Dólar Juárez 2017
                		<a class="pull-right"  href="#navbar-id">Volver Arriba</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
    <!-- /.container -->
	<!-- FACEBOOK -->
	<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=505546169612900";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
 	</div>
    <!-- jQuery -->
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	   <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <!-- Data Table -->
    <script>document.write('<script src="js/jquery.dataTables.min.js" type="text/javascript"><\/script>')</script>
    <!-- MAPS -->
    <script>document.write('<script src="js/mapsv5.js" type="text/javascript"><\/script>')</script>
    <script>document.write('<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBimoXEvMSYzkxp3uq78Vs6wR0VVZ7rcI4&callback=initMap" type="text/javascript" async><\/script>')</script>
</body>
</html>
