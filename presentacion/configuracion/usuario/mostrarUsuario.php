//la finalidad de este codigo es dar la vista previa de los datos de un usuario cuando el admin entre en la gestion de usuarios

<?php
session_start();
require_once("../../../config/config.php");
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $id = intval($id);
    $sql = "select * from empleado where id='$id'";
    $query = mysqli_query($con, $sql);
    $num = mysqli_num_rows($query);
    if ($num == 1) {
        $rw = mysqli_fetch_array($query);
        $dni = $rw['dni'];
        $imagen = $rw['imagen'];
        $nombre = $rw['nombre'];
        $apellido = $rw['apellido'];
        $username = $rw['username'];
        $email = $rw['email'];
        $domicilio = $rw['domicilio'];
        $localidad = $rw['localidad'];
        $telefono = $rw['telefono'];
        $celular = $rw['celular'];
        $registro = $rw['registro'];
        $status = $rw['status'];
        $created_at = $rw['created_at'];
    }
} else {
    exit;
}
?>
<input type="hidden" value="<?php echo $id; ?>" name="id" id="id">
<div class="form-group">
    <label for="dni" class="col-sm-4 control-label">DNI: </label>
    <div class="col-sm-8">
        <?php echo $dni; ?>
    </div>
</div>
<div class="form-group">
    <label for="nombre" class="col-sm-4 control-label">Nombre: </label>
    <div class="col-sm-8">
        <?php echo $nombre; ?>
    </div>
</div>
<div class="form-group">
    <label for="apellido" class="col-sm-4 control-label">Apellido: </label>
    <div class="col-sm-8">
        <?php echo $apellido; ?>
    </div>
</div>
<div class="form-group">
    <label for="usuario" class="col-sm-4 control-label">Usuario: </label>
    <div class="col-sm-8">
        <?php echo $username; ?>
    </div>
</div>
<div class="form-group">
    <label for="email" class="col-sm-4 control-label">Correo Electrónico: </label>
    <div class="col-sm-8">
        <?php echo $email; ?>
    </div>
</div>
<!-- <div class="form-group">
    <label for="password" class="col-sm-2 control-label">Contraseña: </label>
    <div class="col-sm-8">
        <input type="password" class="form-control" id="password" name="password" placeholder="*******">
        <p class="text-right text-muted">La contraseña solo se modifica si escribes algo!.</p>
    </div>
</div> -->
<div class="form-group">
    <label for="domicilio" class="col-sm-4 control-label">Domicilio: </label>
    <div class="col-sm-8">
        <?php echo $domicilio; ?>
    </div>
</div>
<div class="form-group">
    <label for="localidad" class="col-sm-4 control-label">Localidad: </label>
    <div class="col-sm-8">
        <?php echo $localidad; ?>
    </div>
</div>
<div class="form-group">
    <label for="telefono" class="col-sm-4 control-label">Telefóno</label>
    <div class="col-sm-8">
        <?php echo $telefono; ?>
    </div>
</div>
<div class="form-group">
    <label for="celular" class="col-sm-4 control-label">Celular: </label>
    <div class="col-sm-8">
        <?php echo $celular; ?>
    </div>
</div>
<div class="form-group">
    <label for="registro" class="col-sm-4 control-label">Registro: </label>
    <div class="col-sm-8">
        <?php echo $registro; ?>
    </div>
</div>
<div class="form-group">
    <label for="estado" class="col-sm-4 control-label">Estado: </label>
    <div class="col-sm-8">
        <?php echo ($status == 1) ?  "Activo" :  "Inactivo"; ?>
    </div>
</div>
<div class="form-group">
    <label for="estado" class="col-sm-4 control-label">Fecha: </label>
    <div class="col-sm-8">
        <?php echo $created_at; ?>
    </div>
</div>