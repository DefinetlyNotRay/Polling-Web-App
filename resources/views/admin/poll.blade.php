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
   <title>Poll</title>
   @vite('resources/css/app.css')</head>

</head>
<script src="{{asset('n-js/poll.js')}}"></script>
<body>

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
           @php
               // Calculate total percentage of votes for all choices
               $totalPercentage = 0;
               foreach ($pollData['allChoices'] as $choice) {
                   foreach ($finalOverallVoteCount[$pollData['poll']->id] as $choicess => $details) {
                       if ($choice->id === $choicess) {
                           $totalPercentage += $details['percentage'];
                       }
                   }
               }
           @endphp

           @foreach ($pollData['allChoices'] as $choice)
               <div class="flex-select-poll">
                   <div class="dot-poll" data-poll-index="0" data-option-index="0" onclick="trigger(this);"></div>
                   <li class="list-none">{{ $choice->choice }}</li>
               </div>

               <!-- Render the progress bar based on the total percentage -->
               <div class="poll" id="bar-select-poll" show-poll="0">
                   @php
                       $isOnlyUserDivision = $pollData['userVote'] && $choice->id === $pollData['userVote']->choice_id;
                   @endphp
                   <div class="progress-bar" style="width: 100%; background-color: #ccc;">
                       <div class="h-8 mb-{{ $isOnlyUserDivision ? '0' : '2' }}" style="width: {{ $totalPercentage }}%; background-color: {{ $isOnlyUserDivision ? '#3BD138' : '#E93232' }};">
                           &nbsp;
                       </div>
                   </div>
               </div>
           @endforeach
       </div>
       <div class="outlines">&nbsp;</div>
   </div>
@endforeach


   </section>
   <!-- Accounts -->
   <section id="conport2">
   <p class="username">Hello Username!</p>
   <div class="outlines">&nbsp;</div>
   <div class="con-info">
      <p>Change Password</p>
      <div class="box-pass">
         <p>Change</p>
      </div>
   </div>
   <div class="outlines">&nbsp;</div>
   <div class="con-info">
      <p>Logout</p>
      <div class="box-pass box-pass-sec">
         <p>Logout</p>
      </div>
   </div>
   <div class="outlines">&nbsp;</div>
   </section>
      <script src="{{asset('n-js/poll.js')}}"></script>

</body>
</html>
</html>