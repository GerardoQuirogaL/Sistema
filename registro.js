function registrar() {
    const email = document.getElementById('emailRegistro').value;
    const password = document.getElementById('passwordRegistro').value;

    if (email === '' || password === '') {
        alert('Por favor, completa todos los campos');
        return;
    }

    // Crear el objeto con los datos del formulario
    const data = { email: email, password: password };

    // Enviar los datos al servidor usando fetch
    fetch('procesar_registro.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registro exitoso, ahora puedes iniciar sesión');
            window.location.href = 'index.php';  // Redirigir a la página de login
        } else {
            alert('Error: ' + data.message);  // Mostrar el mensaje de error del servidor
        }
    })
    .catch(error => {
        console.error('Error en el registro:', error);  // Mostrar más detalles del error en la consola
        alert('Ocurrió un error durante el registro. Detalles: ' + error.message);
    });
}
