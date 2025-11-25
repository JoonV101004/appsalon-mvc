<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen"></div>
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>

    <?php
        echo $script ?? '';
    ?>

    <script>
    // 1. Lógica para el botón "Atrás" (Recarga forzosa)
    window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    };

    // 2. Lógica "Nuclear" al hacer clic en Cerrar Sesión
    // Buscamos todos los elementos con la clase 'cerrar-sesion'
    const botonesCerrarSesion = document.querySelectorAll('.cerrar-sesion');

    botonesCerrarSesion.forEach(boton => {
        boton.addEventListener('click', function(e) {
            // Nota: No prevenimos el default, dejamos que nos lleve a /logout
            
            // PERO INMEDIATAMENTE borramos todo el contenido visual
            document.body.innerHTML = ''; 
            document.body.style.backgroundColor = 'white';
        });
    });
    </script>
            
</body>
</html>