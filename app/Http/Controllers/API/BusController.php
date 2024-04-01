<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BusRequest;
use Illuminate\Support\Str;
use App\Models\Bus;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ownerId = auth()->user()->id;
        $buses = Bus::where('owner_id', $ownerId)->get();
        return response()->json([
            'status' => true,
            'message' => 'Buses retrieved successfully',
            'data' => $buses
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BusRequest $request)
    {
        $name = $request->name;
        $description = $request->description;
        $code = Str::random(6);
        $alreadyExists = Bus::where('code', $code)->first();
        if (!is_null($alreadyExists)) {
            $code = Str::random(6);
        }
        $bus = new Bus();
        $bus->name = $name;
        $bus->description = $description;
        $bus->code = $code;
        $bus->owner_id = auth()->user()->id;
        $bus->save();

        return response()->json([
            'status' => true,
            'message' => 'Bus created successfully',
            'data' => $bus
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userId = auth()->user()->id;
        $bus = Bus::find($id);
        if (is_null($bus)) {
            return response()->json([
                'status' => false,
                'message' => 'Bus not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Bus retrieved successfully',
            'data' => $bus
        ]);
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
    public function update(BusRequest $request, string $id)
    {
        $bus = Bus::find($id);
        if (is_null($bus)) {
            return response()->json([
                'status' => false,
                'message' => 'Bus not found',
            ]);
        }
        // Check if the user is the owner of the bus
        if ($bus->owner_id !== auth()->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not allowed to perform this action',
            ]);
        }
        $bus = $bus->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Bus updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bus = Bus::find($id);
        if (is_null($bus)) {
            return response()->json([
                'status' => false,
                'message' => 'Bus not found',
            ]);
        }
        // Check if the user is the owner of the bus
        if ($bus->owner_id !== auth()->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'You are not allowed to perform this action',
            ]);
        }

        $bus->delete();
        return response()->json([
            'status' => true,
            'message' => 'Bus deleted successfully',
        ]);
    }
}
