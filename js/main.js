    function superUser() {
        if (document.querySelector("#flexCheckDefault").checked) {
            document.querySelector("#tipoUser").value = "1";
        } else {
            document.querySelector("#tipoUser").value = "2";
        }
    }

    usuario = document.querySelector("#flexCheckDefault");
    usuario.addEventListener("click", superUser);

    window.addEventListener("load", function() {
        if (!document.querySelector("#flexCheckDefault").checked) {
            document.querySelector("#tipoUser").value = "2";
        }
    });

    function validateUser() {
        password = document.getElementById("pass").value;
        confirmPassword = document.getElementById("confPass").value;
        passAproved = false;
        nameAproved = false;
        if (document.getElementById("nombre").value.length > 5) {
            nameAproved = true;
        }
        if (password == "" || confirmPassword == "") {
            document.getElementById("pass").className = "form-control mb-4";
            document.getElementById("confPass").className = "form-control mb-4";
            return;
        }
        if (password != confirmPassword) {
            document.getElementById("pass").className = "form-control mb-4";
        }
        if (password === confirmPassword) {
            document.getElementById("pass").className = "form-control mb-4 is-valid";
            document.getElementById("confPass").className = "form-control mb-4 is-valid";
            passAproved = true;
        } else {
            document.getElementById("confPass").className = "form-control mb-4 is-invalid";
        }

        if (nameAproved && passAproved) {
            document.getElementById("btn").removeAttribute("disabled");
        } else {
            document.getElementById("btn").setAttribute("disabled", "disabled");
        }
    }

    pass = document.getElementById("pass");
    confPass = document.getElementById("confPass");
    nombre = document.getElementById("nombre");
    pass.addEventListener("keyup", validateUser);
    confPass.addEventListener("keyup", validateUser);
    nombre.addEventListener("keyup", validateUser);