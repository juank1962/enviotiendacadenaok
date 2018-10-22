<?php
if (!$_SESSION['latitud']){
    echo '<script>alert("vacio")</script>';
}else{
    echo '<script>alert("Value")</script>';
}

echo '
<script>
    var content = document.getElementById("almacen-cercano");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (objPosition) {
            var lon = objPosition.coords.longitude;
            var lat = objPosition.coords.latitude;
            content.innerHTML = "<p>Alamacén recomendado:<strong>Latitud:</strong> " + lat + "</p><p><strong>Longitud:</strong> " + lon + "</p>";
            jQuery("#almacen").val(lat + " " + lon);
        }, function (objPositionError) {
            switch (objPositionError.code) {
                case objPositionError.PERMISSION_DENIED:
                    content.innerHTML = "No se ha permitido el acceso a la posición del usuario.";
                    break;
                case objPositionError.POSITION_UNAVAILABLE:
                    content.innerHTML = "No se ha podido acceder a la información de su posición.";
                    break;
                case objPositionError.TIMEOUT:
                    content.innerHTML = "El servicio ha tardado demasiado tiempo en responder.";
                    break;
                default:
                    content.innerHTML = "Error desconocido.";
            }
        }, {
            maximumAge: 75000,
            timeout: 15000
        });
    } else {
        content.innerHTML = "Su navegador no soporta la API de geolocalización.";
    };
    </script>';

?>