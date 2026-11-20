const formulario = document.querySelector(
    '[data-formulario="registro"]'
);

if (formulario) {

    const campos = formulario.querySelectorAll("input[required]");

    const password = document.getElementById("password");
    const confirmar = document.getElementById("confirmar_password");

    // Verificar que los campos no estén vacíos
    campos.forEach(campo => {

        const validarCampo = () => {

            if (campo.value === "") {
                campo.setCustomValidity("Este campo es obligatorio.");
            } else {
                campo.setCustomValidity("");
            }
        };

        campo.addEventListener("input", validarCampo);

        validarCampo();
    });

    // Comprobar que las dos contraseñas coincidan
    function validarPassword() {

    const passwordSegura =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;

    if (password.value === "") {
        password.setCustomValidity("La contraseña es obligatoria.");

    } else if (!passwordSegura.test(password.value)) {
        password.setCustomValidity(
            "Usá 8 caracteres o más, con mayúscula, minúscula, número y símbolo."
        );

    } else {
        password.setCustomValidity("");
    }

    if (confirmar.value === "") {
        confirmar.setCustomValidity("Confirmá tu contraseña.");

    } else if (password.value !== confirmar.value) {
        confirmar.setCustomValidity("Las contraseñas no coinciden.");

    } else {
        confirmar.setCustomValidity("");
    }
    
    }

    password.addEventListener("input", validarPassword);
    confirmar.addEventListener("input", validarPassword);

    formulario.addEventListener("submit", function(event) {

        validarPassword();

        if (!formulario.checkValidity()) {
            event.preventDefault();
            formulario.reportValidity();
        }

    });
}
// Validaciones del formulario de Login

const formularioLogin = document.querySelector(
    '[data-formulario="login"]'
);

if (formularioLogin) {

    formularioLogin.addEventListener("submit", function(event) {

        const correo = document.getElementById("correo");
        const password = document.getElementById("password");

        if (
            correo.value.trim() === "" ||
            password.value.trim() === ""
        ) {

            event.preventDefault();

            alert("Completá todos los campos para ingresar.");

        }

    });

}