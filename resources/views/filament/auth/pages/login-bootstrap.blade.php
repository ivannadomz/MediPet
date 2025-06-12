<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Iniciar sesión</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    rel="stylesheet"
  />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap"
    rel="stylesheet"
  />
  <style>
    body {
      background-color: #eaf4fb;
      height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Inter", sans-serif;
    }

    .form-wrapper {
      background-color: #ffffff;
      padding: 50px 40px;
      border-radius: 20px;
      box-shadow: 0 0 30px rgba(0, 78, 146, 0.1);
      width: 100%;
      max-width: 480px;
    }

    .form-wrapper img {
      height: 90px;
      display: block;
      margin: 0 auto 30px;
    }

    .form-label {
      font-weight: 500;
      margin-bottom: 6px;
      color: #004c77;
    }

    .form-control {
      border-radius: 10px;
      font-size: 0.95rem;
      border: 2px solid #b3dcf2;
      background-color: #ffffff;
      color: #004c77;
    }

    .input-group .form-control {
      background-color: #ffffff;
      border-right: none;
      border-radius: 10px 0 0 10px;
    }

    .form-control:focus {
      border-color: #66b7e4;
      box-shadow: none;
      background-color: #ffffff;
    }

    .btn-login {
      background-color: #b3dcf2;
      color: #004c77;
      border: none;
      border-radius: 10px;
      padding: 12px;
      width: 100%;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    .btn-login:hover {
      background-color: #99cfe9;
    }

    .input-group-text {
      background-color: transparent;
      border-left: none;
      border: 2px solid #b3dcf2;
      border-left: 0;
      cursor: pointer;
      border-radius: 0 10px 10px 0;
    }

    .input-group-text i {
      color: #0077b6;
      transition: color 0.3s ease;
    }

    .input-group-text:hover i {
      color: #004c77;
    }

    .forgot-password {
      margin-top: 16px;
      text-align: center;
    }

    .forgot-password a {
      font-size: 0.95rem;
      color: #0077b6;
      text-decoration: none;
    }

    .forgot-password a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="form-wrapper">
    <img src="{{ asset('Medipet.png') }}" alt="Logo Medipet" />
    <form method="POST" action="{{ route('custom.login.form') }}">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico*</label>
        <input
          type="email"
          class="form-control"
          name="email"
          required
          autofocus
        />
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Contraseña*</label>
        <div class="input-group">
          <input
            type="password"
            class="form-control"
            name="password"
            id="password"
            required
          />
          <span class="input-group-text" onclick="togglePassword()" id="toggleBtn">
            <i class="fa-solid fa-eye" id="icon-eye"></i>
          </span>
        </div>
      </div>
      <button type="submit" class="btn-login">Entrar</button>

      <!-- Enlace de Olvidaste tu contraseña -->
      <div class="forgot-password">
        <a href="{{ url('/forgot-password') }}">¿Olvidaste tu contraseña?</a>
      </div>
    </form>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      const icon = document.getElementById("icon-eye");
      const isPassword = passwordInput.type === "password";
      passwordInput.type = isPassword ? "text" : "password";
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
    }
  </script>
</body>
</html>
