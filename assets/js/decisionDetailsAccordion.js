let acc = document.getElementsByClassName("accordion-item-header");
let updateCommentBlock = document.getElementsByClassName('update-comment-form');
let toggleUpdate = document.getElementsByClassName('toggle-update-comment-block');
let showCommentBlock = document.getElementsByClassName('comment-author-is-user');
let firstDecisionBlock = document.getElementById("add-first-decision-block");
let firstDecisionButton = document.getElementById("toggle-first-decision-input");
let addCommentBlock = document.getElementById("add-comment-block");
let addCommentButton = document.getElementById("toggle-comment-input");
acc[0].classList.add("active");
var firstPanel= acc[0].nextElementSibling;
firstPanel.style.maxHeight = firstPanel.scrollHeight + "px";
// Accordion

for (let i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        /* Toggle between adding and removing the "active" class,
        to highlight the button that controls the panel */
        this.classList.toggle("active");

        /* Toggle between hiding and showing the active panel */
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
            panel.style.maxHeight = null;
        } else {
            panel.style.maxHeight = panel.scrollHeight + "px";
        }
    });
}

// First decision block appears when I click on the button

if (firstDecisionButton !== null) {
    firstDecisionButton.addEventListener('click', function () {
        if (firstDecisionBlock.classList.contains('hidden')) {
            firstDecisionBlock.classList.remove('hidden');
            firstDecisionButton.classList.add('hidden');
        }
    })
}

// Update comment appears when I click on update

if (addCommentButton !== null) {
    addCommentButton.addEventListener('click', function () {
        if (addCommentBlock.classList.contains('hidden')) {
            addCommentBlock.classList.remove('hidden');
            addCommentButton.classList.add('hidden');
        }
    })
}


for (let j = 0; j < toggleUpdate.length; j++) {
    toggleUpdate[j].addEventListener("click", function () {
        if (updateCommentBlock[j].classList.contains('hidden')) {
            updateCommentBlock[j].classList.remove('hidden');
            showCommentBlock[j].closest('.accordion-body-block').style.maxHeight = showCommentBlock[j].closest('.accordion-body-block').scrollHeight + "px";
            showCommentBlock[j].classList.add('hidden');
        } else {
            updateCommentBlock[j].classList.add('hidden');
            showCommentBlock[j].classList.remove('hidden');
            showCommentBlock[j].closest('.accordion-body-block').style.maxHeight = showCommentBlock[j].closest('.accordion-body-block').scrollHeight + "px";
        }
    });
}


