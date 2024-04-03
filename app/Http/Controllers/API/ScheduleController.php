<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Http\Requests\ScheduleRequest;
use App\Models\User;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For the bus owner return all schedules assigned to the bus
        if (auth()->user()->role === 'owner') {
            $busId = auth()->user()->bus->id;
            $schedules = Schedule::where('bus_id', $busId)->get();
            if ($schedules->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No schedules found',
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Schedules retrieved successfully',
                'data' => $schedules
            ]);
        }
        // For the student return all schedules assigned to the student
        if (auth()->user()->role === 'student') {
            $userId = auth()->user()->id;
            $user = User::find($userId);
            $schedules = $user->schedules;
            if ($schedules->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No schedules found',
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Schedules retrieved successfully',
                'data' => $schedules
            ]);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request)
    {

        $day = $request->day;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $isAttending = $request->is_attending;
        $id = auth()->user()->id;
        $user = User::find($id);

        $schedule = $user->schedules()->create([
            'day' => $day,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_attending' => $isAttending
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Schedule created successfully',
            'data' => $schedule
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::find($id);
        if (is_null($schedule)) {
            return response()->json([
                'status' => false,
                'message' => 'Schedule not found',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Schedule retrieved successfully',
            'data' => $schedule
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, string $id)
    {
        $schedule = Schedule::find($id);
        if (is_null($schedule)) {
            return response()->json([
                'status' => false,
                'message' => 'Schedule not found',
            ]);
        }
        $schedule->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Schedule updated successfully',
            'data' => $schedule
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userId = auth()->user()->id;
        $user = User::find($userId);
        $schedule = $user->schedules()->find($id);
        if (is_null($schedule)) {
            return response()->json([
                'status' => false,
                'message' => 'Schedule not found',
            ]);
        }
        $schedule->delete();
        return response()->json([
            'status' => true,
            'message' => 'Schedule deleted successfully',
        ]);
    }

    // Function to get the schedule of a student for the next day
    public function getNextSchedule()
    {
        $studentId = auth()->user()->id;
        $user = User::find($studentId);
        $schedules = $user->schedules()
            // date('1') returns the day of the week
            // strtotime('+1 day') returns the next day
            ->where('day', date('l', strtotime('+1 day')))
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Schedules retrieved successfully',
            'data' => $schedules
        ]);
    }
}
