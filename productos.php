<?php include("template/cabecera.php"); ?>
<?php
include("administrador/config/bd.php");

$sentenciaSQL = $conexion->prepare("SELECT *FROM productos");
$sentenciaSQL->execute();
$listaproductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach ($listaproductos as $producto) { ?>
    <div class="col-md-3">
 <div class="card">

        <img class="card-img-top" src="./img/<?php echo $producto['Imagen']; ?>" style="height: 200px " alt="Card image cap">

        <div class="card-body">
            <h4 class="card-title"><?php echo $producto['Nombre']; ?></h4>
            <h4 class="card-title">$ <?php echo $producto['Precio']; ?></h4>
            <a name="" id="" class="btn btn-primary" href="#" role="button">Ver más</a>
        </div>
    </div>
</div>

<?php } ?>
<?php include("template/pie.php"); ?>