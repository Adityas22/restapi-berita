@extends('layout')

@section('content')
    <div class="container py-4">

        <a href="/" class="btn btn-secondary btn-sm mb-3">← Kembali</a>

        <div class="card shadow-sm p-4">

            {{-- Aksi Post --}}
            <div id="post-actions" class="d-flex justify-content-end gap-2 mb-3" style="display:none;">
                <a id="edit-post" class="btn btn-warning btn-sm">Edit</a>
                <button id="delete-post" class="btn btn-danger btn-sm">Delete</button>
            </div>

            {{-- Gambar --}}
            <img id="post-image" class="img-fluid rounded mb-3 d-none" style="max-height:340px; object-fit:cover;">

            {{-- Judul --}}
            <h2 id="post-title"></h2>

            {{-- Info --}}
            <p id="post-info" class="text-muted mb-4"></p>

            {{-- Konten --}}
            <p id="post-content"></p>

            <hr>

            {{-- Judul Komentar --}}
            <h5 id="comment-title">Komentar</h5>

            {{-- List Komentar --}}
            <div id="comment-section"></div>

            {{-- Form komentar --}}
            <div id="comment-form" class="mt-3 d-none">
                <textarea id="comment-input" class="form-control my-2" placeholder="Tulis komentar..."></textarea>
                <button id="comment-send" class="btn btn-primary w-100">Kirim Komentar</button>
            </div>

            {{-- Warning login --}}
            <p id="login-warning" class="text-muted fst-italic mt-3 d-none">
                Login untuk menambahkan komentar.
            </p>

        </div>
    </div>


    {{-- ==================  JAVASCRIPT API ================== --}}
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            await loadUser();
            loadDetail();
        });

        let currentUser = null;
        const postId = "{{ $id }}";

        /* ======================================
           LOAD USER LOGIN
        ====================================== */
        async function loadUser() {
            const token = localStorage.getItem("token");
            if (!token) return;

            let res = await fetch("http://127.0.0.1/restapi-berita/public/api/me", {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });

            if (res.ok) currentUser = await res.json();
        }


        /* ======================================
           LOAD POST DETAIL
        ====================================== */
        async function loadDetail() {
            const token = localStorage.getItem("token");

            let res = await fetch(`http://127.0.0.1/restapi-berita/public/api/posts/${postId}`, {
                headers: token ? {
                    "Authorization": `Bearer ${token}`
                } : {}
            });

            const json = await res.json();
            const data = json.data;

            document.getElementById("post-title").textContent = data.title;
            document.getElementById("post-content").textContent = data.content;

            document.getElementById("post-info").innerHTML =
                `Diposting oleh <b>${data.writer?.name ?? 'Unknown'}</b> pada ${data.created_at}`;

            if (data.image) {
                let img = document.getElementById("post-image");
                img.src = data.image;
                img.classList.remove("d-none");
            }

            // SHOW BUTTON EDIT / DELETE HANYA AUTHOR
            if (currentUser && currentUser.id === data.author) {
                document.getElementById("post-actions").style.display = "flex";
                document.getElementById("edit-post").href = `/posts-edit/${data.id}`;
                document.getElementById("delete-post").onclick = () => deletePost(data.id);
            }

            loadComments(data.comments);

            if (currentUser) {
                document.getElementById("comment-form").classList.remove("d-none");
                document.getElementById("comment-send").onclick = () => addComment(data.id);
            } else {
                document.getElementById("login-warning").classList.remove("d-none");
            }
        }


        /* ======================================
           RENDER KOMENTAR
        ====================================== */
        function loadComments(comments) {
            const section = document.getElementById("comment-section");
            section.innerHTML = "";

            if (!comments || comments.length === 0) {
                section.innerHTML = "<p class='text-muted'>Belum ada komentar.</p>";
                return;
            }

            comments.forEach(c => {
                const div = document.createElement("div");
                div.className = "border rounded p-3 mb-2 bg-light";

                div.innerHTML = `
            <strong>${c.commentator.name}</strong>
            <p class="mb-1">${c.comment}</p>
        `;

                if (currentUser && currentUser.id === c.commentator.id) {
                    div.innerHTML += `
                <div class="d-flex gap-2">
                    <button onclick="editComment(${c.id}, '${c.comment}')" 
                        class="btn btn-warning btn-sm">Edit</button>

                    <button onclick="deleteComment(${c.id})" 
                        class="btn btn-danger btn-sm">Delete</button>
                </div>
            `;
                }

                section.appendChild(div);
            });
        }


        /* ======================================
           ADD KOMENTAR
        ====================================== */
        async function addComment(postId) {
            const token = localStorage.getItem("token");
            const text = document.getElementById("comment-input").value;

            if (!text) return alert("Komentar tidak boleh kosong");

            await fetch("http://127.0.0.1/restapi-berita/public/api/comments", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({
                    post_id: postId,
                    comment: text
                })
            });

            location.reload();
        }


        /* ======================================
           DELETE POST
        ====================================== */
        async function deletePost(id) {
            if (!confirm("Hapus postingan ini?")) return;

            const token = localStorage.getItem("token");

            await fetch(`http://127.0.0.1/restapi-berita/public/api/posts/${id}`, {
                method: "DELETE",
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });

            alert("Berhasil dihapus");
            window.location = "/";
        }


        /* ======================================
           DELETE KOMENTAR
        ====================================== */
        async function deleteComment(id) {
            if (!confirm("Hapus komentar ini?")) return;

            const token = localStorage.getItem("token");

            await fetch(`http://127.0.0.1/restapi-berita/public/api/comments/${id}`, {
                method: "DELETE",
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });

            location.reload();
        }


        /* ======================================
           EDIT KOMENTAR
        ====================================== */
        function editComment(id, oldText) {
            const newText = prompt("Edit komentar:", oldText);
            if (!newText) return;

            updateComment(id, newText);
        }

        async function updateComment(id, newText) {
            const token = localStorage.getItem("token");

            await fetch(`http://127.0.0.1/restapi-berita/public/api/comments/${id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({
                    comment: newText
                })
            });

            location.reload();
        }
    </script>
@endsection
