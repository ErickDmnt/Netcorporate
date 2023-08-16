<?php include("../template/cabecera.php") ?>

<?php
/**print_r($_POST); para recepcion de datos */

/**print_r($_FILES); para recepcion de archivos */

$txtId=(isset($_POST['txtId']))?$_POST['txtId']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../config/bd.php"); 

switch($accion){

    case "Agregar":
    //INSERT INTO `productos` (`Id`, `Nombre`, `Imagen`) VALUES (NULL, 'laptop', 'imagen.jpg');
    $sentenciaSQL=$conexion->prepare("INSERT INTO productos (nombre, imagen) VALUES (:nombre, :imagen);");/**Insercion de datos al localhost phpmyadmin */
    $sentenciaSQL->bindParam(':nombre',$txtNombre);
    $sentenciaSQL->bindParam(':imagen',$txtImagen);
    $sentenciaSQL->execute();
    echo"Presionado boton agregar";

    break;

    case "Modificar":
    echo"Presionado boton modificar";
    break;

    case "Cancelar":
    echo"Presionado boton cancelar";
    break;

}


?>
<div class="col-md-5">


    <form method="POST" enctype="multipart/form-data"> <!-El enctype Sirve para poder admitir arcchivos en el form->

        <div class="card">
            <div class="card-header">
                Datos del Producto
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label for="txtId">ID</label>
                    <input type="text" class="form-control" name="txtId" id="txtId" placeholder="ID">
                </div>

                <div class="form-group">
                    <label for="txtNombre">Nombre</label>
                    <input type="text" class="form-control" name="txtNombre" id="txtNombre" placeholder="Nombre">
                </div>

                <div class="form-group">
                    <label for="txtImagen">Imagen</label>
                    <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen"><!-type=file....para tipos de archivos en el form ->
                </div>

                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" value="Agregar" class="btn btn-success">Agregar</button><!-accion crea la accion a realizar ->
                    <button type="submit" name="accion" value="Modificar" class="btn btn-warning">Modificar</button><!-tipo de boton subbmit ->
                    <button type="submit" name="accion" value="Cancelar" class="btn btn-info">Cancelar</button><!-el value es lo que se hará con la acción ->
                </div>
            </div>

        </div>


    </form>





</div>
<div class="col-md-7">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Producto</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>2</td>
            <td>Aprende php</td>
            <td>imagen.jpg</td>
            <td>Seleccionar Borrar</td>
        </tr>
    </tbody>
</table>

</div>
<?php include("../template/pie.php") ?>