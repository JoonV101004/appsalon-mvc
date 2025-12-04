// Selecciona el área de mensajes
const messages = document.getElementById("messages");

// Función para agregar mensajes al chat
function addMessage(text, sender = "bot") {
  const msg = document.createElement("div");
  msg.textContent = (sender === "bot" ? "🤖 " : "👤 ") + text;
  messages.appendChild(msg);
  messages.scrollTop = messages.scrollHeight;
}

// Función para enviar mensaje del usuario
function sendMessage() {
  const input = document.getElementById("userInput");
  const text = input.value.trim();
  if (!text) return;

  addMessage(text, "user");
  input.value = "";

  // Lógica de respuestas del bot
  if (text.toLowerCase().includes("servicios")) {
    // Consultar servicios desde tu API
    fetch("/api/servicios")
      .then(res => res.json())
      .then(data => {
        let lista = data.map(s => `${s.nombre} - $${s.precio}`).join("\n");
        addMessage("Estos son nuestros servicios:\n" + lista);
      })
      .catch(() => addMessage("Error al obtener servicios."));
  } else if (text.toLowerCase().includes("cita")) {
    // Ejemplo de agendar cita (datos de prueba)
    const citaData = {
      fecha: "2025-12-05",
      hora: "10:00",
      usuarioid: 1, // ID de cliente de prueba
      servicios: "1,2" // IDs de servicios de prueba
    };

    fetch("/api/citas", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(citaData)
    })
      .then(res => res.json())
      .then(data => {
        if (data.resultado) {
          addMessage("Tu cita fue agendada correctamente ✅");
        } else {
          addMessage("No se pudo agendar la cita ❌");
        }
      })
      .catch(() => addMessage("Error al conectar con el servidor."));
  } else {
    addMessage("Puedo ayudarte a ver nuestros servicios o agendar una cita.");
  }
}
