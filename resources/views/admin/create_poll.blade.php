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
   <link rel="stylesheet" href="{{asset('n-css/alert.css')}}"/>

   <!-- Custom Framework -->
   @vite('../resources/css/app.css')
   <title>Poll</title>
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
   <form id="createPollForm" action="/create/poll" method="POST">
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
   <input type="button" name="Submit" value="Submit" id="submitcrpoll" onclick="toshowactions('You are about to create a poll.', this, 2)">
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
                    (soon)
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
                        var requiredInputs = document.querySelectorAll('input[required]');

                        //Check all input
                        var hasEmptyRequiredInput = Array.from(requiredInputs).some(function(input) {
                        return input.value.trim() === '';
                        });

                        console.log("IsEmpty: " + hasEmptyRequiredInput);

                        if(!hasEmptyRequiredInput) {
                            form.submit();
                        }
                        else {
                            textshowalert.innerText = 'Cant process. Some Input are not filled.';
                            window.location.reload();
                        }
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