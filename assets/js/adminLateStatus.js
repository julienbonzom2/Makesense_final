let updateLateStatusButton = document.getElementById('update-late-status-button')
let inputCheckboxes = document.getElementsByClassName('late-form-check-input')
for (let i = 0; i < inputCheckboxes.length; i++) {
    inputCheckboxes[i].addEventListener('change', function () {
        if (showValidateButton()) {
            updateLateStatusButton.classList.remove('hidden');
            // eslint-disable-next-line no-console
        } else {
            updateLateStatusButton.classList.add('hidden');
            // eslint-disable-next-line no-console
        }
    })
}

function showValidateButton() {
    for (let j = 0; j < inputCheckboxes.length; j++) {
        if (inputCheckboxes[j].checked === true) {
            return true;
        }
    }
    return false;
}
