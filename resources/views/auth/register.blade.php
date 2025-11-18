@extends('layout')

@section('content')
    <div class="col-md-4 mx-auto">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="text-center mb-3">Register</h3>

                <div class="mb-3">
                    <label>Nama</label>
                    <input id="name" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Username</label>
                    <input id="username" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" id="password" class="form-control">
                </div>

                <button onclick="register()" class="btn btn-success w-100">Register</button>

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
                alert("Registrasi Berhasil!");
                window.location = "/login";
            } else {
                alert(JSON.stringify(json));
            }
        }
    </script>
@endsection
