<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DelayReport;
use Illuminate\Http\JsonResponse;
use App\Services\DelayReportLockService;


class AgentController extends Controller
{
    public function assignDelayReport(User $user) : JsonResponse 
    {   
        if ($user->hasOpenDelayReports()) {
            return response()->json(['message'=> 'agent already has delay report'], 400);
        }

        $latestOpenDelayReport = DelayReport::whereState('created')->whereNull('user_id')->orderBy('created_at', 'desc')->first();

        if (!$latestOpenDelayReport)
        {
            return response()->json(['message'=> 'delay report not found'], 404);
        }

        if (!DelayReportLockService::lock($latestOpenDelayReport->id)) 
        {
            return response()->json(['message' => 'somthing went wrong'], 409);
        }
        
        $latestOpenDelayReport->user_id = $user->id;
        $latestOpenDelayReport->state = 'assigned';
        $latestOpenDelayReport->save();

        return response()->json(
            [
                'message' => 'delay report assigned successfully',
                'data' =>  $latestOpenDelayReport
            ]
        );

    }
}
