<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Booking $booking)
    {
        $contracts = $booking->where('travel_agent_id', auth()->user()->id)->get()->map(function($value) {
            return $value->getMapping();
        });
        return response()->json([
            'success' => true,
            'data' => $contracts
        ]);
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
       
         

         $booking = $request->only([
            'contract_id',
            'start_date',
            'end_date',
            'travel_agent_id'
         ]);

         $booking['travel_agent_id'] = auth()->user()->id;


         Booking::create($booking);

         return response()->json([
         'success'=> true,
         'message' => 'Booking was captured successfully 🎉'
         ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $booking->update([
            'contract_id' => $request->input('contract_id'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);

        return response()->json([
        'success'=> true,
        'message' => 'Booking was updated successfully 🎉'
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        //
    }
}
