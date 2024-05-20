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
                      <li><a class="font-medium text-white" href="../../">Home</a></li>
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
              <a href="#" class="mr-5" onclick="window.location = '{{route('adminpoll')}}'"><img src="{{ asset('assets/Group 6.png') }}" class="w-7" alt=""></a>
          </div>
          </div>
      </nav>
  </div>
  <section id="conport3">
   <form action="{{route('createpoll')}}" method="POST">
   @csrf
   <p id="textcport3">Create A Poll</p>
   <div class="container-2">
      <div id="popup-error-trim">
      <p id="text-error-trim">Test</p>
      </div>
   <p>Poll Name</p>
   <input type="text" onchange="testInput(this)" name="poll_name" required>
   <p>Poll Deadline</p>
   <input type="date" onchange="testInput(this)" name="poll_deadline" required>
   <p>Poll Body</p>
   <div id="body_poll">
   <input type="text" onchange="testInput(this, false, 0, false)"  name="poll_body" placeholder="Write a option.." required>
   <input type="text" onchange="testInput(this, true, 1, false)" name="poll_body" placeholder="Write a option.." required>
   <!-- Automatic Create's Another -->
   </div>
   <input type="submit" name="Submit" id="submitcrpoll">
   </div>
   </form>
</section>
</body>
</html>