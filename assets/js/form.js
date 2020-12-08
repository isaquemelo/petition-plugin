function pushFormData(data) {
    return fetch(petitionPlugin.url + 'wp-json/petition/sign', {
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

    // Recaptchar
    setTimeout(function() {
        if(grecaptcha){
            grecaptcha.execute()
        }
    }, 2000);

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
                    
                    filteredData[key] = value;
                }
            }

            if(!filteredData['country']) {
                alert("Please select a country");
                e.preventDefault();
                return;
            }
            
            console.log(filteredData);
            loadingForm(petitionForm);

            pushFormData({...defaultValues, ...filteredData}).then(response => {
                if(Number.isInteger(response)) {
                    if(parseInt(response) > 0) {
                        // Hide loader
                        document.querySelector('.loading-area').style.display = 'none';
                        document.querySelector('.success-message').style.display = 'block';
                        // location.reload();
                    } else {
                        document.querySelector('.loading-area').style.display = 'none';
                        document.querySelector('.repeated-signature-message').style.display = 'block';
                    }
                }
            })
        })
    }
});