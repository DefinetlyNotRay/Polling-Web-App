<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="{{asset('n-css/poll.css')}}"/>

    <!-- Link to your CSS file (assuming you have one) -->
    @vite('resources/css/app.css')
</head>
    
<script src="{{asset('n-js/poll.js')}}"></script>
<body class="bg-background-black">
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
        @if(!empty($pollsData))
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
                <h2 class="mb-4 text-3xl font-bold text-white">Votes</h2>
                @foreach($pollsData as $pollData)
                    @php
                        // Initialize total division votes for the current poll
                        $totalDivisionVotes = 0;
                        
                        // Keep track of divisions that have voted for the current poll
                        $votedDivisions = [];
                        
                        // Iterate over choices to collect divisions that have voted
                        foreach ($finalOverallVoteCount[$pollData['poll']->id] as $choiceId => $details) {
                            foreach ($details['divisions'] as $division => $votes) {
                                if (!in_array($division, $votedDivisions)) {
                                    $votedDivisions[] = $division;
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
                                $isMajorityChoice = $details && isset($details['divisions'][$userDivision]) && $details['majority_choice'] === $choice->choice;
                                $isOnlyUserDivision = $details && count($details['divisions']) === 1 && isset($details['divisions'][$userDivision]);
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
            <p>No polls found for this user.</p>
        @endif
    </div>
    <section id="conport2">
        <p class="username">Hello {{ucfirst(Auth()->user()->username)}}!</p>
        <div class="outlines">&nbsp;</div>
     
        <div class="con-info">
           <p>Change Password</p>
           <div class="box-pass" onclick="tochangepass()">
              <p>Change</p>
           </div>
        </div>
        <div class="outlines">&nbsp;</div>
     
        <div class="con-info">
           <p>Logout</p>
           <div class="box-pass box-pass-sec" onclick="tologout()">
            <a href="#">
                <p>Logout</p>
             </a>           </div>
        </div>
        <div class="outlines">&nbsp;</div>
     
        </section>
        <script>

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
}       </script>
</body>
</html>

