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
</head>
<script src="{{asset('n-js/poll.js')}}"></script>
<body>

   <!-- Navbar-->
   <div class="bar">
      <div class="ce-bar">
         <p onclick="window.location = '../'">Home</p>
         <p onclick="window.location = '{{route('userpoll')}}">Polls</p>
      </div>
      <div class="acc">
         <div class="ellipse" onclick="section()"></div>
      </div>
   </div>

   <!-- Section Container -->
   <!-- View Polls -->
   <section id="conport1">
   <p>Polls</p>
   <div id="list-polls">
   
   <!-- gw buatin design nya aja ya -->
   <!-- start of forEach -->
   <div id="polls">
   <p>Ayam apa Telur?</p>
   <p>Created by: (user) | Deadline: (timeout)</p>

   <!-- for Selecting Polls -->
   <div class="select-poll">
   <!-- forEach again -->
   <!-- Poll 1 -->
   <div class="flex-select-poll">
      <div class="dot-poll" data-poll-index="0" data-option-index="0" onclick="trigger(this);"></div>
   <p>Ayam</p>
   </div>
   <div id="bar-select-poll" show-poll="0">
      <div class="bar-selected-poll red"></div>
   </div>
   <!-- Poll 2 -->
   <div class="flex-select-poll">
   <div class="dot-poll" data-poll-index="0" data-option-index="1" onclick="trigger(this);"></div>
   <p>Telur</p>
   </div>
   <div id="bar-select-poll" show-poll="0">
      <div class="bar-selected-poll green"></div>
   </div>
   </div>
   <!-- end forEach -->
   </div>
   <div class="outline"></div>
   <!-- end of forEach -->
   </div>
   </section>
   <!-- Accounts -->
   <section id="conport2">
   <p class="username">Hello Username!</p>
   <div class="outline"></div>
   <div class="con-info">
      <p>Change Password</p>
      <div class="box-pass">
         <p>Change</p>
      </div>
   </div>
   <div class="outline"></div>
   <div class="con-info">
      <p>Logout</p>
      <div class="box-pass box-pass-sec">
         <p>Logout</p>
      </div>
   </div>
   <div class="outline"></div>
   </section>
</body>
</html>