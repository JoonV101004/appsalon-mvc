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
    // 1. AL ENTRAR: Detectar si venimos del botón "Atrás"
    window.onpageshow = function(event) {
        // Si la página viene de la caché (bfcache)
        if (event.persisted) {
            window.location.reload();
        }
    };

    // 2. AL SALIR: "Limpiar la casa" antes de irnos
    // Esto se ejecuta justo cuando el usuario hace clic en un link o cierra sesión
    window.addEventListener('beforeunload', function () {
        // Ocultamos todo el cuerpo de la página
        document.body.style.display = 'none';
        // Opcional: También podrías poner el fondo blanco explícitamente
        document.body.style.backgroundColor = 'white'; 
    });
    </script>
            
</body>
</html>