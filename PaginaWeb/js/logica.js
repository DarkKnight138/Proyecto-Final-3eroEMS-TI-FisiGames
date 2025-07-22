const form = document.getElementById('register-form');
   form.addEventListener('submit', function (e) {
     e.preventDefault();
     const username = form.username.value.trim();
     const email = form.email.value.trim();
     const password = form.password.value;
     const confirmPassword = form['confirm-password'].value;


     if (password !== confirmPassword) {
       alert("Las contraseñas no coinciden.");
       return;
     }


     // Simulación de registro exitoso
     alert("Cuenta creada con éxito, " + username + "!");
     window.location.href = "login.html";
   });