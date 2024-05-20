<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Vote;
use App\Models\Choice;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
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
public function vote(Request $request){

        $request->validate([
            'choice_id' => 'required|integer',
            'poll_id' => 'required|integer',
        ]);
    
        // Get the validated data
        $choiceId = $request->input('choice_id');
        $pollId = $request->input('poll_id');
    
        // Get authenticated user's ID and division ID
        $userId = auth()->user()->id;
        $divisionId = auth()->user()->division_id;
    
        // Create a new vote entry
        Vote::create([
            'poll_id' => $pollId,
            'choice_id' => $choiceId,
            'user_id' => $userId,
            'division_id' => $divisionId
        ]);

   
    return redirect("/admin/poll");


}
public function voteUser(Request $request){

    $request->validate([
        'choice_id' => 'required|integer',
        'poll_id' => 'required|integer',
    ]);

    // Get the validated data
    $choiceId = $request->input('choice_id');
    $pollId = $request->input('poll_id');

    // Get authenticated user's ID and division ID
    $userId = auth()->user()->id;
    $divisionId = auth()->user()->division_id;

    // Create a new vote entry
    Vote::create([
        'poll_id' => $pollId,
        'choice_id' => $choiceId,
        'user_id' => $userId,
        'division_id' => $divisionId
    ]);


return redirect("/poll");


}
}