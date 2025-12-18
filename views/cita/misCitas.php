<h1 class="nombre-pagina">Mis Citas</h1>

<?php if(empty($citas)) { ?>
    <p>No tienes citas agendadas.</p>
<?php } ?>

<ul class="citas">
    <?php foreach($citas as $cita) { ?>
        <li>
            <p>Fecha: <span><?php echo $cita->fecha; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>

            <form action="/api/citas/cancelar" method="POST">
                 <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                <input type="submit" class="boton-eliminar" value="Cancelar Cita">
            </form>


        </li>
    <?php } ?>
</ul>
