<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use stdClass;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Contract $contract)
    {
        $contracts = $contract->all()->map(function($value)  {
            return $value->getMapping();
        });
        return response()->json([
            'success' => true,
            'data'  => $contracts
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

        $contract = $request->only([
            'contract_rates' ,
            'start_date' ,
            'end_date',
            'accommodation_id',
            'travel_agent_id'
        ]);

        $contract['accommodation_id'] = 1;
        $contract['travel_agent_id'] = 2;


        Contract::create($contract);

        return response()->json([
            'success'=> true,
            'message' => 'Contract was captured successfully 🎉'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
       $contract->update([
        'contract_rates'  =>$request->input('contract_rates') ,
        'start_date' =>$request->input('start_date') ,
        'end_date' => $request->input('end_date'),
        'accommodation_id' => $request->input('accommodation_id'),
        'travel_agent_id' => $request->input('travel_agent_id')
       ]);

       return response()->json([
        'success'=> true,
        'message' => 'Contract was updated successfully 🎉'
       ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
         $contracts = Contract::all();
       $contract->delete();
       return response()->json([
        'sucess'=>true,
         'data' => $contracts,
        'message'=>'item deleted successlly 🎉'
       ]);
    }
}
