<h1 class="nombre-pagina">Panel de Administración</h1>

<?php 
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date"
                id="fecha"
                name="fecha"
                value="<?php echo $fecha; ?>"
            />
        </div>
    </form> 
</div>

<?php
    if(count($citas) === 0) {
        echo "<h2>No Hay Citas en esta fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">   
        <?php 
            $idCita = 0;
            foreach( $citas as $key => $cita ) {
                if($idCita !== $cita->id) {
                    $total = 0;
        ?>
        <li>
            <p>ID: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
            <p>Email: <span><?php echo $cita->email; ?></span></p>
            <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>
            <p>Estado: 
                <span style="color: 
                    <?php 
                        if($cita->estado === 'cancelada') {
                            echo 'red';
                        } elseif(new DateTime($cita->fecha . ' ' . $cita->hora) < new DateTime()) {
                            echo 'gray';
                        } else {
                            echo 'green';
                        }
                    ?>">
                    <?php 
                        if($cita->estado === 'cancelada') {
                            echo 'Cancelada';
                        } elseif(new DateTime($cita->fecha . ' ' . $cita->hora) < new DateTime()) {
                            echo 'Finalizada';
                        } else {
                            echo 'Activa';
                        }
                    ?>
                </span>
            </p>

            <h3>Servicios</h3>
        <?php 
            $idCita = $cita->id;
        } 
            $total += $cita->precio;
        ?>
            <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p>
        
        <?php 
            $actual = $cita->id;
            $proximo = $citas[$key + 1]->id ?? 0;

            if(esUltimo($actual, $proximo)) { ?>
                <p class="total">Total: <span>$ <?php echo $total; ?></span></p>

                <?php 
                    $fechaCita = new DateTime($cita->fecha . ' ' . $cita->hora);
                    $hoy = new DateTime();

                    if($fechaCita > $hoy && $cita->estado !== 'cancelada') { ?>
                        <!-- Botón de cancelar solo para citas futuras y activas -->
                        <form action="/api/citas/cancelar" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                            <input type="submit" class="boton-eliminar" value="Cancelar">
                        </form>
                    <?php } ?>

                    <!-- Eliminar siempre disponible -->
                    <form action="/api/eliminar" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
        <?php } 
      } ?>
     </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>


<div id="citas-admin">
    <ul class="citas">   
            <?php 
                $idCita = 0;
                foreach( $citas as $key => $cita ) {
   
                    if($idCita !== $cita->id) {
                        $total = 0;
            ?>
            <li>
                    <p>ID: <span><?php echo $cita->id; ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                    <p>Email: <span><?php echo $cita->email; ?></span></p>
                    <p>Email: <span><?php echo $cita->telefono; ?></span></p>
                    <p>Estado: 
                        <span 
                            style="color: <?php echo $cita->estado === 'cancelada' ? 'red' : 'green'; ?>">
                            <?php echo ucfirst($cita->estado); ?>
                             </span>
                        </p>

                    <h3>Servicios</h3>
            <?php 
                $idCita = $cita->id;
            } // Fin de IF 
                $total += $cita->precio;
            ?>
                    <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p>
            
            <?php 
                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;

                if(esUltimo($actual, $proximo)) { ?>
                    <p class="total">Total: <span>$ <?php echo $total; ?></span></p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>

            <?php } 
          } // Fin de Foreach ?>
     </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>


<!-- Chatbot flotante -->
<div id="chatbot">
  <div id="messages"></div>
  <div id="input-area">
    <input type="text" id="userInput" placeholder="Escribe tu mensaje...">
    <button onclick="sendMessage()">Enviar</button>
  </div>
</div>


<!-- Enlace al script del bot -->
<script src="/js/bot.js"></script>
