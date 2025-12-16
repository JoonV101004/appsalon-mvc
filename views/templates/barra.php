<div class="barra">
    <p>Hola: <?php echo $nombre ?? ''; ?></p>

    <!-- Botón para usuarios normales -->
    <?php if(!isset($_SESSION['admin'])) { ?>
        <a class="boton" href="/misCitas">Mis Citas</a>
    <?php } ?>

    <a class="boton" href="/logout" class="boton-logout">Cerrar Sesión</a>
</div>

<?php if(isset($_SESSION['admin'])) { ?>
    <div class="barra-servicios">
        <a class="boton" href="/admin">Ver Citas</a>
        <a class="boton" href="/servicios">Ver Servicios</a>
        <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
    </div>
<?php } ?>
