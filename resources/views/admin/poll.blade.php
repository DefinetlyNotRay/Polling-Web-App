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

   <!-- Custom Framework -->
   @vite('resources/css/app.css')
   <title>Poll</title>
</head>
<script src="{{asset('n-js/poll.js')}}"></script>
<body>

   <!-- Navbar-->
   <div class="">
      <nav class="flex items-center justify-between py-5 bg-nav">
          <div class="flex items-center">
              <p class="ml-5">&nbsp;</p>
          </div>
          <div class="flex justify-center flex-grow"> <!-- Center content -->
              <ul class="flex gap-5">
                  @if(auth()->check()) {{-- Check if user is authenticated --}}
                  @if(auth()->user()->role === 'admin')
                      {{-- User is an admin --}}
                      <li><a class="font-medium text-white" href="../">Home</a></li>
                      <li><a class="font-bold text-white" href="{{route('adminpoll')}}">Polls</a></li>
                  @else
                      {{-- User is a normal user --}}
                      <li><a class="font-medium text-white" href="../">Home</a></li>
                      <li><a class="font-bold text-white" href="{{route('userpoll')}}">Polls</a></li>
                  @endif
              @endif

              </ul>
          </div>
          <div class="flex items-center">
              <a href="#" class="mr-5" onclick="section()"><img src="{{ asset('assets/Group 6.png') }}" class="w-7" alt=""></a>
          </div>
          </div>
      </nav>
  </div>
   <!-- Section Container -->
   <!-- View Polls -->
   <section id="conport1">
      <div class="do-flex">
      <p>Polls</p>
      <button type="button" class="createpoll" onclick="window.location = '{{route('screatepoll')}}'">Create A Poll</button>
      </div>
      <div id="list-polls">
  
          @php
              $row = 0;
          @endphp
  
          @foreach($poll as $po)
              @if($po['status'] == true)
                  <div id="polls">
                      <p>{{ $po['title'] }}</p>
                      <p>Created by: {{ $po['user'] }} | Deadline: {{ $po['timeout'] }}</p>
  
                      <!-- Selecting Polls -->
                      <div class="select-poll">
                          @php
                              $column = 0;
                              $countvote = 0;
                          @endphp
  
                          <!-- Iterate over poll options -->
                          @foreach($po['polls'] as $selpol)
                              @php
                                  $cbar = 1 * ($column + 1) % 2;
                                  $votes = intval($po['votes'][$column]); // Get votes for current poll option
                                  $totalVotes = array_sum($po['votes']); // Total votes for all options
                                  $percentage = ($totalVotes > 0) ? round(($votes / $totalVotes) * 100, 2) : 0;
                                  $barClass = ($cbar === 0) ? 'green' : 'red';
                              @endphp
  
                              <div class="flex-select-poll">
                                  <div class="dot-poll" data-poll-index="{{ $row }}" data-option-index="{{ $column }}" onclick="trigger(this, {{$totalVotes}}, {{$votes}})"></div>
                                  <p>{{ $selpol }}</p>
                              </div>
  
                              <!-- Poll bar -->
                              <div id="bar-select-poll" show-poll="{{ $row }}">
                                  <div class="bar-selected-poll {{ $barClass }}" style="width: {{ $percentage }}%;" input-poll="{{$row}}-{{$column}}" >
                                  </div>
                              </div>
  
                              @php
                                  $column++;
                              @endphp
                          @endforeach
  
                      </div>
                      <div class="outlines"></div>
                  </div>
               @elseif($po['status'] == false)
               <div id="polls">
                  <p>{{ $po['title'] }}</p>
                  <p>Created by: {{ $po['user'] }} | Deadline: {{ $po['timeout'] }}</p>

                  <!-- Selecting Polls -->
                  <div class="select-poll">
                      @php
                          $column = 0;
                          $countvote = 0;
                      @endphp
    <button type="button" class="bg-[#E93232] px-4 py-1 font-bold" onclick="todeletepoll()">Delete</button>
</div>

                      <!-- Iterate over poll options -->
                      @foreach($po['polls'] as $selpol)
                          @php
                              $cbar = 1 * ($column + 1) % 2;
                              $votes = intval($po['votes'][$column]); // Get votes for current poll option
                              $totalVotes = array_sum($po['votes']); // Total votes for all options
                              $percentage = ($totalVotes > 0) ? round(($votes / $totalVotes) * 100, 2) : 0;
                              $barClass = ($cbar === 0) ? 'green' : 'red';
                          @endphp

                          <div class="flex-select-poll">
                              <div class="dot-poll" data-poll-index="{{ $row }}" data-option-index="{{ $column }}"></div>
                              <p>{{ $selpol }}</p>
                          </div>

                          <!-- Poll bar -->
                          <div id="bar-select-poll" style="display: flex;" show-poll="{{ $row }}">
                              <div class="bar-selected-poll {{ $barClass }}" style="width: {{ $percentage }}%;" input-poll="{{$row}}-{{$column}}">
                                  {{ round($percentage) }}%
                              </div>
                          </div>

                          @php
                              $column++;
                          @endphp
                      @endforeach

                  </div>
                  <div class="outlines"></div>
              </div>
              @endif
  
              @php
                  $row++;
              @endphp
          @endforeach
  
      </div>
   </section>
     <!-- Accounts -->
   <section id="conport2">
   <p class="username">Hello {{ucfirst(auth()->user()->username)}}!</p>
   <div class="outlines"></div>
   <div class="con-info">
      <p>Change Password</p>
      <div class="box-pass" onclick="tochangepass()">
         <p>Change</p>
      </div>
   </div>
   <div class="outlines"></div>
   <div class="con-info">
      <p>Logout</p>
      <div class="box-pass box-pass-sec" onclick="tologout()">
        <a href="#">
            <p>Logout</p>
         </a>      </div>
   </div>
   <div class="outlines"></div>
   </section>
      <script>
        function todeletepoll() {
            let confirmChange = confirm('Are you sure you want delele this poll?');
            if (!confirmChange) {
                alert('Ok. Aborted.');
            } else {
                window.location = '{{route('screatepoll')}}';
            }
        }
            
        function tochangepass() {
            let confirmChange = confirm('Do you want to change the password?');
            if (!confirmChange) {
                alert('Ok. Aborted.');
            } else {
                // Put your password change logic here
            }
        }


        function tologout() {
            let confirmChange = confirm('Do you want to logout?');
            if (!confirmChange) {
                alert('Ok. Aborted.');
            } else {
                window.location = "/logout";
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