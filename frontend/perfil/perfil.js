 const usuario = {
      nombre: "Santiago Ares y Dominic Novik",
      mail: "zanto+domi@mail.com",
      grupo: "Enamorados",
      puntos: -1250,
      rol: "1" // Cambiar entre: "1"(usuario), "2"(admin), "3"(superadmin)
    };

    function mostrarPerfil() {
      const contenedor = document.getElementById("perfil-info");

      // Información base (todos los roles la ven)
      let html = `
        <p><strong>Nombre:</strong> ${usuario.nombre}</p>
        <p><strong>Email:</strong> ${usuario.mail}</p>
        <p><strong>Puntos:</strong> ${usuario.puntos}</p>
        <p><strong>Grupo:</strong> ${usuario.grupo ? usuario.grupo : "Sin grupo"}</p>
      `;

      // Tableros extra según el rol
      if (usuario.rol === "2" || usuario.rol === "3") {
        html += `
          <div class="tablero">
            <h3>Gestión de Usuarios</h3>
            <input type="text" placeholder="Buscar usuario..." />
            <button onclick="alert('Eliminar usuario')"><i class="fas fa-user-minus"></i> Eliminar Usuario</button>
          </div>

          <div class="tablero">
            <h3>Gestión de Grupos</h3>
            <input type="text" placeholder="Buscar grupo..." />
            <button onclick="alert('Eliminar grupo')"><i class="fas fa-users-slash"></i> Eliminar Grupo</button>
          </div>
        `;
      }

      if (usuario.rol === "3") {
        html += `
          <div class="tablero">
            <h3>Gestión de Administradores</h3>
            <input type="text" placeholder="Buscar admin..." />
            <button onclick="alert('Eliminar admin')"><i class="fas fa-user-shield"></i> Eliminar Admin</button>
            <br>
            <button onclick="alert('Crear admin')"><i class="fas fa-user-plus"></i> Crear Admin</button>
          </div>
        `;
      }

      contenedor.innerHTML = html;
    }

    mostrarPerfil();
