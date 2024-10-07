function registrar() {
    const email = document.getElementById('emailRegistro').value;
    const password = document.getElementById('passwordRegistro').value;

    // Validación de campos
    if (email === '' || password === '') {
        alert('Por favor, completa todos los campos');
        return;
    }

    // Validación del formato del correo
    if (!validateEmail(email)) {
        alert('Por favor, introduce un correo electrónico válido');
        return;
    }

    // Crear el objeto con los datos del formulario
    const data = { email: email, password: password };

    // Enviar los datos al servidor usando fetch
    fetch('procesar_registro.php', {  // Esta es la URL correcta
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text())  // Primero obtenemos la respuesta como texto
    .then(responseText => {
        console.log('Respuesta del servidor:', responseText);  // Log de la respuesta cruda para depurar

        // Intentar parsear la respuesta como JSON si es posible
        try {
            const parsedResponse = JSON.parse(responseText);
            return parsedResponse;
        } catch (error) {
            console.error('Error al parsear el JSON:', error);
            throw new Error('La respuesta del servidor no es un JSON válido.');
        }
    })
    .then(data => {
        if (data.success) {
            alert('Registro exitoso, ahora puedes iniciar sesión');
            document.getElementById('emailRegistro').value = '';
            document.getElementById('passwordRegistro').value = '';
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

// Función para validar el formato del correo electrónico
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}
