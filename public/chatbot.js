document.addEventListener("DOMContentLoaded", () => {
    // 1. REFERENCIAS A LOS ELEMENTOS DEL HTML
    const button = document.getElementById("chat-button");
    const windowChat = document.getElementById("chat-window");
    const sendBtn = document.getElementById("chat-send");
    const inputField = document.getElementById("chat-user-input");
    const messagesContainer = document.getElementById("chat-messages");

    // 2. FUNCIÓN PARA ABRIR Y CERRAR EL CHAT
    button.onclick = () => {
        if (windowChat.style.display === "none" || windowChat.style.display === "") {
            windowChat.style.display = "flex";
            button.innerHTML = "✖"; // Cambia el icono a cerrar
            setTimeout(() => inputField.focus(), 100); // Pone el cursor en el input
        } else {
            windowChat.style.display = "none";
            button.innerHTML = "💬"; // Cambia el icono a chat
        }
    };

    // 3. EVENTOS PARA ENVIAR (Click en botón o Tecla Enter)
    sendBtn.onclick = () => sendMessage();
    
    inputField.addEventListener("keypress", (e) => {
        if (e.key === "Enter") sendMessage();
    });

    // 4. LÓGICA PRINCIPAL DEL CHAT
    async function sendMessage() {
        const text = inputField.value.trim();
        if (!text) return;

        // A) Mostrar mensaje del usuario inmediatamente
        addMessage("user", text);
        inputField.value = "";

        // B) Mostrar indicador de "Escribiendo..."
        const loadingId = addMessage("bot", "typing...");

        // C) Cerebro del Bot: Decidir qué responder
        let botResponse = "";

        try {
            const lowerText = text.toLowerCase();

            // CASO 1: Servicios
            if (lowerText.includes("servicios") || lowerText.includes("precio")) {
                try {
                    // Intenta conectar a tu API real
                    const res = await fetch("/api/servicios"); 
                    
                    if (res.ok) {
                        const data = await res.json();
                        // Formatea la lista de servicios que viene de la base de datos
                        const lista = data.map(s => `• ${s.nombre}: $${s.precio}`).join("<br>");
                        botResponse = "<b>Nuestros servicios actuales:</b><br>" + lista;
                    } else {
                        throw new Error("API no disponible");
                    }
                } catch (err) {
                    // Si falla la API, muestra estos datos fijos (Fallback)
                    botResponse = "<b>Ofrecemos:</b><br>• Corte de Cabello - $100<br>• Manicure - $150<br>• Tinte - $300<br><i>(Conecta tu base de datos para ver precios reales)</i>";
                }

            // CASO 2: Citas
            } else if (lowerText.includes("cita") || lowerText.includes("agendar")) {
                 botResponse = "Para agendar una cita, por favor inicia sesión o ve a la sección de 'Reservaciones'.";
            
            // CASO 3: Saludo
            } else if (lowerText.includes("hola") || lowerText.includes("buenas")) {
                botResponse = "¡Hola! 👋 Soy el asistente virtual del Salón. ¿Te gustaría ver nuestros 'servicios'?";
            
            // CASO 4: Despedida
            } else if (lowerText.includes("adios") || lowerText.includes("gracias")) {
                botResponse = "¡Gracias a ti! Que tengas un excelente día. 🌟";

            // CASO 5: No entiende
            } else {
                botResponse = "No estoy seguro de entender. Intenta escribir <b>'servicios'</b> o <b>'cita'</b>.";
            }

        } catch (error) {
            console.error(error);
            botResponse = "Tuve un problema técnico, pero sigo aquí.";
        }

        // D) Reemplazar el "Escribiendo..." por la respuesta real (con un pequeño retraso)
        setTimeout(() => {
            removeMessage(loadingId);
            addMessage("bot", botResponse);
        }, 600); 
    }

    // 5. FUNCIONES AUXILIARES (Dibujar en pantalla)
    function addMessage(type, text) {
        const div = document.createElement("div");
        div.className = type === "user" ? "user-msg" : "bot-msg";
        div.id = `msg-${Date.now()}`; // ID único temporal
        
        if(text === "typing...") {
            div.textContent = "⌛ Escribiendo...";
            div.style.fontStyle = "italic";
            div.style.color = "#888";
            div.style.backgroundColor = "transparent";
            div.style.boxShadow = "none";
            div.style.padding = "5px";
        } else {
            // Usamos innerHTML para permitir negritas <b> y saltos <br>
            div.innerHTML = text; 
        }

        messagesContainer.appendChild(div);
        messagesContainer.scrollTop = messagesContainer.scrollHeight; // Auto-scroll al final
        return div.id;
    }

    function removeMessage(id) {
        const elem = document.getElementById(id);
        if(elem) elem.remove();
    }
});