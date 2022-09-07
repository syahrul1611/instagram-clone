$(document).ready(function(){
    // get file and preview image
    $("#inputGroupFile").on('change',function(){
        var input = $(this)[0];
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result).fadeIn('slow');
                document.querySelector('.preview-camera').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
});
