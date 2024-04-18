<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        // check if the user is a student
        if ($user->role === 'student') {
            // Get the location of the student by location method in user model
            $locations = $user->location;
        } else {
            if ($user->ownedBus) {
                // Retrieve the bus owned by the logged-in owner
                $bus = $user->ownedBus;

                // Retrieve all the students associated with the bus
                $students = $bus->students()->get();

                // Retrieve all locations associated with the students
                $locations = Location::whereIn('user_id', $students->pluck('id'))->get();

                // Return the locations
                return response()->json([
                    'status' => true,
                    'message' => 'Locations retrieved successfully',
                    'data' => $locations
                ]);
            } else {
                // If the owner doesn't have a bus, return an error message
                return response()->json([
                    'status' => false,
                    'message' => 'Owner does not have a bus associated.'
                ]);
            }
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
    public function getIsAttendingLocations()
    {
        $currentDate = Carbon::now();

        // Retrieve the day of the week (0 for Sunday, 1 for Monday, ..., 6 for Saturday)
        $dayOfWeek = Carbon::now()->addDay()->englishDayOfWeek;

        // Retrieve the logged-in owner's user instance
        $owner = Auth::user();

        // Check if the owner has a bus
        if ($owner->ownedBus) {
            // Retrieve the bus owned by the logged-in owner
            $bus = $owner->ownedBus;

            // Retrieve all the students associated with the bus
            $students = $bus->students()->get();

            // Filter the students based on their schedules for the current day of the week and their isAttending attribute
            $filteredStudents = $students->filter(function ($student) use ($dayOfWeek) {
                return $student->schedules()->where('day', $dayOfWeek)->where('is_attending', true)->exists();
            });

            // Retrieve all locations associated with the filtered students
            $locations = Location::whereIn('user_id', $filteredStudents->pluck('id'))->get();

            // Return the locations
            return response()->json([
                'status' => true,
                'message' => 'Locations retrieved successfully',
                'data' => $locations
            ]);
        } else {
            // If the owner doesn't have a bus, return an error message
            return response()->json([
                'status' => false,
                'message' => 'Owner does not have a bus associated.'
            ]);
        }
    }
}
