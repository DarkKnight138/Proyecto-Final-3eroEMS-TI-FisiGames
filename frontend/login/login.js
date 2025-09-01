const form = document.getElementById('login-form');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const email = form.email.value;
      const contraseña = form.contraseña.value;

      // Crea el FormData para enviar los datos
      const formData = new FormData();
      formData.append("email", email);        // agrega el email
      formData.append("contraseña", contraseña);    // agrega la contraseña

      // Crea la conexión con el servidor
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "controladores/login.php", true); //envia los datos a login.php por POST

      // Cuando el servidor responde
      xhr.onload = function () {
        const respuesta = xhr.responseText; // lo que respondió el servidor

        // Si está bien
        if (respuesta === "ok") {
          alert("Bienvenido, " + email);        // mensaje
          window.location.href = "inicio.html";    // redirige al usuario
        } else {
          alert("Usuario o contraseña incorrectos"); // si no, error
        }
      };

      // Envia los datos al servidor
      xhr.send(formData);
    });