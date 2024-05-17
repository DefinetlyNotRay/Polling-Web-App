function trigger(clicked, countvote, currentvote) {
   //Getting Value of Selecting Poll
    var pollIndex = clicked.getAttribute('data-poll-index');
    var optionIndex = clicked.getAttribute('data-option-index');

    //String to Int
    pollIndex = parseInt(pollIndex);
    optionIndex = parseInt(optionIndex);

    //Changes dot poll then disable
    var tempclass = clicked.getAttribute('class');
    clicked.setAttribute('class', tempclass + " dot-click");

    //Filter Polls
    var poll = document.querySelectorAll('[show-poll="' + pollIndex + '"]');

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