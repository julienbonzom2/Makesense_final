let users = document.getElementsByClassName("avatar-checkbox");
let labels = document.getElementsByClassName("form-label");
let selectUserDiv = document.getElementsByClassName('form-user-display');
// eslint-disable-next-line no-console
console.log(selectUserDiv);

for (let i = 0; i < labels.length; i++) {
    labels[i].style.padding = '10px';

}
for (let i = 0; i < selectUserDiv.length; i++) {
    selectUserDiv[i].addEventListener('click', function () {
        if (selectUserDiv[i].firstElementChild.checked) {
            selectUserDiv[i].firstElementChild.checked = false;
            selectUserDiv[i].classList.toggle('selected')
        } else {
            selectUserDiv[i].firstElementChild.checked = true;
            selectUserDiv[i].classList.toggle('selected')
        }
    });
    // labels[i].addEventListener('click', function () {
    //     if (selectUserDiv[i].firstElementChild.checked) {
    //         selectUserDiv[i].classList.toggle('selected')
    //     } else {
    //         selectUserDiv[i].classList.toggle('selected')
    //     }
    // })
}

