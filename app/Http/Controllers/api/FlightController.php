<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasRole('admin') ||auth()->user()->hasRole('passenger')){
        $flight=Flight::all();
        if($flight){
           return response()->json($flight); 
        }
        return response()->json(['message'=>'there is no flights']);
        }
        return response()->json(['message'=>'you do not have role for this action']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()->hasRole('admin')){
        $request->validate([
            'city'=>'required|string|max:50',
            'country'=>'required|string|max:50',
            'price'=>'required|numeric|min:0',
            'points'=>'required|numeric|max:3000',
            'departure_time'=>'required',
            'arrival_time'=>'required',
        ]);
        $flight=Flight::create([
            'city'=>$request->city,
            'country'=>$request->country,
            'price'=>$request->price,
            'points'=>$request->points,
            'departure_time'=>$request->departure_time,
            'arrival_time'=>$request->arrival_time
        ]);
        return response()->json(['message'=>'flight added successfuly',"flight"=>$flight]);
        }
        return response()->json(['message'=>'you do not have role for this action']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(auth()->user()->hasRole('admin') ||auth()->user()->hasRole('passenger')){
        $flight=Flight::find($id);
        if($flight){
            return response()->json($flight);
        }
        return response()->json(['message'=>'did not find this flight']);
        }
        return response()->json(['message'=>'you do not have role for this action']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        if(auth()->user()->hasRole('admin')||auth()->user()->hasRole('passenger')){
        $request->validate([
            'city'=>'required|string|max:50',
            'country'=>'required|string|max:50',
            'price'=>'required|numeric|min:0',
            'points'=>'required|numeric|max:3000',
            'departure_time'=>'required',
            'arrival_time'=>'required',
        ]);
        $flight=Flight::find($id);
        if($flight){
            $flight->update([
            'city'=>$request->city,
            'country'=>$request->country,
            'price'=>$request->price,
            'points'=>$request->points,
            'departure_time'=>$request->departure_time,
            'arrival_time'=>$request->arrival_time
            ]);
            return response()->json($flight);
        }
        return response()->json(['message'=>'did not find this flight']);
                }
        return response()->json(['message'=>'you do not have role for this action']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(auth()->user()->hasRole('admin')){
        $flight=Flight::find($id);
        if($flight){
            $flight->delete();
            return response()->json(['message'=>'deleted']);
        }
        return response()->json(['message'=>'did not find this flight']);
        }
        return response()->json(['message'=>'you do not have role for this action']);
    }
}
