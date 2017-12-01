var map;
var markerMejorCompra = [];
var markerMejorVenta = [];
var length;
var ventaMarker;
var ventaCenter;
var ventaInfoWindow;
var compraMarker;
var compraCenter;
var compraInfoWindow;
var index;
var locations;
var userPosition;
var usermarket;
var userLocationImage = 'imgs/street.png';
var flagVenta = false;
var flagCompra = false;
var countError = 0;




var initMap = function() {
  if(map === undefined || map === null){
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
  		zoom:16,
 		  center: {lat: 31.742965, lng: -106.493397},
    	mapTypeControl: true,
    	scaleControl: true,
    	streetViewControl: true,
    	overviewMapControl: true,
    	rotateControl: true,
      scrollwheel: false,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
  }

     /*Get the data from the server-side*/
   if(locations === undefined || locations === null){
      jsonData();
   }

   getGeolocation();
}

var getGeolocation = function(){
  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
         userPosition = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        //If location found
        usermarket = new google.maps.Marker({
          position: userPosition,
          map: map,
          icon: userLocationImage
        });

        //remove message
        $('#message').hide();

        map.setCenter(userPosition);

        if(flagVenta){
          verVenta();
        }
        if(flagCompra){
          verCompra();
        }

      }, function() {
        handleLocationError();
      });
    } else {
      // Browser doesn't support Geolocation
      handleLocationError();
    }

      /*Get the data from the server-side*/
   if(locations === undefined || locations === null){
      jsonData();
   }
}

var jsonData = function(){
    $.ajax({
        type: 'POST',
        dataType: 'json',
        headers : {'CsrfToken': $('meta[name="csrf-token"]').attr('content')},
        url: 'php/phptojson.php',
        success: callback,
        error: function(e){
            console.log(e.message);
        }
    });
}
 // VENTA INICIO
function verVenta(){
  if(locations === undefined || locations === null){jsonData();}

    flagVenta = true;
  if(!(compraInfoWindow === undefined)){compraInfoWindow.close();}
  if(!(userPosition === undefined)){
    if(ventaMarker === undefined){
      index = 0;
      for (var objName in locations) {
        if (!locations.hasOwnProperty(objName)) continue;
        var obj    = locations[objName];
        var lat    = obj['LAT'];
        var long   = obj['LONG'];
        obj['DIST'] = getDistance(lat, long, userPosition["lat"],userPosition['lng']);
        markerMejorVenta.push(obj);
      }

      calcularDistanciaVenta();
      var mejorVentaObj = calcularMejorVenta();
      var contentWindow = '<div style="text-align:center;"><strong>Mejor Venta</strong><br>' + mejorVentaObj['NOMBRE'] + "<br>"+'Venta :' + mejorVentaObj['VENTA'] + "<br>"+'Compra : ' + mejorVentaObj['COMPRA'] + "<br>"
                                + '<a href="https://maps.google.com/?q=' +mejorVentaObj['LAT']+','+mejorVentaObj['LONG']+'" target="_blank">Click para direccion</a></div>';
      ventaCenter = new google.maps.LatLng(mejorVentaObj['LAT'],mejorVentaObj['LONG']);
      ventaInfoWindow =  new google.maps.InfoWindow({
          content: contentWindow
      });

      ventaMarker = new google.maps.Marker({
          position: ventaCenter,
          map: map
      });

      ventaMarker.addListener('click', function() {
        if(!(compraInfoWindow === undefined)){compraInfoWindow.close();}
        ventaInfoWindow.open(map, ventaMarker);
      });
    }
      ventaInfoWindow.open(map, ventaMarker);
      flagVenta = false;
      map.setCenter(ventaCenter);
  }
}

function calcularMejorVenta(){
  var mejorVenta = markerMejorVenta[0];
  for(var i = 1; i < length;i++){

      var obj = markerMejorVenta[i];

      var price_1 = mejorVenta['VENTA'];
      var price_2 = obj['VENTA'];
      if(price_2 < price_1){
        mejorVenta = markerMejorVenta[i];
      }
    }
  return mejorVenta;
}

function calcularDistanciaVenta(){
  /*SORT BY DISTANCE*/
  markerMejorVenta = sortByKey(markerMejorVenta,'DIST');
}

//VENTA END
//COMPRA INICIO
function verCompra(){
  if(locations === undefined || locations === null){jsonData();}

  flagCompra = true;
  if(!(ventaInfoWindow === undefined)){ventaInfoWindow.close();}
  if(!(userPosition === undefined)){
    if(compraMarker === undefined){
      index = 0;
      for (var objName in locations) {
        if (!locations.hasOwnProperty(objName)) continue;
        var obj    = locations[objName];
        var lat    = obj['LAT'];
        var long   = obj['LONG'];
        obj['DIST'] = getDistance(lat, long, userPosition["lat"],userPosition['lng']);
        markerMejorCompra.push(obj);
      }
      calcularDistanciaCompra();
      var mejorCompraObj = calcularMejorCompra();
      var contentWindow = '<div style="text-align:center;"><strong>Mejor Compra</strong><br>' + mejorCompraObj['NOMBRE'] + "<br>"+'Compra :' + mejorCompraObj['COMPRA'] + "<br>"+'Venta : ' + mejorCompraObj['VENTA'] + "<br>"
                                + '<a href="https://maps.google.com/?q=' +mejorCompraObj['LAT']+','+mejorCompraObj['LONG']+'" target="_blank">Click para direccion</a></div>';
      
      compraCenter = new google.maps.LatLng(mejorCompraObj['LAT'],mejorCompraObj['LONG']);
      compraInfoWindow =  new google.maps.InfoWindow({
          content: contentWindow
      });

      compraMarker = new google.maps.Marker({
          position: compraCenter,
          map: map
      });

      compraMarker.addListener('click', function() {
        if(!(ventaInfoWindow === undefined)){ventaInfoWindow.close();}
        compraInfoWindow.open(map, compraMarker);
      });
    }
      compraInfoWindow.open(map, compraMarker);
      flagCompra = false;
      map.setCenter(compraCenter);
  }
}

function calcularMejorCompra(){
  var mejorCompra = markerMejorCompra[0];
  for(var i = 1; i < length;i++){

      var obj = markerMejorCompra[i];

      var price_1 = mejorCompra['COMPRA'];
      var price_2 = obj['COMPRA'];
      if(price_2 > price_1){
        mejorCompra = markerMejorCompra[i];
      }
    }
    return mejorCompra;
}


function calcularDistanciaCompra(){
  markerMejorCompra = sortByKey(markerMejorCompra,'DIST');
}
//COMPRA END

var sortByKey = function(array, key) {
    return array.sort(function(a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}

var rad = function(x) {
  return x * Math.PI / 180;
};

var getDistance = function(lat_1, long_1, lat_2, long_2) {
  var R = 6371000; // Earth’s mean radius in meter
  var dLat = rad(lat_2 - lat_1);
  var dLong = rad(long_2 - long_1);
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(rad(lat_1)) * Math.cos(rad(lat_2)) *
    Math.sin(dLong / 2) * Math.sin(dLong / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
  return d; // returns the distance in meter
};

var callback = function(json){
  locations = json;
  locationLength = Object.keys(locations).length;
  length = locationLength < 5 ? locationLength : 5;
};

function handleLocationError() {
   console.log("Enable GPS|abilita GPS");
   if(countError++ > 2){
      console.log("Por favor prende tu ubicación y refresca la Página");
   }
   else{
    getGeolocation();
  }
}

/*initialize table*/
$(document).ready(function() {
     $('#table').DataTable( {
      "oLanguage": {"sLengthMenu": "Mostrar _MENU_ Casas De Cambio"},
      "pageLength": 5,
      "order": [[ 1, "asc" ]],
      bFilter: false,
      "language": {"paginate": { "previous": "Anterior  ", "next" : "  Siguiente"}},
      "lengthMenu": [[5, 10, -1], [5, 10, "All"]],
      "columns": [{ "width": "25%" },{ "width": "18.75%" },{ "width": "18.75%" },{ "width": "22%" },{ "width": "15.5%" }]
    } );
});
