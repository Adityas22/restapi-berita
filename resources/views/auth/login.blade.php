<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'API Berita' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body class="bg-light">


    {{-- @section('content') --}}
    <div class="col-md-4 mx-auto">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-3">Login</h3>

                <div class="mb-3">
                    <label>Username</label>
                    <input id="username" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" id="password" class="form-control">
                </div>

                <button onclick="login()" class="btn btn-primary w-100">Login</button>

                <p class="mt-3 text-center">
                    Belum punya akun? <a href="/register">Register</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    async function login() {
        const res = await fetch("http://127.0.0.1/restapi-berita/public/api/login", {
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

        let json = await res.json();

        if (res.ok) {
            localStorage.setItem("token", json.token);
            window.location = "/";
        } else {
            alert(json.message);
        }
    }
</script>
{{-- @endsection --}}
