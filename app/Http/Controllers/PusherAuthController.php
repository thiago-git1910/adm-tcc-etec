<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

class PusherAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        if (Auth::guard('sanctum')->check()) {
            // Return the authentication response for Pusher
            return Broadcast::auth($request);
        }
        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
