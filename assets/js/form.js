(function($) {
    $(document).ready(function(){
        // setTimeout(function() {
        //     if(grecaptcha){
        //         grecaptcha.execute()
        //     }
        // }, 2000);
        
        $('#formPetition').on('submit', function(e){
            e.preventDefault();
            
            if($('#formPetition .custom-checkbox input').length > 0 && !$('#formPetition .custom-checkbox input').is(':checked')){
                $('#formPetition .custom-checkbox').addClass('error');
                return false;
            }
            $.post('/petitions/'+ $('#formPetition').data('petition-id') +'/create', $('#formPetition').serialize() , function(data){
                $('#formPetition input').val('');
                $('#modal-petition .modal-petition-content').show();
                $('#modal-petition .modal-content-stf').hide();
                $('#modal-petition-wrapper, #modal-petition').addClass('show');
                $('#formPetition .custom-checkbox').removeClass('error');
                $('#formPetition .custom-checkbox input').trigger('click');
            })

            return false;
        });

        $('#modal-petition .close-button').click(function(){
            $('#modal-petition-wrapper, #modal-petition').removeClass('show');
        })
        
    });
})(jQuery);