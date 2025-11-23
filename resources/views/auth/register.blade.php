<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="col-md-4 mx-auto mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Register</h3>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input id="name" class="form-control" placeholder="Masukkan nama">
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input id="username" class="form-control" placeholder="Masukkan username">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Masukkan password">
                </div>

                <button onclick="register()" class="btn btn-success w-100 mt-2">Register</button>

                <p class="mt-3 text-center">
                    Sudah punya akun? <a href="/login">Login</a>
                </p>
            </div>
        </div>
    </div>


    <script>
        async function register() {
            const res = await fetch("/api/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    name: document.getElementById("name").value,
                    username: document.getElementById("username").value,
                    password: document.getElementById("password").value
                })
            });

            const json = await res.json();

            if (res.status === 201) {
                alert("Registrasi berhasil!");
                window.location = "/login";
            } else {
                alert(json.message || "Terjadi kesalahan");
            }
        }
    </script>

</body>

</html>
