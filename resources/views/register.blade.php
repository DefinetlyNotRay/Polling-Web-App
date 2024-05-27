<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{asset('n-css/alert.css')}}">

</head>
<body class=" bg-background-black h-screen flex justify-center items-center">
    @if(session('error'))
    <div id="modif-do-alert">
    <div id="do-alert">
        <span>{{session('error')}}</span>
        <div class="line" style="background-color: #e93232"></div>
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

    <div class="flex flex-col justify-center items-start">

        <div class="flex flex-col gap-20 justify-center items-center ">
         <h1 class="text-3xl font-bold text-white">REGISTER</h1>
         <form class="flex-col justify-center items-center flex w-full gap-4" action="{{route('formregister')}}" method="post">

             @csrf
             <div class="flex flex-col w-96 ">
                 <label class="text-white text-2xl font-bold" for="">Username</label>
                 <input type="text" class="px-2 py-1" name="username" oninput="validateInput(event)"    >
             </div>
             <div class="flex flex-col w-96 ">
                 <label class="text-white text-2xl font-bold" for="">Password</label>
                 <input type="password" class="px-2 py-1" name="password" >        
             </div>
             <div class="flex flex-col w-96 ">
                 <label class="text-white text-2xl font-bold" for="">Division</label>
                 <select name="division" class="px-2 py-1" id="">
                     @foreach($division as $divisions)
                     <option value="{{$divisions->id}}">{{$divisions->name}}</option>
                     @endforeach
                 </select>
             </div>
             <input type="hidden" name="api_token" value="{{Str::random(60)}}">
             <input type="hidden" name="expiry_token" value="{{now()->addHours(24)}}">
            
             <button type="submit" class="bg-success mt-4 w-96 text-lg py-1 font-bold">Register</button>
         </form>
        </div>
        <p class="text-white font-bold text-sm mt-1">Have an Account? <a href="/login"><span class="text-gray-600 underline">Login!</span></a></p>
    </div>
    <script>
                function validateInput(event) {
            const input = event.target.value;
            const regex = /^[a-zA-Z]*$/; // Allow only alphabetic characters
            if (!regex.test(input)) {
                event.target.value = input.slice(0, -1); // Remove the last character if it's not alphabetic
            }
        }

    </script>

</body>

</html>

