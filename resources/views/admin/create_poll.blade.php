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
   @vite('../resources/css/app.css')
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
              <a href="#" class="mr-5" onclick="section()"><img src="{{ asset('assets/Group 6.png') }}" class="w-7" alt=""></a>
          </div>
          </div>
      </nav>
  </div>
  <section id="conport1" class="flex items-center justify-center">
   <form action="/create/poll" method="POST">
   @csrf
   <p id="textcport3">Create A Poll</p>
   <div class="container-2">
      <div id="popup-error-trim">
      <p id="text-error-trim">Test</p>
      </div>
   <p>Poll Name</p>
   <input type="text" onchange="testInput(this)" name="title" required>
   <p>Poll Description</p>

   <textarea style="height: 5em; color: black;" name="description"></textarea>
   <p>Poll Deadline</p>
   <input type="date" onchange="testInput(this)" name="deadline" required>
   <p>Poll Body</p>
   <div id="body_poll" class="relative">
    <div class="relative">

        <input type="text" onchange="testInput(this, false, 0, false)"  name="poll_body[]" placeholder="Write a option.." required>
        <button style="" class="absolute  text-[#272525] right-5 top-2 text-xl">-</button>
    </div>
    <div class="relative">

        <input type="text" onchange="testInput(this, true, 1, false)"  name="poll_body[]" placeholder="Write a option.." required>
        <button style="" class="absolute  text-[#272525] right-5 top-2 text-xl">-</button>
    </div>
  

   <!-- Automatic Create's Another -->
   </div>
   <input type="submit" name="Submit" id="submitcrpoll">
   </div>
   </form>
</section>
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
         </a>       </div>
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
    function testInput(info, bool, number, isallowdelete) {
    var pollbody = document.querySelectorAll('[name="poll_body"]');
    
    if (info.value.trim() !== '' && bool === true && number < 4) {
            // Create a new input element
            const newInput = document.createElement('input');
            const newDiv = document.createElement('div');
            const newP = document.createElement('button');
            newP.className = 'absolute text-[#272525] right-5 top-2 text-xl';
            newDiv.className = 'relative';
            newP.innerText = '-'
            newDiv.setAttribute('id', 'div1');
            newP.setAttribute('id','p1');
            newInput.type = 'text';
            newInput.name = `poll_body[]`;
            newInput.className = 'inputforpoll';
            newInput.placeholder = 'Write an option..';
            newInput.required = false;
            let tonumber = number + 1; // Increment number
            newInput.setAttribute('onchange', 'testInput(this, true, ' + tonumber +', true)');
            
            // Append the new input element to the form
            const form = document.getElementById('body_poll');
            form.appendChild(newDiv);
            const div = document.getElementById('div1');

            div.appendChild(newInput);

            div.appendChild(newP);

    } else if (isallowdelete === true && number >= 2) {
        pollbody.forEach(function(res) {
        // Check if the value is empty and if it's one of the dynamically created inputs
        if (res.value.trim() === '') {
            info.remove();
            return;
        }
        });
    } 
    else if(info.value.trim() === '') {
        var diserror = document.getElementById('popup-error-trim');
        var texterror = document.getElementById('text-error-trim');
        diserror.style.display = 'block';
        texterror.innerText = 'Input "' + info.getAttribute('name') + '" masih kosong.';
        setTimeout(function() {
            diserror.style.display = 'none';
            texterror.innerText = null;
        }, 3000);
    }
}
</script>
</body>
</html>