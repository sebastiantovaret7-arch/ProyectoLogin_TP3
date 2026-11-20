<?php

session_start();

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["usuario_id"])) {

    header("Location: index.php");
    exit;

}

$nombre = htmlspecialchars(
    $_SESSION["nombre"],
    ENT_QUOTES,
    "UTF-8"
);

$apellido = htmlspecialchars(
    $_SESSION["apellido"],
    ENT_QUOTES,
    "UTF-8"
);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Proyecto Login</title>

    <link rel="stylesheet" href="assets/css/estilos.css">

</head>

<body>

    <main class="contenedor">

        <section class="tarjeta dashboard">

            <h1>¡Bienvenido/a!</h1>

            <h2>
                <?= $nombre . " " . $apellido ?>
            </h2>

            <p class="subtitulo">
                Accediste correctamente a tu dashboard.
            </p>

            <p class="texto-dashboard">
                Tu sesión está iniciada y ya podés utilizar el sistema.
            </p>

            <a href="logout.php" class="boton">
                Cerrar sesión
            </a>

        </section>

    </main>

</body>

</html>