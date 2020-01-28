jQuery(document).ready(function($){
    $("#subscriber-form").submit(function(e) {
        e.preventDefault();
        
        var subscriberData = $("#subscriber-form").serialize();

        $.ajax({
            type: 'POST',
            url: $("#subscriber-form").attr('action'),
            data: subscriberData,
        }).done(function(response) {
            $('#form-msg').text(response);
            $('#name').val('');
            $('#email').val('');
        }).fail(function(data) {
            if(data.response) {
                $('#form-msg').text(data.response);
            } else {
                $('#form-msg').text("A mensagem não foi enviada");
            }
        });
    })
});