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
    const skipedInputs = ['accept-terms', 'show_signature'];
    const defaultValues = { 'keep_me_updated': false};

    // Status area
    const counting = document.querySelector('.signatures-count .quantity span:first-child');
    const progressCounting = document.querySelector('.progress-info span:first-child');
    const progressArea = document.querySelector('.progressed-area');

    const signaturesList = document.querySelector('.signatures-history');
    const lastSignature = document.querySelector('.signatures-history .user-signature:first-child');
    const firstSignature = document.querySelector('.signatures-history .user-signature:last-child');

    let goal = document.querySelector('.progress-info span:last-child');

    if(goal) {
        goal = parseInt(goal.innerHTML);
    }

    if(petitionForm) {
        petitionForm.addEventListener('submit', function(e) {
            const filteredData = {};
            const formData = new FormData(petitionForm);
            
            var existShowPublicly = document.getElementsByName('show_signature');
            const showSignatureChecked = (existShowPublicly.length) ? existShowPublicly[0].checked : false;
            
            filteredData['show_signature'] = showSignatureChecked;

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
            
            // console.log(filteredData);
            loadingForm(petitionForm);

            pushFormData({...defaultValues, ...filteredData}).then(response => {
                if(Number.isInteger(response)) {
                    if(parseInt(response) > 0) {
                        // Hide loader
                        document.querySelector('.loading-area').style.display = 'none';
                        document.querySelector('.success-message').style.display = 'block';

                        if(counting) {
                            counting.innerHTML = parseInt(counting.innerHTML) + 1;

                            if(progressCounting) {
                                progressCounting.innerHTML = parseInt(progressCounting.innerHTML) + 1;
                            } 

                            if(progressArea) {
                                progressArea.style.width = (parseInt(progressCounting.innerHTML) / goal) * 100 + "%";
                            }

                            if(showSignatureChecked){
                                const newSignature = document.createElement('div');
                                newSignature.classList.add('user-signature');
                                newSignature.innerHTML = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-user fa-w-14 fa-3x"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z" class=""></path></svg>' 
                                                         + filteredData['name'] + " "
                                                         + signaturesList.getAttribute('data-signature-text');
                                signaturesList.insertBefore(newSignature, lastSignature);
                            }
                        }
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