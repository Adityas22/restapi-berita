@extends('layout')

@section('content')
    <div class="container py-4">

        <a href="/" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

        <h3 class="mb-4">Tambah Berita</h3>

        <form id="addPostForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Judul Berita</label>
                <input type="text" class="form-control" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Isi Konten</label>
                <textarea class="form-control" name="content" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar (optional)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>

        <div id="message" class="mt-3"></div>

    </div>

    <script>
        document.getElementById('addPostForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            let token = localStorage.getItem('token'); // ambil token login

            if (!token) {
                alert("Kamu harus login dulu!");
                return;
            }

            let formData = new FormData(this);

            try {
                let response = await fetch("http://localhost:8000/api/posts", {
                    method: "POST",
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    body: formData
                });

                let res = await response.json();

                if (response.ok) {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-success">Berhasil menambahkan berita!</div>`;

                    // redirect ke home
                    setTimeout(() => {
                        window.location.href = "/";
                    }, 1500);

                } else {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-danger">${res.message || "Gagal menyimpan"}</div>`;
                }

            } catch (error) {
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-danger">Terjadi kesalahan!</div>`;
            }
        });
    </script>
@endsection
