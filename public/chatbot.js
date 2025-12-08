document.addEventListener("DOMContentLoaded", () => {
    const button = document.getElementById("chat-button");
    const windowChat = document.getElementById("chat-window");
    const sendBtn = document.getElementById("chat-send");
    const inputField = document.getElementById("chat-user-input");
    const messages = document.getElementById("chat-messages");

    button.onclick = () => {
        windowChat.style.display =
            windowChat.style.display === "none" ? "flex" : "none";
    };

    sendBtn.onclick = async () => {
        const text = inputField.value.trim();
        if (!text) return;

        addMessage("user", text);
        inputField.value = "";

        // Aquí puedes conectar con tu backend o con la API de OpenAI
        addMessage("bot", "Estoy pensando...");

        setTimeout(() => {
            addMessage("bot", "Hola, soy tu asistente del sitio 😄");
        }, 1000);
    };

    function addMessage(type, text) {
        const div = document.createElement("div");
        div.className = type === "user" ? "user-msg" : "bot-msg";
        div.textContent = text;
        messages.appendChild(div);
    }
});