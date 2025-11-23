<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="col-md-4 mx-auto mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">Login</h3>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input id="username" class="form-control" placeholder="Masukkan username">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Masukkan password">
                </div>

                <button onclick="loginUser()" class="btn btn-primary w-100 mt-2">Login</button>

                <p class="mt-3 text-center">
                    Belum punya akun? <a href="/register">Register</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        async function loginUser() {
            const res = await fetch("/api/login", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    username: document.getElementById("username").value,
                    password: document.getElementById("password").value
                })
            });

            const json = await res.json();

            if (res.ok) {
                localStorage.setItem("token", json.token);
                window.location = "/";
            } else {
                alert(json.message || "Login gagal");
            }
        }
    </script>

</body>

</html>
