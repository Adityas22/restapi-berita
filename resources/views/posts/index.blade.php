@extends('layout')

@section('content')
    <h3>Daftar Post</h3>

    <div class="row" id="posts"></div>

    <script>
        async function loadPosts() {
            const res = await fetch("/api/posts");
            const json = await res.json();

            let container = document.getElementById("posts");

            json.data.forEach(p => {
                container.innerHTML += `
        <div class="col-md-4 mb-3">
            <div class="card">
                <img src="/storage/${p.image}" class="card-img-top">
                <div class="card-body">
                    <h5>${p.title}</h5>
                    <p>${p.content.substring(0, 100)}...</p>
                    <a href="/post/${p.id}" class="btn btn-primary btn-sm">Detail</a>
                </div>
            </div>
        </div>`;
            });
        }

        loadPosts();
    </script>
@endsection
