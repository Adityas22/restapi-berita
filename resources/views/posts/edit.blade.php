@extends('layout')

@section('content')
    <div class="container py-4">

        <a href="/" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

        <h3 class="mb-4">Edit Berita</h3>

        <form id="editPostForm" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
                <label class="form-label">Judul Berita</label>
                <input type="text" class="form-control" name="title" id="title" required>
                <div class="invalid-feedback" id="error-title"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Isi Konten</label>
                <textarea class="form-control" name="content" id="content" rows="5" required></textarea>
                <div class="invalid-feedback" id="error-content"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Saat Ini</label>
                <div id="current-image" class="mb-2"></div>

                <label class="form-label">Ganti Gambar (opsional)</label>
                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                <div class="invalid-feedback" id="error-image"></div>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-primary">
                <span id="btnText">Update</span>
                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2" role="status"
                    style="display:none"></span>
            </button>
        </form>

        <div id="message" class="mt-3"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            await loadPost();
        });

        function clearErrors() {
            ['title', 'content', 'image'].forEach(f => {
                const el = document.getElementById('error-' + f);
                if (el) el.textContent = '';
                const input = document.getElementById(f);
                if (input) {
                    input.classList.remove('is-invalid');
                }
            });
            document.getElementById('message').innerHTML = '';
        }

        async function loadPost() {
            let id = "{{ $id }}";
            try {
                let response = await fetch("http://localhost:8000/api/posts/" + id);
                if (!response.ok) {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-danger">Gagal memuat data (status ${response.status})</div>`;
                    return;
                }
                let res = await response.json();
                document.getElementById("title").value = res.data.title || '';
                document.getElementById("content").value = res.data.content || '';

                if (res.data.image) {
                    document.getElementById("current-image").innerHTML =
                        `<img src="${res.data.image}" width="200" class="rounded">`;
                }
            } catch (err) {
                console.error(err);
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-danger">Jaringan bermasalah saat memuat data.</div>`;
            }
        }

        document.getElementById('editPostForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            clearErrors();

            let id = "{{ $id }}";
            let token = localStorage.getItem('token');
            if (!token) {
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-warning">Kamu harus login terlebih dahulu.</div>`;
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // disable button + show spinner
            submitBtn.disabled = true;
            btnText.textContent = 'Updating...';
            btnSpinner.style.display = 'inline-block';

            let formData = new FormData(this);
            formData.append("_method", "PATCH");

            try {
                let response = await fetch("http://localhost:8000/api/posts/" + id, {
                    method: "POST", // tetap POST karena _method=PATCH
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    body: formData
                });

                let resText = await response.text();
                let res;
                try {
                    res = JSON.parse(resText);
                } catch (e) {
                    res = {
                        message: resText
                    };
                }

                if (response.ok) {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-success">Berhasil mengupdate berita!</div>`;

                    // redirect ke halaman detail atau home
                    setTimeout(() => {
                        window.location.href = "/posts-view/" + id;
                    }, 700);
                } else {
                    // Tangani validation errors (422) atau error lain
                    if (response.status === 422 && res.errors) {
                        // tampilkan per-field
                        for (const field in res.errors) {
                            const msg = res.errors[field].join(' ');
                            const errorEl = document.getElementById('error-' + field);
                            const inputEl = document.getElementById(field);
                            if (errorEl) errorEl.textContent = msg;
                            if (inputEl) inputEl.classList.add('is-invalid');
                        }
                        document.getElementById('message').innerHTML =
                            `<div class="alert alert-danger">Validasi gagal. Periksa isian.</div>`;
                    } else {
                        // fallback: tampilkan pesan dari server jika ada, atau status
                        let m = res.message || res.error || `Terjadi kesalahan. Status ${response.status}`;
                        document.getElementById('message').innerHTML =
                            `<div class="alert alert-danger">${m}</div>`;
                    }
                    console.error('Server error:', response.status, res);
                }
            } catch (error) {
                console.error('Network error:', error);
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-danger">Terjadi kesalahan jaringan. Coba lagi.</div>`;
            } finally {
                // enable button + hide spinner
                submitBtn.disabled = false;
                btnText.textContent = 'Update';
                btnSpinner.style.display = 'none';
            }
        });
    </script>
@endsection
