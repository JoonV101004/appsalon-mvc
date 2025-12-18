<h1 class="nombre-pagina">Mis Citas</h1>
<h1 class="nombre-pagina">Mis Citas</h1>

<?php if(!empty($_GET['mensaje'])) { ?>
    <p class="alerta exito"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
<?php } ?>

<!-- Botón para regresar al inicio -->
<div class="acciones">
    <a href="/cita" class="boton">⬅️ Regresar al Inicio</a>
</div>

<?php if(empty($citas)) { ?>
    <p>No tienes citas agendadas.</p>
<?php } ?>

<ul class="citas">
    <?php foreach($citas as $cita) { ?>
        <li>
            <p>Fecha: <span><?php echo $cita->fecha; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>

            <?php 
                $fechaCita = new DateTime($cita->fecha . ' ' . $cita->hora);
                $hoy = new DateTime();

                if($fechaCita > $hoy && $cita->estado !== 'cancelada') { ?>
                    <form action="/api/citas/cancelar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Cancelar Cita">
                    </form>
                <?php } else { ?>
                    <p><strong>Estado:</strong> 
                        <?php echo $cita->estado === 'cancelada' ? 'Cancelada' : 'Finalizada'; ?>
                    </p>
                <?php } ?>
        </li>
    <?php } ?>
</ul>


<?php if(!empty($_GET['mensaje'])) { ?>
    <p class="alerta exito"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
<?php } ?>

<?php if(empty($citas)) { ?>
    <p>No tienes citas agendadas.</p>
<?php } ?>

<ul class="citas">
    <?php foreach($citas as $cita) { ?>
        <li>
            <p>Fecha: <span><?php echo $cita->fecha; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>

            <?php 
                // Crear objetos DateTime para comparar
                $fechaCita = new DateTime($cita->fecha . ' ' . $cita->hora);
                $hoy = new DateTime();

                if($fechaCita > $hoy && $cita->estado !== 'cancelada') { ?>
                    <!-- Botón de cancelar solo para citas futuras y activas -->
                    <form action="/api/citas/cancelar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Cancelar Cita">
                    </form>
                <?php } else { ?>
                    <!-- Texto para citas pasadas o ya canceladas -->
                    <p><strong>Estado:</strong> 
                        <?php echo $cita->estado === 'cancelada' ? 'Cancelada' : 'Finalizada'; ?>
                    </p>
                <?php } ?>
        </li>
    <?php } ?>
</ul>
