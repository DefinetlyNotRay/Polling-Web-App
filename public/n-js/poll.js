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
