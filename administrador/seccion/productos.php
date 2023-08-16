<?php include("../template/cabecera.php") ?>

<div class="col-md-5">
    <form method="POST" enctype="multipart/form-data">

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
            <label for="exampleInputEmail1">Email address</label>
            <input type="file" class="form-control" name="txtId" id="txtId" placeholder="ID">
        </div>

<div class="btn-group" role="group" aria-label="">
    <button type="button" class="btn btn-success">Agregar</button>
    <button type="button" class="btn btn-warning">Modificar</button>
    <button type="button" class="btn btn-info">Cancelar</button>
</div>
    </div>

</div>
        

    </form>





</div>
<div class="col-md-7">
    Mostrar los datos del producto
</div>
<?php include("../template/pie.php") ?>