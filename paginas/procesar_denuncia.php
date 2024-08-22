<?php
// Configuración de la conexión a la base de datos (ajusta los valores según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$database = "ciberacoso_db";

// Conectarse a la base de datos
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $motivo = $_POST['motivo'];
    $archivo_path = "";

    // Subir los archivos al servidor y guardar las rutas en la base de datos
    if ($_FILES['archivos']['error'] === 0) {
        $archivo_name = $_FILES['archivos']['name'];
        $archivo_tmp = $_FILES['archivos']['tmp_name'];
        $archivo_path = "uploads/" . $archivo_name;

        // Mover el archivo al directorio de uploads
        move_uploaded_file($archivo_tmp, $archivo_path);
    }

    // Insertar la denuncia en la base de datos
    $sql = "INSERT INTO denuncias (nombre, email, motivo, archivo_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $email, $motivo, $archivo_path);

    if ($stmt->execute()) {
        $result_message = "Denuncia enviada con éxito.";
    } else {
        $result_message = "Error al enviar la denuncia: " . $stmt->error;
    }

    $stmt->close();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
