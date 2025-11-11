<!DOCTYPE html>
<html lang="es">
<?php
session_start(); // Inicia sesión
if (!isset($_SESSION['usuario_id'])) { // Verifica si hay usuario logueado
    header("Location: login.php"); // Redirige a login si no
    exit();
}
require 'controladores/conexion.php'; // Incluye conexión a BD

// Verificar si el usuario ya pertenece a un grupo
$stmt = $conexion->prepare("SELECT id_grupo FROM pertenece_a WHERE id_cuenta = ?");
$stmt->bind_param("i", $_SESSION['id_cuenta']); // Vincula parámetro
$stmt->execute(); // Ejecuta consulta
$res = $stmt->get_result(); // Obtiene resultados
$pertenece = ($res && $res->num_rows > 0); // Booleano: ya pertenece?
?>
<head>
 <meta charset="UTF-8" /> <!-- Codificación -->
 <meta name="viewport" content="width=device-width, initial-scale=1" /> <!-- Responsive -->
 <title>Grupos - FisiGames</title>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" /> <!-- Iconos -->

 <style>
  @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap'); /* Fuente */
  @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css'); /* Iconos */

  * { box-sizing: border-box; margin: 0; padding: 0; } /* Reset */
  body { font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg,#0f0c29,#302b63,#24243e); color: #eee; min-height: 100vh; padding-top: 70px; } /* Fondo y padding */
  nav { width: 100%; height: 60px; background-color: #1a1a2e; box-shadow: 0 0 15px #00ffe7; display: flex; align-items: center; padding: 0 2rem; position: fixed; top: 0; left: 0; z-index: 1000; gap: 2rem; } /* Navbar */
  .logo { font-size:1.8rem;color:#00ffe7;font-weight:700; } /* Logo */
  .nav-left{display:flex;gap:1.5rem;flex-grow:1;} /* Links izquierda */
  .nav-left a, .nav-right a { text-decoration:none;color:#eee;padding:8px 12px;border-radius:6px;display:inline-flex;align-items:center;gap:6px;font-weight:600;} /* Estilo links */
  .nav-left a:hover, .nav-right a:hover { background:#00ffe7;color:#1a1a2e; box-shadow:0 0 8px #00ffe7; } /* Hover links */
  .container { padding:2rem; max-width:1000px; margin:80px auto 40px; } /* Contenedor principal */
  .form-box { background:rgba(26,26,46,0.95); padding:1.6rem; border-radius:12px; box-shadow:0 0 20px #00ffe7; margin-bottom:1rem; } /* Caja formularios */
  input[type="text"], input[type="password"] { width:100%; padding:10px; border-radius:6px; border:none; background:#1a1a2e; color:#eee; box-shadow: inset 0 0 5px #00ffe7; } /* Inputs */
  button { background:transparent;border:2px solid #00ffe7;color:#00ffe7;padding:8px 12px;border-radius:12px;cursor:pointer;} /* Botones */
  button:hover { background:#00ffe7;color:#1a1a2e; } /* Hover botones */
  .grupo { display:flex; justify-content:space-between; align-items:center; gap:12px; background:rgba(20,20,40,0.9); padding:10px; border-radius:8px; margin-bottom:10px; box-shadow:0 0 10px #00ffe7; } /* Cada grupo */
  .grupo .info { text-align:left; } /* Info grupo */
  .small { font-size:0.9rem; color:#ccc; } /* Texto pequeño */
</style>

</head>
<body>
 <nav>
  <div class="logo">FisiGames</div> <!-- Logo -->
  <div class="nav-left">
    <a href="inicio.php"><i class="fas fa-home"></i> Inicio</a> <!-- Link inicio -->
    <a href="puntuaciones.php"><i class="fas fa-trophy"></i> Puntuaciones</a> <!-- Link puntuaciones -->
  </div>
  <div class="nav-right">
    <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a> <!-- Link perfil -->
  </div>
</nav>

 <div class="container">
   <div class="form-box">
     <h2>Buscar grupos</h2>
     <input id="buscador" type="text" placeholder="🔍 Escribí para buscar grupos (insensible a mayúsculas)"> <!-- Buscador -->
   </div>

   <div class="form-box">
     <h2>Grupos existentes</h2>
     <div id="grupos-list">Cargando...</div> <!-- Lista de grupos -->
   </div>
 </div>

<!-- Modal pedir contraseña -->
<div id="pw-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:2000;">
  <div style="background:rgba(20,20,40,0.98); padding:16px; border-radius:10px; width:320px; box-shadow:0 0 20px #00ffe7;">
    <h3 id="pw-t">Unirse al grupo</h3> <!-- Título modal -->
    <input id="pw-input" type="password" placeholder="Contraseña"> <!-- Input contraseña -->
    <div style="text-align:right; margin-top:10px;">
      <button onclick="cerrarPw()">Cancelar</button> <!-- Botón cerrar -->
      <button id="pw-join">Unirse</button> <!-- Botón unirse -->
    </div>
  </div>
</div>

<script>
const pertenece = <?php echo $pertenece? 'true' : 'false'; ?>; // Booleano pertenece
let grupos = []; // Array de grupos
let targetJoinId = 0; // ID grupo objetivo

// Cargar grupos desde backend
async function cargar() {
  try {
    const r = await fetch('controladores/controladores grupos/mostrar_grupos.php'); // Fetch PHP
    const data = await r.json();
    grupos = data || [];
    render(); // Renderizar lista
  } catch (e) {
    document.getElementById('grupos-list').innerText = 'Error al cargar grupos.';
    console.error(e);
  }
}

// Renderizar grupos en pantalla
function render() {
  const q = document.getElementById('buscador').value.trim().toLowerCase(); // Texto buscador
  const cont = document.getElementById('grupos-list');
  cont.innerHTML = '';
  const filt = grupos.filter(g => g.nombre_grupo.toLowerCase().includes(q)); // Filtrado
  if (filt.length === 0) { cont.innerHTML = '<p class="small">No se encontraron grupos.</p>'; return; }
  filt.forEach(g=>{
    const div = document.createElement('div');
    div.className = 'grupo';
    const miembros = g.usuarios.length ? g.usuarios.join(', ') : 'Sin miembros'; // Lista miembros
    div.innerHTML = `<div class="info"><strong>${escapeHtml(g.nombre_grupo)}</strong><div class="small">Creador: ${escapeHtml(g.creador)} • Miembros: ${escapeHtml(miembros)}</div></div>`;
    
    const btnWrap = document.createElement('div');
    if (!pertenece) { // Solo si no pertenece a grupo
      const btn = document.createElement('button');
      btn.innerText = 'Unirse';
      btn.onclick = ()=> abrirPw(g.id_grupo, g.nombre_grupo); // Abrir modal
      btnWrap.appendChild(btn);
    } else {
      btnWrap.innerHTML = '<span class="small">Ya perteneces a un grupo</span>';
    }
    div.appendChild(btnWrap);
    cont.appendChild(div);
  });
}

document.getElementById('buscador').addEventListener('input', render); // Filtrado en tiempo real

function escapeHtml(t){ if (!t) return ''; return String(t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); } // Escapar HTML

function abrirPw(id, nombre) { // Abrir modal contraseña
  targetJoinId = id;
  document.getElementById('pw-t').innerText = 'Unirse a "' + nombre + '"';
  document.getElementById('pw-input').value = '';
  document.getElementById('pw-modal').style.display = 'flex';
}

function cerrarPw(){ // Cerrar modal
  document.getElementById('pw-modal').style.display = 'none';
  targetJoinId = 0;
}

// Click "Unirse" modal
document.getElementById('pw-join').addEventListener('click', async ()=> {
  const pw = document.getElementById('pw-input').value;
  if (!targetJoinId) return;
  const body = new URLSearchParams();
  body.append('id_grupo', targetJoinId);
  body.append('password', pw);
  try {
    const r = await fetch('controladores/controladores grupos/ingresar_grupo.php', {method:'POST', body});
    const j = await r.json();
    alert(j.message || 'Respuesta del servidor'); // Mensaje
    if (j.status === 'ok') {
      cerrarPw(); // Cerrar modal
      cargar(); // Recargar grupos
      setTimeout(()=>location.reload(),500); // Recarga página
    }
  } catch (e) {
    alert('Error al intentar unirse.');
    console.error(e);
  }
});

cargar(); // Carga inicial
</script>
</body>
</html>
