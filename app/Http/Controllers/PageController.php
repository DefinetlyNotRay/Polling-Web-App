<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $choice = $vote->choice->choice;

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
        foreach ($data['choices'] as $choice => $details) {
            $divisionVotes = []; // Array to store votes for each choice within each division
            
            // Count votes for each choice within each division
            foreach ($details['divisions'] as $division => $votes) {
                if (!isset($divisionVotes[$choice])) {
                    $divisionVotes[$choice] = 0;
                }
                $divisionVotes[$choice] += $votes;
            }
            
            // Find the choice with the maximum votes across divisions
            $maxVotes = 0;
            $majorityChoice = null;
            foreach ($divisionVotes as $choice => $votes) {
                if ($votes > $maxVotes) {
                    $maxVotes = $votes;
                    $majorityChoice = $choice;
                }
            }
            
            $percentage = ($maxVotes / $data['totalVotes']) * 100; // Calculate percentage based on maxVotes
            $finalOverallVoteCount[$pollId][$majorityChoice] = [
                'percentage' => $percentage,
                'divisions' => $details['divisions'],
                'count' => $maxVotes, // Store the count based on maxVotes
                'majority_choice' => $majorityChoice, // Store the majority choice
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
                  
                    $pollsData[] = [
                        'poll' => $poll,
                        'choices' => $voteChoices,
                        'allChoices' => $allChoices, // Include all choices for the poll
                        'vote' => $vote,
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

    //Part of VGJR
    public function user_showpoll() {
    $time = time();
    $dateFormatted = date('m/d/Y, h:i:s', $time);

    // "Example"
    $poll = [
        [
            "title" => "Ayam apa Telur?",
            "user" => "ahmad",
            "timeout" => $dateFormatted,
            "polls" => [
                0 => "Ayam",
                1 => "Telur"
            ],
            "votes" => [
                0 => "3",
                1 => "0"
            ],
            "status" => true
        ],
        [
            "title" => "Bubur diaduk apa gak diaduk",
            "user" => "amongus",
            "timeout" => $dateFormatted,
            "polls" => [
                0 => "Apa Coba",
                1 => "Gak"
            ],
            "votes" => [
                0 => "19",
                1 => "5"
            ],
            "status" => false
        ]
    ];    

        return view("user.poll", ['poll' => $poll]);
    }

    public function admin_showpoll()
    {
        $finalOverallVoteCount = $this->countMajorityVotes();
        // Retrieve all votes for the authenticated user
        $votes = Vote::where("user_id", auth()->user()->id)->get();
        
        // Retrieve all polls and eager load choices and user
        $polls = Poll::with('choices', 'user')->get();
        
        $user = Auth::user();
       
        $vote = Vote::all();
        // Initialize array to store poll data
        $pollsData = [];
        
     

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
    
        
        return view("admin.poll", compact("pollsData", "user", "finalOverallVoteCount"));
    }
    
    
   
}