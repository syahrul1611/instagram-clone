$('.open-modal').on('click', function (e) {
    slug = e.target.dataset['slug'];
    const link = appLink;
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        method: "GET",
        url: link+'/post/'+slug
    }).done(function (response) {
        $('.comment-box').empty();
        $('.post-username-profile').attr('href', link+'/profile/'+response.user_username);
        $('.post-name-profile').html(response.user_name);
        $('.post-image-profile').attr('src', link+'/storage/profile/'+response.user_image);
        response.comments.forEach(comment => {
            $('.comment-box').prepend(`
            <div class="comment position-relative">
                <a href="${link}/profile/${comment.user_username}" class="link-dark text-decoration-none comment-username-profile">
                    <img src="${link}/storage/profile/${comment.user_image}" width="36" class="rounded-circle me-1 comment-image-profile" style="aspect-ratio:1;">
                    <span>${comment.user_name}</span>
                </a>
                <p>${comment.comment}</p>
                <small class="position-absolute top-0 end-0 m-1">${comment.time}</small>
            </div>
            `);
        });
        $('.image-post').attr('src', link+'/storage/post/'+response.user_username+'/'+response.image);
        $('.like-count-post').html(`${response.likes} suka`);
        $('.caption-post').html(response.caption);
        $('.like-button-post').attr('data-slug',slug);
        $('.like-button-post').addClass('post-'+slug);
        $('.comment-form').attr('data-slug',slug);
        if (response.like == true) {
            $('.like-button-post').removeClass('bi-heart');
            $('.like-button-post').addClass('bi-heart-fill');
            $('.like-button-post').addClass('text-danger');
        } else {
            $('.like-button-post').addClass('bi-heart');
            $('.like-button-post').removeClass('bi-heart-fill');
            $('.like-button-post').removeClass('text-danger');
        }
        $('.action').empty();
        if (response.edit == true) {
            $('.action').prepend(`
            <button type="button" class="btn btn-outline-secondary w-100 p-0 mb-2" data-bs-toggle="modal" data-bs-target="#editPost">
                Edit
            </button>
            <button type="button" class="btn btn-danger w-100 p-0 mb-2" data-bs-toggle="modal" data-bs-target="#deletePost">
                Hapus
            </button>
            `);
        }
        $('.form-edit').attr('action', link+'/post/'+slug);
        $('.caption').html(response.caption);
        $('.delete-post').attr('action', link+'/post/'+slug);
    });
})