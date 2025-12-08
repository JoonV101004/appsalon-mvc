<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="stylesheet" href="/chatbot.css">
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen"></div>
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>

    <?php echo $script ?? ''; ?>

    <script>
    // 1. AL CARGAR: Gestión del caché
    window.onpageshow = function(event) {
        if (event.persisted) {
            document.body.style.display = 'none';
            window.location.reload();
        }
    };

    // 2. AL SALIR: Logout animado
    const botonLogout = document.querySelector('#boton-logout');
    if(botonLogout) {
        botonLogout.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.innerHTML = '';
            document.body.style.backgroundColor = 'white';
            setTimeout(function() {
                window.location.href = '/logout';
            }, 50); 
        });
    }
    </script>

    <div id="chat-button">💬</div>

    <div id="chat-window">
        <div id="chat-header">Asistente Virtual</div>
        
        <div id="chat-messages">
            </div>
        
        <div id="chat-input">
            <input id="chat-user-input" type="text" placeholder="Escribe tu mensaje...">
            <button id="chat-send">➤</button>
        </div>
    </div>

    <script src="/chatbot.js" defer></script>
            
</body>
</html>