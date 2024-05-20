function submitForm(selectedRadio) {
    const pollId = selectedRadio.getAttribute("data-poll-id");
    const choiceId = selectedRadio.getAttribute("data-choice-id");


    const form = selectedRadio.closest("form");
    form.querySelector('input[name="poll_id"]').value = pollId;
    form.querySelector('input[name="choice_id"]').value = choiceId;
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const pollsData = {!! json_encode($pollsData) !!};

    pollsData.forEach(pollData => {
        if (pollData.hasVoted) {
            const pollId = pollData.poll.id;
            const userVote = pollData.userVote;
            if (userVote) {
                const selectedRadio = document.querySelector(`input[data-poll-id="${pollId}"][data-choice-id="${userVote.choice_id}"]`);
                if (selectedRadio) {
                    selectedRadio.checked = true;
                    selectedRadio.disabled = true;
                    trigger(selectedRadio);
                }
            }
        }
    });
});


function trigger(selectedRadio) {
    const pollId = selectedRadio.getAttribute("data-poll-id");
    const radioButtons = document.querySelectorAll(`input[data-poll-id="${pollId}"]`);

    radioButtons.forEach((radio) => {
        radio.disabled = true;
    });

    selectedRadio.disabled = false;
}
    poll.forEach(function(res) {
        //Show Results Polls
        res.style.display = 'flex';
    })
    
   //Remove Click Events
   var click = document.querySelectorAll('[data-poll-index="' + pollIndex + '"]');
   click.forEach(function(then) {
        then.removeAttribute('onclick');
   })

   //Change Width Bar
   var inputpoll = document.querySelectorAll('[input-poll="' + pollIndex + '-' + optionIndex + '"]');
   var convote = countvote + 1; 
   var curvote = currentvote + 1;
   var calcvote = parseInt((curvote / convote) * 100);
   inputpoll[0].style.width = calcvote + '%';
}

function section() {
    var port1 = document.getElementById('conport1');
    var port2 = document.getElementById('conport2');
    var port3 = document.getElementById('conport3');
    if(port2.style.display === 'none' || port2.style.display === '') {
        port1.style.display = 'none';
        port2.style.display = 'flex';
        port3.style.display = 'none';
    }
    else {
        port1.style.display = 'block';
        port2.style.display = 'none';
        port3.style.display = 'none';
    }
}

function testInput(info, bool, number, isallowdelete) {
    var pollbody = document.querySelectorAll('[name="poll_body"]');
    
    if (info.value.trim() !== '' && bool === true && number < 4) {
            // Create a new input element
            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.name = 'poll_body';
            newInput.className = 'inputforpoll';
            newInput.placeholder = 'Write an option..';
            newInput.required = false;
            let tonumber = number + 1; // Increment number
            newInput.setAttribute('onchange', 'testInput(this, true, ' + tonumber +', true)');
            
            // Append the new input element to the form
            const form = document.getElementById('body_poll');
            form.appendChild(newInput);
    } else if (isallowdelete === true && number >= 2) {
        pollbody.forEach(function(res) {
        // Check if the value is empty and if it's one of the dynamically created inputs
        if (res.value.trim() === '') {
            info.remove();
            return;
        }
        });
    } 
    else if(info.value.trim() === '') {
        var diserror = document.getElementById('popup-error-trim');
        var texterror = document.getElementById('text-error-trim');
        diserror.style.display = 'block';
        texterror.innerText = 'Input "' + info.getAttribute('name') + '" masih kosong.';
        setTimeout(function() {
            diserror.style.display = 'none';
            texterror.innerText = null;
        }, 3000);
    }
}
