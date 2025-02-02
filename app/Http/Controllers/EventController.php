<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Summary of generate_token
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function generate_token()
    {
        $eventToken = request()->user()->createToken('event-token')->plainTextToken;

        return response()->json([
            'eventToken' => $eventToken,
            'message' => 'success'
        ], 200);
    }
}
