<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'bus owner') {
            $locations = Location::all();
            return response()->json($locations);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'student') {
            $location = new Location;
            $location->user_id = $user->id;
            $location->longitude = $request->longitude;
            $location->latitude = $request->latitude;
            $location->save();

            return response()->json([
                'status' => true,
                'message' => 'Location created successfully',
                'data' => $location
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json([
                'status' => false,
                'message' => 'Location not found'
            ], 404);
        }

        return response()->json($location);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        if ($user->role === 'student') {
            $location = Location::find($id);
            if (!$location) {
                return response()->json([
                    'status' => false,
                    'message' => 'Location not found'
                ], 404);
            }

            $location->user_id = $user->id;
            $location->longitude = $request->longitude ?? $location->longitude;
            $location->latitude = $request->latitude ?? $location->latitude;
            $location->save();

            return response()->json([
                'status' => true,
                'message' => 'Location updated successfully',
                'data' => $location
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        if ($user->role === 'student') {
            $location = Location::find($id);
            if (!$location) {
                return response()->json([
                    'status' => false,
                    'message' => 'Location not found'
                ], 404);
            }

            $location->delete();

            return response()->json([
                'status' => true,
                'message' => 'Location deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action'
            ], 401);
        }
    }
}
