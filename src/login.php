<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <div class="titulo">
      <h2 class=>INICIO DE SESIÓN</h2>
      <p>Ingresa los datos necesarios para poder acceder al listado</p>
    </div>
    <div>
      <div class="tarjetaLogin">
        <form id="formLogin" action="api.php?action=login" method="post">
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required />
          <br /><br />
          <label for="password">Contraseña:</label>
          <input type="password" id="password" name="password" required />
          <br /><br />
          <button type="submit">Iniciar Sesión</button>
        </form>
        <div id="loginError" style="color: #a00; margin-top: .5rem;"></div>
        <div id="loginSuccess" style="margin-top: 1rem;"></div>
      </div>
    </div>
  </body>
</html>
<script>
const api = 'api.php';

document.getElementById('formLogin')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const email = document.getElementById('email')?.value?.trim() || '';
  const password = document.getElementById('password')?.value || '';
  const errorNode = document.getElementById('loginError');

  if (!email || !password) {
    if (errorNode) errorNode.textContent = 'Email y contraseña son obligatorios.';
    return;
  }

  try {
    const resp = await fetch(`${api}?action=login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    });
    const cuerpo = await resp.json();
    if (!cuerpo.ok) {
      if (errorNode) errorNode.textContent = cuerpo.error || 'Credenciales incorrectas.';
      return;
    }
    const role = cuerpo.data?.role ?? cuerpo.data?.rol ?? cuerpo.role ?? cuerpo.role ?? null;
    if (!role) {
      if (errorNode) errorNode.textContent = 'No se recibió el rol del servidor.';
      return;
    }
    localStorage.setItem('userRole', role);
    
    const successNode = document.getElementById('loginSuccess');
    let redirectUrl = '';
    let mensaje = '';
    
    if (role === 'admin') {
      mensaje = '<h4>✓ Te estamos redirigiendo al panel de administración...</h4>';
      redirectUrl = '/index_ajax.html';
      successNode.className = 'mensaje-admin';
    } else if (role === 'usuario') {
      mensaje = '<h4>✓ ¡Bienvenido Usuario!</h4>';
      redirectUrl = '/sociograma/index.php';
      successNode.className = 'mensaje-usuario';
    }

    if (successNode) {
      successNode.innerHTML = mensaje;
    }
    
    setTimeout(() => {
      window.location.href = redirectUrl;
    }, 1500);
  } catch (err) {
    console.error(err);
    if (errorNode) errorNode.textContent = 'Error al conectar con el servidor.';
  }


});
</script>
<style scoped>
  .tarjetaLogin {
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    max-width: 300px;
    margin: auto;
    font-family: Arial, sans-serif;
    font-size: 16px;
    background-color: #f9f9f9;
    align-items: center;
    display: flex;
    flex-direction: column;
  }

  .titulo {
    text-align: center;
    margin-bottom: 20px;
  }

  .mensaje-admin {
    background-color: #0a0;
    color: white;
    padding: 12px 16px;
    border-radius: 4px;
    text-align: center;
    font-weight: bold;
  }

  .mensaje-usuario {
    background-color: #08a;
    color: white;
    padding: 12px 16px;
    border-radius: 4px;
    text-align: center;
    font-weight: bold;
  }

  .mensaje-admin h4,
  .mensaje-usuario h4 {
    margin: 0;
    font-size: 16px;
  }
</style>
