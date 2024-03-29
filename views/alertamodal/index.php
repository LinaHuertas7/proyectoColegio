<?php

$dbHost = "localhost";
$dbUser = "root";
$dbPass = "Miva2020*";
$dbName = "db_sistema_sena";
$charset = "charset=utf8";

$conexion = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if (mysqli_connect_errno())
    die("Error connecting " . mysqli_connect_error());


if (isset($_SESSION["id_usuario"])) {
    header("Location: ./index.php");
}
if (!empty($_POST)) {
    $user = mysqli_real_escape_string($conexion, $_POST['user']);
    $password = mysqli_real_escape_string($conexion, $_POST['password']);
    $password_encriptada = sha1($password);
    $sql = "SELECT id_usuario FROM usuario WHERE usuario.nombre_usuario = '$user' 
                                        AND usuario.password_usuario = '$password'";
    $result = $conexion->query($sql);
    $rows = $result->num_rows;
    if ($rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["id_usuario"] = $row["id_usuario"];
        $id = (implode("\n", $_SESSION));

        $data = mysqli_query($conexion, ("SELECT * FROM USUARIO 
                                            INNER JOIN ACUDIENTE ON USUARIO.ID_USUARIO = ACUDIENTE.ID_ACUDIENTE
                                            WHERE ACUDIENTE.ID_ACUDIENTE = '$id'"));
        $nombre = ' ';
        while ($row = mysqli_fetch_row($data)) {
            $nombre = ($row[9]) . ' ';
            $id_alumno = $row[15];
        }
        $words = explode(' ', $nombre);
        $result = ' ';
        foreach ($words as $w) {
            if (strlen($w) > 2) {
                $result .= ucwords(strtolower($w)) . ' ';
            } else {
                $result .= strtolower($w) . ' ';
            }
        }
        if ($user == 'ADMIN' and $password == 'ADMIN') {
            MensajeAlerta("correcto", "Bienvenido", "./prueba.html");
        } else {
            MensajeAlerta("correcto", "Bienvenido $result ", "../nuevo/index.php");
        }
    } else {
        MensajeAlerta("error", "Los datos ingresados son incorrectos", "../../views/index/index.php");
    }
}
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../../../proyectoColegio/public/css/alertamodal.css">
<div class="container">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="#modalTitle"></h4>
                </div>
                <div class="modal-body">
                    <img id="image_modal" style="width:150px" src="">
                    <label id="label1"> </label>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" id="boton_redirec" href="">OK</a>
                </div>
            </div>

        </div>
    </div>

</div>

<?php

$imagen = "";
$mensaje = "";
$redireccion = "";

function MensajeAlerta($opcion, $mensaje, $redireccion)
{

    if ($opcion == 'correcto') {
        $imagen = "../../public/img/correcto.PNG";
    }
    if ($opcion == 'error') {
        $imagen = "../../public/img/error.PNG";
    }
    echo '<button type="button" id="verModal" style="display: none;" 
            class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" 
            data-imagen="' . $imagen . '" data-mensaje="' . $mensaje .
        '"data-redireccion="' . $redireccion . '">Open Modal
        </button>';
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        LlamarModal();
        $("#verModal").click();
    });

    function LlamarModal() {
        $('#myModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var dato_i = button.data('imagen')
            var dato_m = button.data('mensaje')
            var dato_r = button.data('redireccion')
            var modal = $(this)
            $('#image_modal').attr('src', dato_i);
            document.getElementById('label1').innerHTML = dato_m;
            $('#boton_redirec').attr('href', dato_r);

        });
    }
</script>