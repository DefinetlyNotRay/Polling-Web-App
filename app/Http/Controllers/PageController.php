<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use App\Models\Choice;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    // Assuming you have the division information associated with each user, you can modify your code like this:

        public function countMajorityVotes()
        {
            $votes = Vote::with(['user.division', 'choice.poll'])->get();
            $overallVoteCount = [];
            
            foreach ($votes as $vote) {
                $pollId = $vote->choice->poll_id;
                $division = $vote->user->division->name;
                $choice = $vote->choice->id;
        
                if (!isset($overallVoteCount[$pollId])) {
                    $overallVoteCount[$pollId] = ['totalVotes' => 0, 'choices' => []];
                }
                if (!isset($overallVoteCount[$pollId]['choices'][$choice])) {
                    $overallVoteCount[$pollId]['choices'][$choice] = ['count' => 0, 'divisions' => []];
                }
                if (!isset($overallVoteCount[$pollId]['choices'][$choice]['divisions'][$division])) {
                    $overallVoteCount[$pollId]['choices'][$choice]['divisions'][$division] = 0;
                }
                $overallVoteCount[$pollId]['choices'][$choice]['divisions'][$division]++;
                $overallVoteCount[$pollId]['choices'][$choice]['count']++;
                $overallVoteCount[$pollId]['totalVotes']++;
            }
        
            $finalOverallVoteCount = [];
            
            foreach ($overallVoteCount as $pollId => $data) {
                foreach ($data['choices'] as $choiceId => $details) {
                    $divisionVotes = []; // Array to store votes for each choice within each division
                    
                    // Count votes for each choice within each division
                    foreach ($details['divisions'] as $division => $votes) {
                        if (!isset($divisionVotes[$choiceId])) {
                            $divisionVotes[$choiceId] = 0;
                        }
                        $divisionVotes[$choiceId] += $votes;
                    }
                    
                    // Find the choice with the maximum votes across divisions
                    $maxVotes = 0;
                    $majorityChoice = null;
                    foreach ($divisionVotes as $choiceId => $votes) {
                        if ($votes > $maxVotes) {
                            $maxVotes = $votes;
                            $majorityChoice = $choiceId;
                        }
                    }
                    
                    $percentage = ($maxVotes / $data['totalVotes']) * 100; // Calculate percentage based on maxVotes
                    $finalOverallVoteCount[$pollId][$choiceId] = [
                        'percentage' => $percentage,
                        'divisions' => $details['divisions'],
                        'count' => $maxVotes, // Store the count based on maxVotes
                        'majority_choice' => $majorityChoice, // Store the majority choice
                        'votes' => $maxVotes // Store the maximum votes
                    ];
                }
            }
        
            return $finalOverallVoteCount;
        }
        
        
    public function home()
    {
        $finalOverallVoteCount = $this->countMajorityVotes();
        // Retrieve all votes for the authenticated user
        $votes = Vote::where("user_id", auth()->user()->id)->get();
        $vote = Vote::all();
        // Initialize array to store poll data
        $pollsData = [];
        
        $user = Auth::user();
     

        // Check if any votes exist for the user
        if ($votes->isNotEmpty()) {
            foreach ($votes as $vote) {
                // Retrieve the corresponding poll for each vote
                $poll = Poll::find($vote->poll_id);
    
                if ($poll) {
                    // Retrieve all choices for the poll
                    $allChoices = Choice::where("poll_id", $poll->id)->get();
                    
                    // Check if 'choice_id' attribute is not empty or null
                    if (!empty($vote->choice_id)) {
                        // Retrieve choice for each vote
                        $voteChoice = Choice::find($vote->choice_id);
                        if ($voteChoice) {
                            $voteChoices = collect([$voteChoice]); // Wrap choice in a collection
                        } else {
                            $voteChoices = collect(); // Create an empty collection if choice is not found
                        }
                    } else {
                        $voteChoices = collect(); // Create an empty collection if 'choice_id' attribute is empty or null
                    }
                    $totalCount = 0;
                    foreach ($finalOverallVoteCount[$poll->id] as $choiceData) {
                        $totalCount += $choiceData['count'];
                       
                    }
                  
                    $userVote = $votes->where('poll_id', $poll->id)->first();

                    $pollsData[] = [
                        'poll' => $poll,
                        'choices' => $voteChoices,
                        'allChoices' => $allChoices, // Include all choices for the poll
                        'vote' => $vote,
                        'userVote' => $userVote,

                        'totalCount' => $totalCount // Add total count to poll data
                    ];
                }
            }
        }
    
        return view("home", compact("pollsData","user",'finalOverallVoteCount'));
    }
    
    
    


    public function login(){
        return view("login");
    }


    public function register() {
        $do = Division::get();
        return view("register", ['division' => $do]);
    }

    public function form_register(Request $request) {
        if(empty($request->input('username'))) {
            return back()->with('error', 'Authentication failed.');
        }

        $creds = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
            'division' => 'required',
        ]);
    
        if(!$creds) {
            return back()->with('error', 'Authentication failed.');   
        }
        // Check if the username is a string (this is redundant since `string` validation is already done)
        if (!is_string($creds['username'])) {
            return back()->with('error', 'Invalid username format.');
        }
    
        // Check if the username already exists
        if (User::where('username', $request->input('username'))->exists()) {
            return back()->with('error', 'Username has already been taken.');
        }
    
        // Create a new user
        $user = User::create([
            'username' => $creds['username'],
            'password' => bcrypt($creds['password']),
            'division_id' => $creds['division'],
            'role' => "user",
        ]);
    
        // Attempt to authenticate the user
         if($user) {
            return redirect()->route('login')->with('error', 'Account Created. Please do login.');
        }
        else {
            // Handle authentication failure
            return back()->with('error', 'Authentication failed.');
        }
    }

    //Part of VGJR
    public function user_showpoll() {
        $finalOverallVoteCount = $this->countMajorityVotes();
        $user = auth()->user();
        
        // Retrieve all polls and eager load choices and user
        $polls = Poll::with('choices')->get();
        
        // Initialize array to store poll data
        $pollsData = [];
    
        foreach ($polls as $poll) {
            // Retrieve all choices for the poll
            $allChoices = $poll->choices;
    
            // Check if the user has voted on this poll
            $userVote = Vote::where("user_id", $user->id)->where("poll_id", $poll->id)->first();
    
            // Get the choice the user voted for (if any)
            $voteChoices = collect();
            if ($userVote && !empty($userVote->choice_id)) {
                $voteChoice = Choice::find($userVote->choice_id);
                if ($voteChoice) {
                    $voteChoices = collect([$voteChoice]);
                }
            }
            $userVote = Vote::where("user_id", $user->id)->where("poll_id", $poll->id)->first();

    
            // Calculate total vote count for the poll
            $totalCount = 0;
            if (isset($finalOverallVoteCount[$poll->id])) {
                foreach ($finalOverallVoteCount[$poll->id] as $choiceData) {
                    $totalCount += $choiceData['count'];
                }
            }
            $deadlineOver = false;
            $deadline = Carbon::parse($poll->deadline);

            if($deadline->isPast()) {
                $deadlineOver = true;
            }
    
            // Add the poll data to the array
            $pollsData[] = [
                'poll' => $poll,
                'choices' => $voteChoices,
                'allChoices' => $allChoices,
                'userVote' => $userVote,
                'totalCount' => $totalCount,
                'hasVoted' => $userVote ? true : false,
                'deadlineOver' => $deadlineOver

            ];
        }
    
        return view("user.poll", compact("pollsData", "user", "finalOverallVoteCount"));
    }
    
    public function admin_showpoll()
    {
        $finalOverallVoteCount = $this->countMajorityVotes();
        $user = auth()->user();
        
        // Retrieve all polls and eager load choices and user
        $polls = Poll::with('choices')->get();
        
        // Initialize array to store poll data
        $pollsData = [];
    
        foreach ($polls as $poll) {
            // Retrieve all choices for the poll
            $allChoices = $poll->choices;
    
            // Check if the user has voted on this poll
            $userVote = Vote::where("user_id", $user->id)->where("poll_id", $poll->id)->first();
    
            // Get the choice the user voted for (if any)
            $voteChoices = collect();
            if ($userVote && !empty($userVote->choice_id)) {
                $voteChoice = Choice::find($userVote->choice_id);
                if ($voteChoice) {
                    $voteChoices = collect([$voteChoice]);
                }
            }
            $userVote = Vote::where("user_id", $user->id)->where("poll_id", $poll->id)->first();

    
            // Calculate total vote count for the poll
            $totalCount = 0;
            if (isset($finalOverallVoteCount[$poll->id])) {
                foreach ($finalOverallVoteCount[$poll->id] as $choiceData) {
                    $totalCount += $choiceData['count'];
                }
            }
            $deadlineOver = false;
            $deadline = Carbon::parse($poll->deadline);

            if($deadline->isPast()) {
                $deadlineOver = true;
            }
    
            // Add the poll data to the array
            $pollsData[] = [
                'poll' => $poll,
                'choices' => $voteChoices,
                'allChoices' => $allChoices,
                'userVote' => $userVote,
                'totalCount' => $totalCount,
                'hasVoted' => $userVote ? true : false,
                'deadlineOver' => $deadlineOver

            ];
        }
    
        return view("admin.poll", compact("pollsData", "user", "finalOverallVoteCount"));
    }
    
    public function admin_createpoll(Request $request) {
        // Combine the date from the form input with the current time
        $deadlineDateTime = Carbon::parse($request->deadline)->endOfDay();
    
        // Create the poll
        $user = auth()->user()->id;
        $poll = Poll::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $deadlineDateTime, // Use the combined date and 23:59:59 as time
            'created_by' => $user
        ]);
        
        // Get the ID of the newly created poll
        $pollId = $poll->id;

    // Process each input in 'poll_body[]'
    if ($request->has('poll_body')) {
        foreach ($request->poll_body as $option) {
            // Skip empty options
            if (empty($option)) {
                continue;
            }

            // Create choice for the poll
            Choice::create([
                'choice' => $option,
                'poll_id'=> $pollId
            ]);
        }
    }
        // Redirect back with success message
        return redirect("/admin/poll")->with('success', 'Poll successful created.');
    }
    



    public function admin_screatepoll() {
        return view("admin.create_poll");
    }
    public function changePassword(){
        return view("changepassword");
    }
    public function passwordChange(Request $request){
        $validate = $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required'
        ]);
        $user = auth()->user(); // Assuming the user is authenticated
        if (Hash::check($validate['oldPassword'], $user->password)) {
              // Old password matches, proceed to update the password
        $user->password = Hash::make($validate['newPassword']);
        $user->save();
        return redirect('/')->with('success', 'Password updated successfully.');

        }else {
            // Old password does not match, return with error
            return redirect()->back()->with('error', 'Invalid old password.');
        }

    }
    

   

}