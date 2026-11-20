<?php

require_once __DIR__ . "/config/conexion.php";

$error = "";
$exito = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmar = $_POST["confirmar_password"] ?? "";

    // Validar campos vacíos
    if (
        $nombre === "" ||
        $apellido === "" ||
        $correo === "" ||
        $password === "" ||
        $confirmar === ""
    ) {
        $error = "Todos los campos son obligatorios.";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Ingresá un correo electrónico válido.";

    } elseif (
    strlen($password) < 8 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[^A-Za-z0-9]/', $password)
    ) {
    $error = "La contraseña debe tener al menos 8 caracteres e incluir mayúscula, minúscula, número y símbolo.";

    } elseif ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";

    } else {

        // Proteger la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sql = "INSERT INTO usuarios
                    (nombre, apellido, correo, password)
                    VALUES (:nombre, :apellido, :correo, :password)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":nombre" => $nombre,
                ":apellido" => $apellido,
                ":correo" => $correo,
                ":password" => $passwordHash
            ]);

            $exito = "¡Usuario creado correctamente! Ya podés iniciar sesión.";

        } catch (PDOException $e) {

            if ($e->getCode() === "23000") {
                $error = "Ese correo electrónico ya está registrado.";
            } else {
                $error = "Ocurrió un error al crear el usuario.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | Proyecto Login</title>

    <link rel="stylesheet" href="assets/css/estilos.css">
    <script src="assets/js/validaciones.js" defer></script>
</head>

<body>

    <main class="contenedor">

        <section class="tarjeta">

            <h1>Crear cuenta</h1>

            <p class="subtitulo">
                Completá tus datos para registrarte.
            </p>

            <?php if ($error !== ""): ?>
                <div class="mensaje error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <?php if ($exito !== ""): ?>
                <div class="mensaje exito" role="status">
                    <?= htmlspecialchars($exito, ENT_QUOTES, "UTF-8") ?>
                </div>

                <a href="index.php" class="boton">
                    Ir al inicio de sesión
                </a>

            <?php else: ?>

                <form method="POST" action="" data-formulario="registro">

                    <div class="campo">
                        <label for="nombre">Nombre</label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            placeholder="Ingresá tu nombre"
                            autocomplete="given-name"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="apellido">Apellido</label>
                        <input
                            type="text"
                            id="apellido"
                            name="apellido"
                            placeholder="Ingresá tu apellido"
                            autocomplete="family-name"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="correo">Correo electrónico</label>
                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            placeholder="ejemplo@gmail.com"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="password">Contraseña</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Mínimo 8 caracteres"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="confirmar_password">
                            Confirmar contraseña
                        </label>

                        <input
                            type="password"
                            id="confirmar_password"
                            name="confirmar_password"
                            placeholder="Repetí tu contraseña"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="boton">
                        Crear nuevo usuario
                    </button>

                </form>

                <p class="enlace">
                    ¿Ya tenés una cuenta?
                    <a href="index.php">Volver al login</a>
                </p>

            <?php endif; ?>

        </section>

    </main>

</body>
</html>