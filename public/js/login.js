// Esperamos a que todo el HTML esté cargadito antes de hacer cosas
document.addEventListener('DOMContentLoaded', () => {
    // Agarramos el formulario de login, el ojito y la contraseña
    const loginForm = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const errorAlert = document.getElementById('error-alert');

    // Aquí manejamos el famoso "ver/ocultar contraseña"
    togglePassword.addEventListener('click', () => {
        // Si está password, lo cambiamos a texto y viceversa
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        // Cambiamos el emoji según se vea o no la contraseña  --> eye / see-no-evil monkey
        togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
    });

    // Cuando se manda el formulario, hacemos magia con AJAX
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault(); // Cancelamos el envío normal, no queremos recargar
        errorAlert.classList.add('hidden'); // Escondemos el error por si estaba visible

        const formData = new FormData(loginForm); // Tomamos los datos del formulario

        try {
            // Mandamos los datos al servidor como un jefe
            const response = await fetch('LoginController.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json(); // Lo que nos devuelve el servidor

            if (data.success) {
                alert("¡Acceso correcto! Redirigiendo..."); // Éxito, todo cool
                window.location.href = 'dashboard.php'; // Aquí iríamos al panel
            } else {
                errorAlert.classList.remove('hidden'); // Ups, credenciales mal 😅
            }
        } catch (error) {
            console.error("Error en la petición:", error); // Algo falló con la conexión
        }
    });
});
