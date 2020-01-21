$(function() {

    // prevent the submit button to be pressed twice
    $(".form-horizontal").submit(function() {
        $(this).find('input[type=submit]').attr('disabled', true);
        $(this).find('input[type=submit]').val('Sending, please wait...');
    });
})