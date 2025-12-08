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
    // 1. AL CARGAR: Si venimos del caché ("Atrás"), ocultamos todo inmediatamente y recargamos
    window.onpageshow = function(event) {
        if (event.persisted) {
            // Ocultar contenido inmediatamente para evitar el "flash" de 2 segundos
            document.body.style.display = 'none';
            window.location.reload();
        }
    };

    // 2. AL SALIR: Interceptar el click de Logout
    const botonLogout = document.querySelector('#boton-logout');

    if(botonLogout) {
        botonLogout.addEventListener('click', function(e) {
            // A) DETENER la navegación normal
            e.preventDefault();

            // B) DESTRUIR el contenido visual
            document.body.innerHTML = '';
            document.body.style.backgroundColor = 'white';

            // C) Esperar un instante para asegurar que el navegador "vea" la pantalla blanca
            setTimeout(function() {
                // D) Ahora sí, irnos
                window.location.href = '/logout';
            }, 50); 
        });
    }
    </script>

    <link rel="stylesheet" href="/chatbot.css">

<div id="chat-button">💬</div>

<div id="chat-window">
    <div id="chat-messages"></div>
    <div id="chat-input">
        <input id="chat-user-input" type="text" placeholder="Escribe tu mensaje...">
        <button id="chat-send">Enviar</button>
    </div>
</div>

<script src="/chatbot.js"></script>
            
</body>
</html>