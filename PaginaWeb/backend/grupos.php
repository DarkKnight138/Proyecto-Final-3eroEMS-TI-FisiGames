<!DOCTYPE html>
<html lang="es">
<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
require 'controladores/conexion.php';

// Verificar si el usuario ya pertenece a un grupo
$stmt = $conexion->prepare("SELECT id_grupo FROM pertenece_a WHERE id_cuenta = ?");
$stmt->bind_param("i", $_SESSION['id_cuenta']);
$stmt->execute();
$res = $stmt->get_result();
$pertenece = ($res && $res->num_rows > 0);
?>
<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <title>Grupos - FisiGames</title>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
 <style>
   @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap');
   * { box-sizing: border-box; margin: 0; padding: 0; }
   body { font-family: 'Orbitron', sans-serif; background: linear-gradient(135deg,#0f0c29,#302b63,#24243e); color: #eee; min-height: 100vh; padding-top: 70px; }
   nav { width: 100%; height: 60px; background-color: #1a1a2e; box-shadow: 0 0 15px #00ffe7; display: flex; align-items: center; padding: 0 2rem; position: fixed; top: 0; left: 0; z-index: 1000; gap: 2rem; }
   .logo { font-size:1.8rem;color:#00ffe7;font-weight:700; }
   .nav-left{display:flex;gap:1.5rem;flex-grow:1;}
   .nav-left a, .nav-right a { text-decoration:none;color:#eee;padding:8px 12px;border-radius:6px;display:inline-flex;align-items:center;gap:6px;font-weight:600;}
   .nav-left a:hover, .nav-right a:hover { background:#00ffe7;color:#1a1a2e; box-shadow:0 0 8px #00ffe7; }
   .container { padding:2rem; max-width:1000px; margin:80px auto 40px; }
   .form-box { background:rgba(26,26,46,0.95); padding:1.6rem; border-radius:12px; box-shadow:0 0 20px #00ffe7; margin-bottom:1rem; }
   input[type="text"], input[type="password"] { width:100%; padding:10px; border-radius:6px; border:none; background:#1a1a2e; color:#eee; box-shadow: inset 0 0 5px #00ffe7; }
   button { background:transparent;border:2px solid #00ffe7;color:#00ffe7;padding:8px 12px;border-radius:12px;cursor:pointer;}
   button:hover { background:#00ffe7;color:#1a1a2e; }
   .grupo { display:flex; justify-content:space-between; align-items:center; gap:12px; background:rgba(20,20,40,0.9); padding:10px; border-radius:8px; margin-bottom:10px; box-shadow:0 0 10px #00ffe7; }
   .grupo .info { text-align:left; }
   .small { font-size:0.9rem; color:#ccc; }
 </style>
</head>
<body>
 <nav>
   <div class="logo">FisiGames</div>
   <div class="nav-left">
     <a href="inicio.php"><i class="fas fa-home"></i> Inicio</a>
     <a href="puntuaciones.php"><i class="fas fa-users"></i> Puntuaciones</a>
     <a href="grupos.php"><i class="fas fa-users"></i> Grupos</a>
   </div>
   <div class="nav-right">
     <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
   </div>
 </nav>

 <div class="container">
   <div class="form-box">
     <h2>Buscar grupos</h2>
     <input id="buscador" type="text" placeholder="🔍 Escribí para buscar grupos (insensible a mayúsculas)">
   </div>

   <div class="form-box">
     <h2>Grupos existentes</h2>
     <div id="grupos-list">Cargando...</div>
   </div>
 </div>

<!-- Modal pedir contraseña (simple) -->
<div id="pw-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:2000;">
  <div style="background:rgba(20,20,40,0.98); padding:16px; border-radius:10px; width:320px; box-shadow:0 0 20px #00ffe7;">
    <h3 id="pw-t">Unirse al grupo</h3>
    <input id="pw-input" type="password" placeholder="Contraseña">
    <div style="text-align:right; margin-top:10px;">
      <button onclick="cerrarPw()">Cancelar</button>
      <button id="pw-join">Unirse</button>
    </div>
  </div>
</div>

<script>
const pertenece = <?php echo $pertenece? 'true' : 'false'; ?>;
let grupos = [];
let targetJoinId = 0;

async function cargar() {
  try {
    const r = await fetch('controladores/controladores grupos/mostrar_grupos.php');
    const data = await r.json();
    grupos = data || [];
    render();
  } catch (e) {
    document.getElementById('grupos-list').innerText = 'Error al cargar grupos.';
    console.error(e);
  }
}

function render() {
  const q = document.getElementById('buscador').value.trim().toLowerCase();
  const cont = document.getElementById('grupos-list');
  cont.innerHTML = '';
  const filt = grupos.filter(g => g.nombre_grupo.toLowerCase().includes(q));
  if (filt.length === 0) { cont.innerHTML = '<p class="small">No se encontraron grupos.</p>'; return; }
  filt.forEach(g=>{
    const div = document.createElement('div');
    div.className = 'grupo';
    const miembros = g.usuarios.length ? g.usuarios.join(', ') : 'Sin miembros';
    div.innerHTML = `<div class="info"><strong>${escapeHtml(g.nombre_grupo)}</strong><div class="small">Creador: ${escapeHtml(g.creador)} • Miembros: ${escapeHtml(miembros)}</div></div>`;
    const btnWrap = document.createElement('div');
    // si no pertenece, mostrar botón unirse
    if (!pertenece) {
      const btn = document.createElement('button');
      btn.innerText = 'Unirse';
      btn.onclick = ()=> abrirPw(g.id_grupo, g.nombre_grupo);
      btnWrap.appendChild(btn);
    } else {
      btnWrap.innerHTML = '<span class="small">Ya perteneces a un grupo</span>';
    }
    div.appendChild(btnWrap);
    cont.appendChild(div);
  });
}

document.getElementById('buscador').addEventListener('input', render);

function escapeHtml(t){ if (!t) return ''; return String(t).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

function abrirPw(id, nombre) {
  targetJoinId = id;
  document.getElementById('pw-t').innerText = 'Unirse a "' + nombre + '"';
  document.getElementById('pw-input').value = '';
  document.getElementById('pw-modal').style.display = 'flex';
}

function cerrarPw(){
  document.getElementById('pw-modal').style.display = 'none';
  targetJoinId = 0;
}

document.getElementById('pw-join').addEventListener('click', async ()=> {
  const pw = document.getElementById('pw-input').value;
  if (!targetJoinId) return;
  const body = new URLSearchParams();
  body.append('id_grupo', targetJoinId);
  body.append('password', pw);
  try {
    const r = await fetch('controladores/controladores grupos/ingresar_grupo.php', {method:'POST', body});
    const j = await r.json();
    alert(j.message || 'Respuesta del servidor');
    if (j.status === 'ok') {
      cerrarPw();
      // si te uniste, recargar para ocultar botones (o redirigir)
      cargar();
      // opcional: reload para refrescar el estado pertenece en backend
      setTimeout(()=>location.reload(),500);
    }
  } catch (e) {
    alert('Error al intentar unirse.');
    console.error(e);
  }
});

cargar();
</script>
</body>
</html>
