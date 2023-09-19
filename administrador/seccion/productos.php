<?php include("../template/cabecera.php") ?>
<?php

/**print_r($_POST); para recepcion de datos */

/**print_r($_FILES); para recepcion de archivos */

$txtId = (isset($_POST['txtId'])) ? $_POST['txtId'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$txtPrecio = (isset($_POST['txtPrecio'])) ? $_POST['txtPrecio'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

include("../config/bd.php");

switch ($accion) {

    case "Agregar":
        //INSERT INTO `productos` (`Id`, `Nombre`, `Imagen`) VALUES (NULL, 'laptop', 'imagen.jpg');
        $sentenciaSQL = $conexion->prepare("INSERT INTO productos (nombre,precio, imagen) VALUES (:nombre,:precio, :imagen);");
        /*Insercion de datos al localhost phpmyadmin*/
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':precio',$txtPrecio);
        //crea el nombre para temporal de acuerdo a la fecha 
        $fecha = new DateTime();
        //crea el archivo en temporal con el nombre fecha o si esta vacio lo deja en imagen.jpg por defecto
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";

        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
        //mueve el archivo si existe en temporalImagen
        if ($tmpImagen != "") {
            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
        }

        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->execute();
        header("Location: productos.php");
        break;

    case "Modificar":

        //actualizamos el nombre
        $sentenciaSQL = $conexion->prepare("UPDATE productos SET Nombre=:nombre WHERE Id=:Id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':Id', $txtId);
        $sentenciaSQL->execute();
        //verificamos que no este vacio
        if ($txtImagen != "") {

            //cambiamos el nombre del archivo
            $fecha = new DateTime();
            $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
            $tmpImagen = $_FILES["txtImagen"]["tmp_name"];

            //movemos el archivo a la ubicacion indicada (recordar dar privilegios [esritura lectura] a la carpeta de destino en mac)
            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);

            //selecciona y borra el archivo antiguo -- hastaterminar el if
            $sentenciaSQL = $conexion->prepare("SELECT imagen FROM productos WHERE Id=:Id");
            $sentenciaSQL->bindParam(':Id', $txtId);
            $sentenciaSQL->execute();
            $producto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if (isset($producto["imagen"]) && ($producto["imagen"] != "imagen.jpg")) {
                if (file_exists("../../img/" . $producto["imagen"]))
                    unlink("../../img/" . $producto["imagen"]);
            }

            //despues de los pasos anteriores actualizamos el arhivo nuevo
            $sentenciaSQL = $conexion->prepare("UPDATE productos SET Imagen=:imagen WHERE Id=:Id");
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            $sentenciaSQL->bindParam(':Id', $txtId);
            $sentenciaSQL->execute();
        }
        header("Location: productos.php");
        break;

    case "Cancelar":

        header("Location: productos.php");

        break;

    case "Seleccionar";

        $sentenciaSQL = $conexion->prepare("SELECT *FROM productos WHERE Id=:Id");
        $sentenciaSQL->bindParam(':Id', $txtId);
        $sentenciaSQL->execute();
        $producto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre = $producto['Nombre'];
        $txtPrecio = $producto['Precio'];
        $txtImagen = $producto['Imagen'];
        // echo "Presionado boton Seleccionar";
        break;

    case "Borrar";
        //primero selecciona el archivo dependiendo si se selecciona correctamente el id

        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM productos WHERE Id=:Id");
        $sentenciaSQL->bindParam(':Id', $txtId);
        $sentenciaSQL->execute();
        $producto = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        //si el archivo  es diferente a imagen.jpg lo borra
        if (isset($producto["imagen"]) && ($producto["imagen"] != "imagen.jpg")) {
            if (file_exists("../../img/" . $producto["imagen"]))
                unlink("../../img/" . $producto["imagen"]);
        }
        //borrado de archivo
        $sentenciaSQL = $conexion->prepare("DELETE FROM productos WHERE Id=:Id");
        $sentenciaSQL->bindParam(':Id', $txtId);
        $sentenciaSQL->execute();
        header("Location: productos.php");
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
                        <input type="text" required readonly class="form-control" value="<?php echo $txtId; ?>" name="txtId" id="txtId" placeholder="ID">
                    </div>

                    <div class="form-group">
                        <label for="txtNombre">Nombre:</label>
                        <input type="text" required class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label for="txtNombre">Precio:</label>
                        <input type="number" step="any" required class="form-control" value="<?php echo $txtPrecio; ?>" name="txtPrecio" id="txtPrecio" placeholder="Precio">
                    </div>

                    <div class="form-group">

                        <label for="txtImagen">Imagen:</label>
                        <br>
                        <?php if ($txtImagen != "") { ?>


                            <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen ?>" width="200" alt="" srcset="">


                        <?php } ?>
                        <input type="file"  class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen"><!-type=file....para tipos de archivos en el form ->
                    </div>

                    <div class="btn-group" role="group" aria-label="">
                        <button type="submit" name="accion" <?php echo($accion=="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success">Agregar</button><!-accion crea la accion a realizar ->
                        <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":""; ?>  value="Modificar" class="btn btn-warning">Modificar</button><!-tipo de boton subbmit ->
                        <button type="submit" name="accion" <?php echo($accion!="Seleccionar")?"disabled":""; ?>  value="Cancelar" class="btn btn-info">Cancelar</button><!-el value es lo que se hará con la acción ->
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
                <th>Precio</th>
                <th>Producto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaproductos as $producto) { ?>
                <tr>
                    <td><?php echo $producto['Id']; ?></td>
                    <td><?php echo $producto['Nombre']; ?></td>
                    <td><?php echo $producto['Precio']; ?></td>
                    <td>

                        <img src="../../img/<?php echo $producto['Imagen']; ?>" width="250" alt="" srcset="">

                    </td>

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