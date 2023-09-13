<?php include("../template/cabecera.php") ?>
<?php

/**print_r($_POST); para recepcion de datos */

/**print_r($_FILES); para recepcion de archivos */

$txtId = (isset($_POST['txtId'])) ? $_POST['txtId'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

include("../config/bd.php");

switch ($accion) {

    case "Agregar":
        //INSERT INTO `productos` (`Id`, `Nombre`, `Imagen`) VALUES (NULL, 'laptop', 'imagen.jpg');
        $sentenciaSQL = $conexion->prepare("INSERT INTO productos (nombre, imagen) VALUES (:nombre, :imagen);");
        /**Insercion de datos al localhost phpmyadmin */
        $sentenciaSQL->bindParam(':nombre', $txtNombre);

        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";

        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

        if ($tmpImagen != "") {
            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->execute();

        break;

    case "Modificar":
        $sentenciaSQL = $conexion->prepare("UPDATE productos SET Nombre=:nombre WHERE Id=:Id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':Id', $txtId);
        $sentenciaSQL->execute();

        if ($txtImagen != "") {
            $sentenciaSQL = $conexion->prepare("UPDATE productos SET Imagen=:imagen WHERE Id=:Id");
            $sentenciaSQL->bindParam(':imagen', $txtImagen);
            $sentenciaSQL->bindParam(':Id', $txtId);
            $sentenciaSQL->execute();
        }
        echo "Presionado boton modificar";
        break;

    case "Cancelar":
        echo "Presionado boton cancelar";
        break;

    case "Seleccionar";

        $sentenciaSQL = $conexion->prepare("SELECT *FROM productos WHERE Id=:Id");
        $sentenciaSQL->bindParam(':Id', $txtId);
        $sentenciaSQL->execute();
        $producto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre = $producto['Nombre'];
        $txtImagen = $producto['Imagen'];
        // echo "Presionado boton Seleccionar";
        break;

    case "Borrar";
        $sentenciaSQL = $conexion->prepare("DELETE FROM productos WHERE Id=:Id");
        $sentenciaSQL->bindParam(':Id', $txtId);
        $sentenciaSQL->execute();
        //echo "Presionado boton Borrar";
        break;
}
$sentenciaSQL = $conexion->prepare("SELECT *FROM productos");
$sentenciaSQL->execute();
$listaproductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


?>
<div class="col-md-5">

    <div class="card">
        <div class="card-header">
            Datos del Producto
        </div>


        <div class="card-body">

            <form method="POST" enctype="multipart/form-data"> <!-El enctype Sirve para poder admitir arcchivos en el form->

                    <div class="form-group">
                        <label for="txtId">ID:</label>
                        <input type="text" class="form-control" value="<?php echo $txtId; ?>" name="txtId" id="txtId" placeholder="ID">
                    </div>

                    <div class="form-group">
                        <label for="txtNombre">Nombre:</label>
                        <input type="text" class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre">
                    </div>



                    <div class="form-group">

                        <label for="txtImagen">Imagen:</label>

                        <?php echo $txtImagen; ?>

                        <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen"><!-type=file....para tipos de archivos en el form ->
                    </div>

                    <div class="btn-group" role="group" aria-label="">
                        <button type="submit" name="accion" value="Agregar" class="btn btn-success">Agregar</button><!-accion crea la accion a realizar ->
                            <button type="submit" name="accion" value="Modificar" class="btn btn-warning">Modificar</button><!-tipo de boton subbmit ->
                                <button type="submit" name="accion" value="Cancelar" class="btn btn-info">Cancelar</button><!-el value es lo que se hará con la acción ->
                    </div>

            </form>
        </div>

    </div>
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
            <?php foreach ($listaproductos as $producto) { ?>
                <tr>
                    <td><?php echo $producto['Id']; ?></td>
                    <td><?php echo $producto['Nombre']; ?></td>
                    <td><?php echo $producto['Imagen']; ?></td>

                    <td>
                        <form method="post">

                            <input type="hidden" name="txtId" id="txtId" value="<?php echo $producto['Id']; ?>" />

                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary" />
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger" />

                        </form>
                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

<?php include("../template/pie.php"); ?>