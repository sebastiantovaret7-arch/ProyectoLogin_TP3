<?php

session_start();

require_once __DIR__ . "/config/conexion.php";

// Si ya inició sesión, ir directamente al dashboard
if (isset($_SESSION["usuario_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$correo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $correo = trim($_POST["correo"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($correo === "" || $password === "") {

        $error = "Completá todos los campos.";

    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

        $error = "Ingresá un correo válido.";

    } else {

        $sql = "SELECT id, nombre, apellido, correo, password
                FROM usuarios
                WHERE correo = :correo
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([":correo" => $correo]);

        $usuario = $stmt->fetch();

        // Comprobar que el usuario exista y la contraseña coincida
        if ($usuario && password_verify($password, $usuario["password"])) {

            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["apellido"] = $usuario["apellido"];
            $_SESSION["correo"] = $usuario["correo"];

            header("Location: dashboard.php");
            exit;

        } else {

            $error = "Correo o contraseña incorrectos.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Proyecto Login</title>

    <link rel="stylesheet" href="assets/css/estilos.css">

    <script src="assets/js/validaciones.js" defer></script>

</head>

<body>

    <main class="contenedor">

        <section class="tarjeta">

            <h1>Iniciar sesión</h1>

            <p class="subtitulo">
                Ingresá tus datos para acceder al sistema.
            </p>

            <?php if ($error !== ""): ?>

                <div class="mensaje error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>

            <?php endif; ?>

            <form method="POST" action="" data-formulario="login">

                <div class="campo">

                    <label for="correo">Usuario (correo electrónico)</label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        placeholder="ejemplo@gmail.com"
                        value="<?= htmlspecialchars($correo, ENT_QUOTES, "UTF-8") ?>"
                        autocomplete="username"
                        required
                    >

                </div>

                <div class="campo">

                    <label for="password">Contraseña</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Ingresá tu contraseña"
                        autocomplete="current-password"
                        required
                    >

                </div>

                <button type="submit" class="boton">
                    Ingresar
                </button>

            </form>

            <p class="enlace">
                ¿Todavía no tenés una cuenta?

                <a href="registro.php">Crear nuevo usuario</a>
            </p>

        </section>

    </main>

</body>

</html>