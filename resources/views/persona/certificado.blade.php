<!DOCTYPE html>
<html lang="es-ES">

<head>
    <title>SEREMI</title>
    <!-- CSRF Token -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="I7qBOtMAsAwojhsOVP5NNfSGzK0Y3fqfcpCNwPiG">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

</head>

<body>
    <div class="container mb-1">
        <div class="clearfix">
            <img class="float-left" src="http://170.239.84.189/assets/img/Logo_SeremiTarapaca.png" width="90px"
                height="90px">
            <img class="float-right" src="http://170.239.84.189/assets/img/Logo_ResidenciaSanitaria.png" width="150px"
                height="90px">
        </div>
    </div>
    <div class="container text-justify text-center mb-1">
        <h2>Certificado de Egreso de Residencia Sanitaria</h2>
    </div>
    <div class="container mb-1">
        <p>
        </p>
        <hr>
        <p></p>
        <p class="text-right">IQUIQUE,
            {{ $fecha_registro }}</p>
        <blockquote>
            <p class="text-justify">
                <strong>La SECRETARÍA REGIONAL MINISTERIAL DE SALUD de la Región de Tarapacá</strong>, certifica
                que,<strong>
                    {{ $prefix }}
                    {{ $nombre }}</strong>
                identificada con
                <strong>OTROS</strong>
                <strong>{{ $persona->documento }}</strong>



                con fecha de nacimiento en <strong>{{ $fecha_nacimiento }}</strong>
                <strong>({{$edad}})</strong> egresó de una Residencia Sanitaria o Residencia Sanitaria Transitoria

                según “Protocolo de Residencia Sanitaria – Plan de Acción Coronavirus COVID-19”
                emitido por el Ministerio de Salud, en Residencia Sanitaria/Residencia Sanitaria Transitoria.
            </p>
            <p class="text-justify">Hoy cumple condiciones de egreso, puesto que no presenta sintomatología sugerente
                de
                SARS-CoV-2 y ha
                cumplido con el aislamiento efectivo.</p>
            <p class="text-justify">En caso de ameritar evaluación médica post término de cuarentena, deberá concurrir
                a
                la Red Asistencial.
            </p>

            <p>
            </p>
            <ul class="list-unstyled">
                <li>Se recuerda mantener:
                    <ul>
                        <li>Uso habitual de mascarilla</li>
                        <li>Distanciamiento social</li>
                        <li>Lavado frecuente de manos</li>
                    </ul>
                </li>
            </ul>
            <p></p>

        </blockquote>
    </div>

    <div class="container mb-1 text-center">
        <p>
        </p>
        <hr>
        <p></p>
        <ul class="list-unstyled">
            <li> MEDICO YENDRI MILENKA NAVARRETE TOCALE
            </li>
            <li> NÚMERO DE DOCUMENTO 18372523-8 </li>
            <li> <strong>Unidad de Residencias Sanitarias</strong> </li>
            <li> <strong>SEREMI de Salud Tarapacá</strong> </li>
        </ul>

    </div>



</body>

</html>
