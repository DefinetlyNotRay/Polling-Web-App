<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Password</title>
    <link rel="stylesheet" href="{{asset('n-css/poll.css')}}"/>
    @vite('resources/css/app.css')

</head>
<body class="">
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
    <div class="h-screen flex justify-center items-center" id="conport1">
        <div class="flex flex-col justify-center items-start" >
    
            <div class="flex flex-col gap-20 justify-center items-center ">
             <h1 class="text-3xl font-bold text-white">Change Password</h1>
             <form class="flex-col justify-center items-center flex w-full gap-4" action="/change/password" method="post">
                 @csrf
                 <div class="flex flex-col w-96 ">
                     <label class="text-white text-2xl font-bold" for="">Old Password</label>
                     <input type="password" class="px-2 py-1" name="oldPassword">
                 </div>
                 <div class="flex flex-col w-96 ">
                     <label class="text-white text-2xl font-bold" for="">New Password</label>
                     <input type="password" class="px-2 py-1" name="newPassword" >        
                 </div>
                
                 <button type="submit" class="bg-success mt-4 w-96 text-lg py-1 font-bold">Submit</button>
             </form>
         </div>             
        </div>
    </div>
    <section id="conport2">
        <p class="username">Hello {{ucfirst(Auth()->user()->username)}}!</p>
        <div class="outlines">&nbsp;</div>
     
        <div class="con-info">
           <p>Change Password</p>
           <div class="box-pass">
             <a href="/changepassword">
              <p>Change</p>
           </a>
           </div>
        </div>
        <div class="outlines">&nbsp;</div>
     
        <div class="con-info">
           <p>Logout</p>
           <div class="box-pass box-pass-sec">
              <a href="/logout">
                 <p>Logout</p>
              </a>
           </div>
        </div>
        <div class="outlines">&nbsp;</div>
     
        </section>
        <script>
            function section() {
                var port1 = document.getElementById('conport1');
                var port2 = document.getElementById('conport2');
                if(port2.style.display === 'none' || port2.style.display === '') {
                    port1.style.display = 'none';
                    port2.style.display = 'flex';
                }
                else {
                    port1.style.display = 'flex';
                    port2.style.display = 'none';
                }
            }
        </script>
</body>
</html>
