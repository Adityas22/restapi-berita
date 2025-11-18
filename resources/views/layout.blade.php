<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'API Berita' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        // Cek token untuk digunakan di navbar
        const hasToken = !!localStorage.getItem("token");
    </script>
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark px-3 d-flex justify-content-between">
        <a class="navbar-brand" href="/">REST API BERITA</a>

        <div id="nav-right"></div>
    </nav>

    <div class="container py-4">
        @yield('content')
    </div>

    <footer class="fixed-bottom bg-dark-subtle">
        <p class="text-center text-muted">Dibuat oleh Adiya Septiawan</p>
    </footer>

</body>

<script>
    // Navbar dinamis
    const navRight = document.getElementById("nav-right");

    if (localStorage.getItem("token")) {
        // Jika sudah login → tampilkan Tambah Berita + Logout
        navRight.innerHTML = `
            <a href="/posts-view" class="btn btn-sm btn-primary me-2">Tambah Berita</a>
            <button onclick="logout()" class="btn btn-sm btn-danger">Logout</button>
        `;
    } else {
        // Jika belum login → tampilkan Login + Register
        navRight.innerHTML = `
            <a href="/login" class="btn btn-sm btn-light me-2">Login</a>
            <a href="/register" class="btn btn-sm btn-success">Register</a>
        `;
    }

    // Fungsi Logout
    async function logout() {
        const token = localStorage.getItem("token");

        await fetch("http://127.0.0.1/restapi-berita/public/api/logout", {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
                "Authorization": `Bearer ${token}`
            }
        });

        localStorage.removeItem("token");
        window.location = "/login";
    }
</script>

</html>
