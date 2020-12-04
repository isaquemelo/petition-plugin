// (function($) {
//     $(document).ready(function(){
//         // setTimeout(function() {
//         //     if(grecaptcha){
//         //         grecaptcha.execute()
//         //     }
//         // }, 2000);
        
//         $('#formPetition').on('submit', function(e){
//             e.preventDefault();
            
//             if($('#formPetition .custom-checkbox input').length > 0 && !$('#formPetition .custom-checkbox input').is(':checked')){
//                 $('#formPetition .custom-checkbox').addClass('error');
//                 return false;
//             }
//             $.post('/petitions/'+ $('#formPetition').data('petition-id') +'/create', $('#formPetition').serialize() , function(data){
//                 $('#formPetition input').val('');
//                 $('#modal-petition .modal-petition-content').show();
//                 $('#modal-petition .modal-content-stf').hide();
//                 $('#modal-petition-wrapper, #modal-petition').addClass('show');
//                 $('#formPetition .custom-checkbox').removeClass('error');
//                 $('#formPetition .custom-checkbox input').trigger('click');
//             })

//             return false;
//         });

//         $('#modal-petition .close-button').click(function(){
//             $('#modal-petition-wrapper, #modal-petition').removeClass('show');
//         })
        
//     });
// })(jQuery);

function pushFormData(data) {
    return fetch('http://localhost/wp-json/petition/sign', {
        method: 'post',
        body: JSON.stringify(data),
        headers: {
            'Content-Type': 'application/json'
        },

    }).then(function(response) {
        return response.json();
    }).then(function(response) {
        if(response.data && response.data.status == 400) {
            document.querySelector('.success-message').innerHTML = 'Something went wrong, try again later.';
            document.querySelector('.success-message').style.display = 'block';
            document.querySelector('.loading-area').style.display = 'none';
        }


        return response;
    });
}

function loadingForm(formElement) {
    // Hide form and show loading area
    formElement.style.display = 'none';
    document.querySelector('.loading-area').style.display = 'block';
}


window.addEventListener('DOMContentLoaded', function() {
    // Language switcher
    const languageSelect = document.querySelector('select#petition-language-selector');
    if(languageSelect) {
        languageSelect.addEventListener('change', function(event) {
            window.location = event.target.value;
        })
    }

    // Form submition
    const petitionForm = document.querySelector('#petition-form');
    const skipedInputs = ['accept-terms'];
    const defaultValues = { 'keep_me_updated': false };

    if(petitionForm) {
        petitionForm.addEventListener('submit', function(e) {
            const formData = new FormData(petitionForm);
            const filteredData = {};

            for (let [key, value] of formData.entries()) {
                // Skip some fields
                if(!skipedInputs.includes(key)) {
                    console.log(key, value);
                    filteredData[key] = value;
                }
            }

            if(!filteredData['country']) {
                alert("Please select a country");
                e.preventDefault();
                return;
            }
            
            loadingForm(petitionForm);

            pushFormData({...defaultValues, ...filteredData}).then(response => {
                if(Number.isInteger(response)) {
                    // Hide loader
                    document.querySelector('.loading-area').style.display = 'none';
                    document.querySelector('.success-message').style.display = 'block';
                    // location.reload();
                }
            })
        })
    }
});