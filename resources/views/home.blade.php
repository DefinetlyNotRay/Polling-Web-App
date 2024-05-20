<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <!-- Link to your CSS file (assuming you have one) -->
    <link rel="stylesheet" href="{{asset('n-css/home.css')}}">
    @vite('resources/css/app.css')</head>
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
                        <li><a class="font-bold text-white" href="/home">Home</a></li>
                        <li><a class="font-medium text-white" href="{{route('adminpoll')}}">Polls</a></li>
                    @else
                        {{-- User is a normal user --}}
                        <li><a class="font-bold text-white" href="/home">Home</a></li>
                        <li><a class="font-medium text-white" href="{{route('userpoll')}}">Polls</a></li>
                    @endif
                @endif

                </ul>
            </div>
            <div class="flex items-center">
                <a href="" class="mr-5"><img src="{{ asset('assets/Group 6.png') }}" class="w-7" alt=""></a>
            </div>
            </div>
        </nav>
    </div>
    <div class="flex flex-col pl-36 pt-36">
        @if(!empty($pollsData))
        <div>

        <h1 class="mb-5 text-3xl font-bold text-white">{{ucfirst($user->username)}}</h1>
        <div class="flex mb-12 gap-5 mb-5">
            <div class="flex gap-2">
                <img src="{{asset('assets/Rectangle 11.png')}}" alt=""> <p class="text-white font-semibold">Your Vote</p>
            </div>
            <div class="flex gap-2">
                <img src="{{asset('assets/Rectangle 12.png')}}" alt="">  <p class="text-white font-semibold">Oposing Vote</p>
            </div>
        </div>
        <h2 class="text-3xl font-bold mb-4 text-white" >Votes</h2>
        @foreach($pollsData as $pollData)
        @php
            // Initialize total division votes for the current poll
            $totalDivisionVotes = 0;
            
            // Keep track of divisions that have voted for the current poll
            $votedDivisions = [];
            
            // Iterate over choices to collect divisions that have voted
            foreach ($finalOverallVoteCount[$pollData['poll']->id] as $choice => $details) {
                foreach ($details['divisions'] as $division => $votes) {
                    if (!in_array($division, $votedDivisions)) {
                        $votedDivisions[] = $division;
                    }
                }
            }
            
            // Total division votes for the current poll
            $totalDivisionVotes = count($votedDivisions);
        @endphp
    
        <p class="text-2xl mb-3 font-bold text-white">{{ $pollData['poll']->title }}</p>
        <p class="text-white mb-3 text-lg font-semibold">Total Votes: {{ $totalDivisionVotes }}</p>
    
        <div class="w-[40%]">
            @foreach($finalOverallVoteCount[$pollData['poll']->id] as $choicess => $details)
                <div class="poll">
                    @php
                        // Check if the current choice is the majority choice for the user's division
                        $userDivision = auth()->user()->division->name;
                        $isMajorityChoice = isset($details['divisions'][$userDivision]) && $details['majority_choice'] === $choicess;
                        $isOnlyUserDivision = count($details['divisions']) === 1 && isset($details['divisions'][$userDivision]);
                    @endphp
                    <p class="font-bold text-lg text-[{{ $isOnlyUserDivision ? '#3BD138' : '#E93232' }}]">{{ $choicess }}: {{ $details['percentage'] }}%</p>
    
                    <div class="progress-bar" style="width: 100%; background-color: #ccc;">
                        <div class="h-8 mb-{{ $isOnlyUserDivision ? '0' : '2' }}" style="width: {{ $details['percentage'] }}%; background-color: {{ $isOnlyUserDivision ? '#3BD138' : '#E93232' }};">
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
    
    
        @else
            <p>No polls found for this user.</p>
        @endif

    </div>
</body>
</html>


