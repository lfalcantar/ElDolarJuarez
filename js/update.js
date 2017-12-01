/**
 * Created by NOOB on 7/4/17.
 */
const elementHasChanged = "inputchanged";
var newFormCounter = 0;

/*The -1 in the if, is because the clave does not count as a change.*/
var submitCasa = function (id) {
    var json = datajson(id);
    makeAjaxCall(json,id,false);
}

var submitNewCasa = function (id) {
    var json = datajson(id);
    json['create'] = "True";
    makeAjaxCall(json,id);
}
var deleteCasa = function (id) {
    var json = dataDeleteJson(id);
    makeAjaxCall(json,id);
    deleteForm(id);
}

var makeAjaxCall = function (json,id) {
    if(Object.keys(json).length-1 > 0) {
        disableInputs(id);
        ajaxCall(json,id,true);
    }
    else{alert('Nothing to update Cok');}
}

var disableInputs = function (id) {
    var parent = $('#parent-' + id);
    var inputs = parent.find("input");
    inputs.attr("disabled", true);
    parent.css({'background-image': 'url(' + '../imgs/loader.gif' + ')', 'background-repeat': 'no-repeat', 'background-size' : '100%'});
}

var enableInputs = function (id) {
    var parent = $('#parent-' + id);
    var clave = $('#clave-' + id);
    var inputs = parent.find("input");

    inputs.attr("disabled", false);
    clave.attr("disabled", true);
    parent.css('background-image', 'none');
}

var addNewform = function () {
    var newform = document.createElement('div');
    newform.id = newFormCounter;
    newform.innerHTML = '<div class="unique-'+newFormCounter+' custoim_box custom_border col-lg-6 col-md-6 col-sm-12 col-xs-12"> ' +
        '<div id="parent-'+newFormCounter+'" class="center">' +
        '<p>Disponible <input id="disponible-'+newFormCounter+'" class="'+elementHasChanged+'" name="disponible"  type="checkbox"></p>' +
        '<p>Nombre <input id="nombre-'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="nombre" value=""></p>' +
        '<p>Clave <input id="clave-'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="clave" value=""></p>' +
        '<p>Compra <input id="compra-'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="compra" value=""></p>' +
        '<p>Venta <input id="venta-'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="venta" value=""></p>' +
        '<p>Direccion  <input id="direccion-'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="direccion" value=""></p>' +
        '<p>Zona <input id="zona-'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="zona" value=""></p>' +
        '<p>Latitude <input id="latitude-'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="latitude" value=""></p>' +
        '<p>Longitude <input id="longitude-'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="longitude" value=""></p>' +
        '<p>Horarios <input id="horarios'+newFormCounter+'" class="'+elementHasChanged+'" type="text"  name="horarios" value=""></p>' +
        '<i><strong>note:</strong>separa horarios con "|"</i>' +
        '<input id="' + newFormCounter + '" value="Add Casa" onClick="createCasa(this.id)" type="button" onClick="submitCasa(this.id)">' +
        '<input id="' + newFormCounter + '" value="Remove Form" onClick="deleteForm(this.id)" type="button" onClick="submitCasa(this.id)">' +
        '</div></div>';

    $(newform).insertBefore('#addingForm');
    newFormCounter++;
}

var createCasa = function (id) {
    if(validation(id)){
        submitNewCasa(id);
    }
}

var deleteForm = function (id) {
    $('#' +  id).remove();
}

var changeIDs = function (id) {
    var parent = $('#parent-' + id);
    var clave = $('#clave-' + id);
    var inputs = parent.find("input");

    inputs.attr("disabled", false);

}

var ajaxCall = function (json,id,editButtons) {
    var ajaxRequest= $.ajax({
        url: '../php/updateQuery.php',
        type: "post",
        data: json,
        headers : {'CsrfToken': $('meta[name="csrf-token"]').attr('content')}
    });
    /*  request cab be abort by ajaxRequest.abort() */
    ajaxRequest.done(function (response, textStatus, jqXHR){
        // show successfully for submit message
        console.log(response + "->" + textStatus);
        removeClass(json,id);
        enableInputs(id);
        changeButtons(changeButtons);
    });
    /* On failure of request this function will be called  */
    ajaxRequest.fail(function (){

        console.log("fail");
    });
}

var changeButtons = function (editButtons) {
    if(editButtons){
        // $('#attached_docs :input[value="123"]').remove();

    }
}

/*Clave always need to be present*/
var datajson = function (id) {
    var json = {};
    json['nombre']    = $('#nombre-' + id).hasClass(elementHasChanged) ? $('#nombre-' + id).val() : 'NULL';
    json['clave']     = $('#clave-' + id).val();
    json['direccion'] = $('#direccion-' + id).hasClass(elementHasChanged) ?  $('#direccion-' + id).val() : 'NULL';
    json['horarios']  = $('#horarios-' + id).hasClass(elementHasChanged) ? $('#horarios-' + id).val() : 'NULL';
    json['zona']      = $('#zona-' + id).hasClass(elementHasChanged) ? $('#zona-' + id).val() : 'NULL';
    json['venta']     = $('#venta-' + id).hasClass(elementHasChanged) ? $('#venta-' + id).val() : 'NULL';
    json['compra']    = $('#compra-' + id).hasClass(elementHasChanged) ? $('#compra-' + id).val() : 'NULL';
    json['latitude']  = $('#latitude-' + id).hasClass(elementHasChanged) ? $('#latitude-' + id).val() : 'NULL';
    json['longitude'] = $('#longitude-' + id).hasClass(elementHasChanged) ? $('#longitude-' + id).val() : 'NULL';
    json['disponible']= $('#disponible-' + id).hasClass(elementHasChanged) ? ($('#disponible-' + id).is(':checked') ? 1 : 0) : 'NULL';

    return deleteNotChangedKeys(json);
}

/*Clave always need to be present*/
var dataDeleteJson = function (id) {
    var json = {};
    json['clave'] = $('#clave-' + id).val();
    json['delete'] = "True";
    return json;
}

var removeClass = function (json, id) {
    jQuery.each(json, function(key, val) {
        if(key !=  'clave'){
            $('#' + key + '-' +  id).removeClass(elementHasChanged);
        }
    });
    return json;
}

var deleteNotChangedKeys =  function (json) {
    jQuery.each(json, function(key, val) {
        if(val == 'NULL'){ delete json[key]; }
    });
    return json;
}

var validation =  function (id) {

    var clave = $('#clave-' + id);

    if($.trim(clave.val()).length == 0){
        alert("Cok La clave no puede estar vacia");
        return false;
    }

    if (hasWhiteSpace(clave.val())){
        alert("Cok quitale los espacios");
        return false;
    }

    //Check if the id is unique.
    if($('#clave-' + clave.val()).length > 0){
        alert("Cok casas must have a unique clave");
        return false;
    }

    return true;
}

var hasWhiteSpace = function(s) {
    return /\s/g.test(s);
}

$( document ).ready(function() {
    $("input").change(function () {
        $('#' + this.id).addClass(elementHasChanged);
    });
});


