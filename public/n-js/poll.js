function trigger(clicked) {
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
}

function section() {
    var port1 = document.getElementById('conport1');
    var port2 = document.getElementById('conport2');
    if(port2.style.display === 'none' || port2.style.display === '') {
        port1.style.display = 'none';
        port2.style.display = 'flex';
    }
    else {
        port1.style.display = 'block';
        port2.style.display = 'none';
    }

}