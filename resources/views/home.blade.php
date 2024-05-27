<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="{{asset('n-css/poll.css')}}"/>
    <link rel="stylesheet" href="{{asset('n-css/alert.css')}}"/>

    <!-- Link to your CSS file (assuming you have one) -->
    @vite('resources/css/app.css')
</head>
    
<body class="bg-background-black">
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

    @if(session('success'))
    <div id="modif-do-alert">
    <div id="do-alert">
        <span>{{session('success')}}</span>
        <div class="line"></div>
      </div>
    </div>
      <script>
        // Close the alert after the animation completes (5 seconds)
        setTimeout(() => {
          const alert = document.getElementById('do-alert');
          const alert2 = document.getElementById('modif-do-alert');
          alert.style.animation = 'fadeOut 1s forwards';
          alert.addEventListener('animationend', () => {
            alert.remove();
            alert2.remove();
          });
        }, 3500);
      </script>
      @endif
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
                        <li><a class="font-bold text-white" href="/">Home</a></li>
                        <li><a class="font-medium text-white" href="{{route('adminpoll')}}">Polls</a></li>
                    @else
                        {{-- User is a normal user --}}
                        <li><a class="font-bold text-white" href="/">Home</a></li>
                        <li><a class="font-medium text-white" href="{{route('userpoll')}}">Polls</a></li>
                    @endif
                @endif

                </ul>
            </div>
            <div class="flex items-center">
                <button onclick="section()" href=""  class="mr-5"><img src="{{ asset('assets/Group 6.png') }}" class="w-7" alt=""></button>
            </div>
            </div>
        </nav>
    </div>
    <div class="flex flex-col pt-[4rem] pl-36 " id="conport1">
        <div>
            <h1 class="mb-5 text-3xl font-bold text-white">{{ ucfirst($user->username) }}</h1>
            <div class="flex gap-5 mb-5 mb-12">
                <div class="flex gap-2">
                    <img src="{{ asset('assets/Rectangle 11.png') }}" alt=""> <p class="font-semibold text-white">Your Vote</p>
                </div>
                <div class="flex gap-2">
                    <img src="{{ asset('assets/Rectangle 12.png') }}" alt="">  <p class="font-semibold text-white">Opposing Vote</p>
                </div>
            </div>
            @if(!empty($pollsData))
                <h2 class="mb-4 text-3xl font-bold text-white">Votes</h2>
                @foreach($pollsData as $pollData)
                    @php
                        // Initialize total division votes for the current poll
                        $totalDivisionVotes = 0;
    
                        // Keep track of divisions that have voted for the current poll
                        $votedDivisions = [];
    
                        // Iterate over choices to collect divisions that have voted
                        if (isset($finalOverallVoteCount[$pollData['poll']->id])) {
                            foreach ($finalOverallVoteCount[$pollData['poll']->id] as $choiceId => $details) {
                                foreach ($details['divisions'] as $division => $votes) {
                                    if (!in_array($division, $votedDivisions)) {
                                        $votedDivisions[] = $division;
                                    }
                                }
                            }
                        }
    
                        // Total division votes for the current poll
                        $totalDivisionVotes = count($votedDivisions);
                    @endphp
    
                    <p class="mb-3 text-2xl font-bold text-white">{{ $pollData['poll']->title }}</p>
                    <p class="mb-3 text-lg font-semibold text-white">Total Votes: {{ $totalDivisionVotes }}</p>
    
                    <div class="w-[40%]">
                        @foreach($pollData['allChoices'] as $choice)
                            @php
                                $details = $finalOverallVoteCount[$pollData['poll']->id][$choice->id] ?? null;
                                $userDivision = auth()->user()->division->name;
                                $percentage = $details ? $details['percentage'] : 0;
                                $isUserVote = $pollData['userVote'] && $choice->id === $pollData['userVote']->choice_id;
                            @endphp
    
                            <div class="poll">
                                <p class="text-lg font-bold" style="color:{{ $isUserVote ? '#3BD138' : '#E93232' }}">{{ $choice->choice }}: {{ round($percentage) }}%</p>
                                <div class="progress-bar" style="width: 100%; background-color: #ccc;">
                                    <div class="h-8 mb-{{ $isUserVote ? '0' : '2' }}" style="width: {{ $percentage }}%; background-color: {{ $isUserVote ? '#3BD138' : '#E93232' }};">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="">
                            &nbsp;
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-white">No polls found for this user.</p>
        @endif
    </div>
</div>
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


