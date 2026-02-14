<!DOCTYPE html>
<html lang="es">
<head>
    <!-- configuracion basica oara que el navegador entienda el documento-->
    <meta charset="UTF-8">
    <!-- hace que la pagina se vea bien en cualquier tipo de dipositivo -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - Login</title> 
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="left-panel">
            <!-- colocar imagen para el login -->
        </div>

        <div class="right-panel">
            <div class="form-box">
                <!-- colocar icono bust in silhouette-->
                <div class="avatar"> </div>

                <h1>BIENVENIDO</h1>
                <div id="error-alert" class="alert hidden">
                    ACCESO DENEGADO
                </div>

                <form id="loginForm">
                    <div class="input-group">
                        <label>Usuario</label>
                        <span> </span>
                        <input type="text" name="usuario" required>
                    </div>

                    <div class="input-group">
                        <label>Password</label>

                        <span> </span>
                        <input type="password" id="password" name="password" required>
                        <span id="togglePassword" class="eye-icon"> </span>
                    </div>

                    <a href="#" class="forget-link">
                        Olvide mi password
                    </a>
                    
                    <button type="submit" class="btn-submit">
                        INICIAR SESION
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="../public/js/login.js"></script>
</body>
</html>