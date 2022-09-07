<div class="modal fade" id="post" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="postLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <a href="" class="link-dark text-decoration-none post-username-profile">
                    <img src="" width="36" class="rounded-circle me-1 post-image-profile" style="aspect-ratio:1;">
                    <span class="post-name-profile"></span>
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="d-flex flex-column">
                        <img src="" class="w-100 image-post">
                        <div class="container mt-1 d-flex justify-content-between">
                            <i class="bi bi-heart fs-5 like-button like-button-post" role="button"></i>
                            <span class="like-count-post"></span>
                        </div>
                        <p class="caption-post"></p>
                        @if ($page !== 'search')
                        <div class="action"></div>
                        @endif
                        <div class="comment-box border-2 border-top pt-2"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form class="w-100 comment-form">
                <div class="input-group mb-3">
                    <input type="text" name="comment" class="form-control comment-input" placeholder="Tulis komentar" aria-label="Tulis komentar" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Kirim</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($page !== 'search')
{{-- modal edit post --}}
<div class="modal fade" id="editPost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPostLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-edit" action="" method="post" novalidate>
                    @csrf
                    @method('put')
                    <label class="form-label">Caption <span class="text-length"></span></label>
                    <div class="position-relative mb-3">
                        <input id="caption" type="hidden" name="caption" value="">
                        <trix-toolbar id="post" style="display:none;"></trix-toolbar>
                        <trix-editor toolbar="post" input="caption" class="caption"></trix-editor>
                    </div>
                    <button type="submit" class="btn btn-primary save w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal delete post --}}
<div class="modal fade" id="deletePost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deletePostLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePostLabel">Delete Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Yakin hapus postingan?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <form action="" method="post" class="delete-post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Iya</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
$('.like-button').on('click', function(e) {
    slug = e.target.dataset['slug'];
    const likeButton = e.target;
    const likeBtnHome = document.getElementsByClassName(slug)[0];
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        url: "{{ env('APP_URL').'/like/' }}"+slug
    }).done(function (response) {
        if (response[1] == true) {
            likeButton.classList.remove('bi-heart');
            likeButton.classList.add('bi-heart-fill');
            likeButton.classList.add('text-danger');
            likeButton.nextElementSibling.innerHTML = `${response[0]} suka`;
            likeBtnHome.classList.remove('bi-heart');
            likeBtnHome.classList.add('bi-heart-fill');
            likeBtnHome.classList.add('text-danger');
            likeBtnHome.nextElementSibling.innerHTML = `${response[0]} suka`;
        } else {
            likeButton.classList.add('bi-heart');
            likeButton.classList.remove('bi-heart-fill');
            likeButton.classList.remove('text-danger');
            likeButton.nextElementSibling.innerHTML = `${response[0]} suka`;
            likeBtnHome.classList.add('bi-heart');
            likeBtnHome.classList.remove('bi-heart-fill');
            likeBtnHome.classList.remove('text-danger');
            likeBtnHome.nextElementSibling.innerHTML = `${response[0]} suka`;
        }
    });
});
$('.comment-form').on('submit', function (e) {
    e.preventDefault();
    slug = e.target.dataset['slug'];
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "POST",
        url: "{{ env('APP_URL').'/comment/' }}"+slug,
        data: {
            comment: $('.comment-input').val()
        }
    }).done(function (response) {
        const link="{{ env('APP_URL') }}"
        $('.comment-input').val('');
        $('.comment-box').prepend(`
        <div class="comment position-relative">
            <a href="${link}/profile/${response.user_username}" class="link-dark text-decoration-none comment-username-profile">
                <img src="${link}/storage/profile/${response.user_image}" width="36" class="rounded-circle me-1 comment-image-profile" style="aspect-ratio:1;">
                <span>${response.user_name}</span>
            </a>
            <p>${response.comment}</p>
            <small class="position-absolute top-0 end-0 m-1">${response.time}</small>
        </div>
        `);
    });
});
</script>