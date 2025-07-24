<?php

?>
<html>


<!-- NAVBAR SOLO LOGO -->
<nav>
  <div class="logo">FisiGames</div>
</nav>


<!-- FORMULARIO LOGIN -->
<div class="login-container">
  <h2>Iniciar Sesión</h2>
  <form id="login-form">
    <div class="input-group">
      <label for="username">Usuario</label>
      <input type="text" id="username" name="username" required />
    </div>
    <div class="input-group">
      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required />
    </div>
    <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> Entrar</button>
  </form>


  <div class="no-account">
    ¿No tenés cuenta? <a href="register.html">Registrate</a>
  </div>
</div>

</html>
<?php

?>