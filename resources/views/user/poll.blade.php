<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">

   <!-- Custom Font -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="{{asset('n-css/poll.css')}}"/>
   <link rel="stylesheet" href="{{asset('n/css/alert.css')}}"/>

   <!-- Custom Framework -->
   @vite('resources/css/app.css')
   <title>Poll</title>
   @vite('resources/css/app.css')</head>

</head>
<script src="{{asset('n-js/poll.js')}}"></script>
<body>
    <!-- Confirm Alert -->
    <div id="modif-do-confirm">
        <div id="do-confirm">
            <span id="text-do-confirm">Test.</span>
            <div class="frs-button">
                <input type="button" name="await_to_confirm" id="dc_green" value="Submit">
                <input type="button" name="await_to_cancel" id="dc_red" value="Cancel">
            </div>
          </div>
        </div>

   <nav class="flex items-center justify-between py-5 bg-nav">
      <div class="flex items-center">
          <p class="ml-5">&nbsp;</p>
      </div>
      <div class="flex justify-center flex-grow"> <!-- Center content -->
          <ul class="flex gap-5">
              @if(auth()->check()) {{-- Check if user is authenticated --}}
              @if(auth()->user()->role === 'admin')
                  {{-- User is an admin --}}
                  <li><a class="font-medium text-white" href="/">Home</a></li>
                  <li><a class="font-bold text-white" href="{{route('adminpoll')}}">Polls</a></li>
              @else
                  {{-- User is a normal user --}}
                  <li><a class="font-medium text-white" href="/">Home</a></li>
                  <li><a class="font-bold text-white" href="{{route('userpoll')}}">Polls</a></li>
              @endif
          @endif

          </ul>
      </div>
      <div class="flex items-center">
         <button href="" class="mr-5" onclick="section()"><img src="{{ asset('assets/Group 6.png') }}" class="w-7" alt=""></button>
      </div>
      </div>
  </nav>



   
  </div>
   <!-- Section Container -->
   <!-- View Polls -->
   <section id="conport1">
   <p>Polls</p>
   <div id="list-polls">
   
   <!-- gw buatin design nya aja ya -->
   <!-- start of forEach -->
   @foreach($pollsData as $pollData)
   <div id="polls">
       <p>{{ $pollData['poll']->title }}</p>
       <p>Created by: {{ $pollData['poll']->user->username }} | Deadline: {{ $pollData['poll']->deadline }}</p>
   
       <!-- for Selecting Polls -->
       <div class="select-poll">
          @foreach ($pollData['allChoices'] as $choice)
          <form id="vote-form" method="POST" action="/poll/vote/user">
            @csrf
            <input type="hidden" name="poll_id" id="poll_id">
            <input type="hidden" name="choice_id" id="choice_id">
        
            <div class="flex-select-poll">
                <input class="dot-poll" type="radio" name="vote" data-poll-id="{{$choice->poll_id}}" data-choice-id="{{$choice->id}}" required onclick="toshowactions('This action will sent your option.', this, 3)" {{$pollData['hasVoted'] ? 'disabled' : ($pollData['deadlineOver'] ? 'disabled' : '')               }} {{ $pollData['userVote'] && $choice->id === $pollData['userVote']->choice_id ? 'checked' : '' }}>
                <li class="list-none">{{ $choice->choice }}</li>
            </div>
              
              <!-- Render the progress bar based on the percentage -->

               <div id="bar-select-poll" class="mt-2" style="display:{{$pollData['hasVoted'] ? 'flex': ($pollData['deadlineOver'] ? 'flex' : 'none')}} !important;" show-poll="{{$pollData['poll']->id}}">
                   @php
                       $isUserVote = $pollData['userVote'] && $choice->id === $pollData['userVote']->choice_id;
                       $percentage = 0;
                       if (isset($finalOverallVoteCount[$pollData['poll']->id][$choice->id])) {
                                $percentage = $finalOverallVoteCount[$pollData['poll']->id][$choice->id]['percentage'];
                            }
                  @endphp


               <div class=" progress-bar" style="width: 100%; background-color: #ccc;">
                  <div class="h-100 mb-{{ $isUserVote ? '0' : '2' }}" style=" width: {{ $percentage }}%; background-color: {{ $isUserVote ? '#3BD138' : '#E93232' }};">
                     &nbsp;
                  </div>
               </div>

            </form>
         </div>
@endforeach
</div>
<div class="mt-3 outlines">&nbsp;</div>
</div>
@endforeach





   </section>
     <!-- Accounts -->
   <section id="conport2">
   <p class="username">Hello {{ucfirst(Auth()->user()->username)}}!</p>
   <div class="outlines">&nbsp;</div>

   <div class="con-info">
    <p>Change Password</p>
    <div class="box-pass" onclick="toshowactions('Did you want to change password?', this, 4)">
      <a href="#">
          <p>Change</p>
          </a>
    </div>
 </div>
 <div class="outlines">&nbsp;</div>
 <div class="con-info">
    <p>Logout</p>
    <div class="box-pass box-pass-sec" onclick="toshowactions('Are you sure you want log out?', this, 5)">
      <a href="#">
          <p>Logout</p>
       </a>      </div>
 </div>
   <div class="outlines">&nbsp;</div>

   </section>
      <script src="{{asset('n-js/poll.js')}}"></script>
      <script>
        //Shows confirm alert
        function toshowactions(message, element, path_action) {
            var boolalert = false;

            var showalert = document.getElementById('modif-do-confirm');
            var conshowalert = document.getElementById('do-confirm');
            var textshowalert = document.getElementById('text-do-confirm');

            conshowalert.style.animation = 'goesUpShow 0.5s forwards';
            showalert.style.display = 'flex';
            textshowalert.innerText = message;

            // Add event listener to the document to capture all clicks
            // Filtering to avoid duplicate listener
            if(!boolalert) {
            document.addEventListener('click', captureclicks);
            boolalert = true;
            }

            function captureclicks(event) {

            var b1alert = document.getElementById('dc_green').getAttribute('name'); // Submit / Continue
            var b2alert = document.getElementById('dc_red').getAttribute('name'); // Cancel

            const container = conshowalert;

            //do refresh page in case users load back
            if(document.getElementById('dc_green').disabled || document.getElementById('dc_red').disabled) {
                window.location.reload();
                textshowalert.innerText = 'Refreshing.';
                document.getElementById('dc_green').style.backgroundColor = 'whitesmoke';
                document.getElementById('dc_red').style.backgroundColor = 'whitesmoke';
                    
            }
                if (container.contains(event.target) && !event.target.getAttribute('name')) {
                    console.log("isPopUpAlert: " + true);
                }
                else if(event.target.getAttribute('name') == b1alert) { // Submit / Continue
                    textshowalert.innerText = 'Processing.';
                    document.getElementById('dc_green').disabled = true;
                    document.getElementById('dc_red').disabled = true;
                    
                    //start functions here
                    /*
                    List number for doing actions depending where you on.
                    
                    0 :: @Admin / Create a poll (before)
                    1 :: @Admin / Delete specific poll
                    2 :: @Admin / Create a poll (after)
                    3 :: @(A/U)  / Choose Option Poll (before)
                    4 :: @(A/U) / Change Password
                    5 :: @(A/U) / Logout

                    */

                    if(path_action == 0) {
                        window.location = '{{route('screatepoll')}}';
                    }
                    else if(path_action == 1) {
                        var formId = element.getAttribute('myButtonForm');
                        var form = document.querySelector('form[action="/poll/delete/' + formId + '"]');

                        form.submit();
                    }
                    else if(path_action == 2) {
                        var form = document.getElementById('createPollForm');

                        form.submit();
                    }
                    else if(path_action == 3) {
                        var radio = element;

                        submitForm(radio);
                    }
                    else if(path_action == 4) {
                        window.location = '{{route('changepassword')}}';
                    }
                    else if(path_action == 5) {
                        window.location = '{{route('logout')}}';
                    }
                        //Prevent from duplicating actions
                        setTimeout(function() {
                        
                        //avoiding duplicate listener                        
                        document.removeEventListener('click', captureclicks);
                        boolalert = false;

                        conshowalert.style.animation = 'goesUpClose 0.5s forwards';
                        }, 250);

                        setTimeout(function() {
                        showalert.style.display = 'none';
                        }, 800);

                    //end functions here

                //To ensure main element dont get trigger
                } else if(!element.contains(event.target) && event.target.getAttribute('onclick') === null || event.target.getAttribute('name') == b2alert) { // Cancel
                    conshowalert.style.animation = 'goesUpClose 0.5s forwards';
                    document.removeEventListener('click', captureclicks);
                    boolalert = false;
                    console.log("isPopUpAlert: " + false);

                    setTimeout(function() {
                        showalert.style.display = 'none';
                    }, 750);
                }
            }
        }
        
          function submitForm(radio) {
             // Get the data-poll-id of the selected radio button
          const pollId = radio.getAttribute("data-poll-id");

         // Find all radio buttons with the same data-poll-id
         const radioButtons = document.querySelectorAll(
            `input[data-poll-id="${pollId}"]`
         );

         // Disable all radio buttons with the same data-poll-id
         radioButtons.forEach((radio) => {
            radio.disabled = true;
         });
         //  Getting Value of Selecting Poll
         var pollIndex = radio.getAttribute("data-poll-id");
         var optionIndex = radio.getAttribute("data-option-index");

         //String to Int
         pollIndex = parseInt(pollIndex);
         optionIndex = parseInt(optionIndex);

         //Changes dot poll then disable
         var tempclass = radio.getAttribute("class");
         radio.setAttribute("class", tempclass + " dot-click");

         //Filter Polls
         var poll = document.querySelectorAll('[show-poll="' + pollIndex + '"]');

         poll.forEach(function (res) {
            //Show Results Polls
            res.style.display = "flex";
         });

         // Enable the selected radio button to maintain the selection
         radio.disabled = false;
        const pollIdd = radio.getAttribute('data-poll-id');
        const choiceIdd = radio.getAttribute('data-choice-id');

        // Set the hidden inputs' values
        document.getElementById('poll_id').value = pollIdd;
        document.getElementById('choice_id').value = choiceIdd;

        // Submit the form
        document.getElementById('vote-form').submit();
         }
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
                            showProgressBar(pollId);
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

      </script>
</body>
</html>
</html>