<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Accommodation $accommodation)
    {
        $accommodations = $accommodation->all();
        return response()->json([
        'success' => true,
        'data' => $accommodations
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
        $accommodation = $request->only([
            'name' ,
            'description' ,
            'standard_rack_rate'
        ]);

        Accommodation::create($accommodation);

        return response()->json([
            'success'=> true,
            'message' => 'Accommodation was captured successfully 🎉'
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Accommodation $accommodation)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accommodation $accommodation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
      // Update the accommodation with the request data
      $accommodation->update([
        'name' => $request->input('name'),
        'description' => $request->input('description'),
        'standard_rack_rate' => $request->input('standard_rack_rate'),
      ]);

      // Return a response indicating success
      return response()->json([
        'success' => true,
        'message' => 'Accommodation updated successfully 🎉'], 200);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {

        $accommodation->delete();

        // Return a response indicating success
        return response()->json([
        'success' => true,
        'message' => 'Accommodation deleted successfully 🎉'], 200);

    }

}
