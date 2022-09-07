addEventListener("trix-change", event => {
    const { editor } = event.target;
    const string = editor.getDocument().toString();
    const characterCount = string.length - 1;
    $('.string-length').text(`(${characterCount}/150)`);
    $('.text-length').text(`(${characterCount}/150)`);
    if (characterCount > 150) {
        $('.string-length').css('color', 'red');
        $('.text-length').css('color', 'red');
        $('.save').attr('disabled','');
    } else {
        $('.string-length').css('color', 'black');
        $('.text-length').css('color', 'black');
        $('.save').removeAttr('disabled','');
    }
});
addEventListener("trix-initialize", event => {
    const { editor } = event.target;
    const string = editor.getDocument().toString();
    const characterCount = string.length - 1;
    $('.string-length').text(`(${characterCount}/150)`);
    $('.text-length').text(`(${characterCount}/150)`);
});