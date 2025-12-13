<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FcmTokenController extends Controller
{
    /**
     * Store or update FCM token for authenticated user
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            $user->fcm_token = $request->token;
            $user->save();

            Log::info('FCM token saved for user: ' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'FCM token saved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving FCM token: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving FCM token'
            ], 500);
        }
    }

    /**
     * Remove FCM token (on logout)
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            $user->fcm_token = null;
            $user->save();

            Log::info('FCM token removed for user: ' . $user->id);

            return response()->json([
                'success' => true,
                'message' => 'FCM token removed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing FCM token: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error removing FCM token'
            ], 500);
        }
    }
}
