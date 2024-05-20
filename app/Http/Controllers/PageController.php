<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use App\Models\Choice;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
                $choice = $vote->choice->id; // Change to id instead of choice text
        
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
                        'votes' => $votes
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
    public function register(){
        $division = Division::get();
        return view("register",compact('division'));
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
    
            // Add the poll data to the array
            $pollsData[] = [
                'poll' => $poll,
                'choices' => $voteChoices,
                'allChoices' => $allChoices,
                'userVote' => $userVote,
                'totalCount' => $totalCount,
                'hasVoted' => $userVote ? true : false
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
    
            // Add the poll data to the array
            $pollsData[] = [
                'poll' => $poll,
                'choices' => $voteChoices,
                'allChoices' => $allChoices,
                'userVote' => $userVote,
                'totalCount' => $totalCount,
                'hasVoted' => $userVote ? true : false
            ];
        }
    
        return view("admin.poll", compact("pollsData", "user", "finalOverallVoteCount"));
    }
    
    public function admin_createpoll(Request $request) {
        //Retrieve the input data
        $pollName = $request->input('title');
        $pollDeadline = $request->input('deadline');
        // $pollBodies = $request->input('poll_body');
        $lastPoll = Poll::latest()->first();
        //Perform validation
        $validated = $request->validate([
            'poll_title' => 'required|string|max:60',
            'poll_desc' => 'required',
            'poll_deadline' => 'required|date',
            'poll_body' => 'array|min:1',
            'poll_body.*' => 'string|max:255',

        ]);

        
        $user = Auth()->user()->id;
        
        Poll::create(['title'=>$validated['poll_title'],'description'=>$validated['poll_desc'],'deadline'=>$validated['poll_deadline'],'created_by'=>$user]);
        foreach ($validatedData['poll_body'] as $option) {
            Choice::create([
                'poll_id'=> $lastPoll->id,
                'choices' => $option
            ]);
           
        }
        


        //Silahkan diubah, mau tambahin message atau apa kek
        return redirect()->back();
    }



    public function admin_screatepoll() {
        return view("admin.create_poll");
    }

    
   

}