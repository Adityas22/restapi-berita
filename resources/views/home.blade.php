@extends('layout')

@section('content')
    <h3 class="mb-4">Daftar Berita</h3>

    <div id="posts-container" class="row g-3">
        <!-- data posts masuk ke sini -->
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", loadPosts);

        async function loadPosts() {
            const token = localStorage.getItem("token");

            let res = await fetch("http://127.0.0.1/restapi-berita/public/api/posts", {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    ...(token ? {
                        "Authorization": `Bearer ${token}`
                    } : {})
                }
            });

            let json = await res.json();
            console.log(json);

            const container = document.getElementById("posts-container");
            container.innerHTML = "";

            json.data.forEach(post => {
                container.innerHTML += `
                    <div class="col-md-4">
                        <div class="card shadow-sm h-100">
                            ${post.image ? `<img src="${post.image}" class="card-img-top" style="height:200px; object-fit:cover;">` : ''}

                            <div class="card-body">
                                <h5 class="card-title">${post.title}</h5>
                                <p class="text-muted small">Penulis: ${post.writer ? post.writer.name : '-'}</p>

                                <p>${post.content.substring(0, 100)}...</p>

                                <a href="/posts-view/${post.id}" class="btn btn-sm btn-primary">Detail</a>

                                ${token ? `
                                                    <button onclick="deletePost(${post.id})" class="btn btn-sm btn-danger float-end">
                                                        Hapus
                                                    </button>
                                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        async function deletePost(id) {
            if (!confirm("Yakin ingin menghapus postingan ini?")) return;

            const token = localStorage.getItem("token");

            let res = await fetch(`http://127.0.0.1/restapi-berita/public/api/posts/${id}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                }
            });

            if (res.ok) {
                alert("Berhasil dihapus!");
                loadPosts();
            } else {
                alert("Gagal menghapus!");
            }
        }
    </script>
@endsection
