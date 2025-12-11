<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->hasRole('passenger')){
        $bookings=auth()->user()->bookings()->get();
        if($bookings){
           return response()->json($bookings); 
        }
        return response()->json(['message'=>'there is no flights']);
        }
        return response()->json(['message'=>'you do not have role for this action']);
    }

    public function showBookings(){
        if(auth()->user()->hasRole('admin')){
        $bookings=Booking::all();
        if($bookings){
           return response()->json($bookings); 
        }
        return response()->json(['message'=>'there is no flights']);
        }
        return response()->json(['message'=>'you do not have role for this action']);  
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,string $id)
    {
        if(auth()->user()->hasRole('passenger')){
            $request->validate([
            'seats_number'=>'required|numeric|min:1',
            'class'=>'required|string',
            ]);
            $flight=Flight::find($id);
            if(auth()->user()->is_loyal && $request->seats_number==1){
            if($request->class=='A' && $flight->class_A_seats>1){
                $flight->update(['class_A_seats'=> $flight->class_A_seats-1]);
            auth()->user()->bookings()->create([
            'flight_id'=>$flight->id,
            'seats_number'=>$request->seats_number,
            'total_price'=>0,
            'class'=>$request->class,
            ]);
            auth()->user()->update([
                'is_loyal'=>false,
                'points'=>0
            ]);
            }elseif ($request->class=='B' && $flight->class_B_seats>1) {
                $flight->update(['class_B_seats'=>$flight->class_B_seats-1]);
                            auth()->user()->bookings()->create([
            'flight_id'=>$flight->id,
            'seats_number'=>$request->seats_number,
            'total_price'=>0,
            'class'=>$request->class,
            ]);
            auth()->user()->update([
                'is_loyal'=>false,
                'points'=>0
            ]);
            }else{
                return response()->json(['message'=>'unvalid seats!']);
            }
            return response()->json(['message'=>'enjoy your free trip,your request is pending now!']);
            }
            if($request->class=='A' && $flight->class_A_seats>$request->seats_number){
                $flight->update(['class_A_seats'=>$flight->class_A_seats-$request->seats_number]);
            auth()->user()->bookings()->create([
            'flight_id'=>$flight->id,
            'seats_number'=>$request->seats_number,
            'total_price'=>$request->seats_number*$flight->price,
            'class'=>$request->class,
            ]);
            
            auth()->user()->update([
                'points'=>auth()->user()->points+$flight->points
            ]);
            if(auth()->user()->points>3000){
                auth()->user()->update([
                'is_loyal'=>true
            ]);
            return response()->json(['message'=>'your request is pending now!,and because your are loyal you can have a free trip for one persone whenever you want']);
            }
            return response()->json(['message'=>'your request is pending now!']);
            }elseif ($request->class=='B' && $flight->class_B_seats>$request->seats_number) {
                $flight->update(['class_B_seats'=>$flight->class_B_seats-$request->seats_number]);
            auth()->user()->bookings()->create([
            'flight_id'=>$flight->id,
            'seats_number'=>$request->seats_number,
            'total_price'=>$request->seats_number*$flight->price,
            'class'=>$request->class,
            ]);
            
            auth()->user()->update([
                'points'=>auth()->user()->points+$flight->points
            ]);
            if(auth()->user()->points>3000){
                auth()->user()->update([
                'is_loyal'=>true
            ]);
            return response()->json(['message'=>'your request is pending now!,and because your are loyal you can have a free trip for one persone whenever you want']);
            }
            return response()->json(['message'=>'your request is pending now!']);
            }else{
                return response()->json(['message'=>'unvalid seats!']);
            }

        }
        return response()->json(['message'=>'you do not have role for this action']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(auth()->user()->hasRole('admin') ||auth()->user()->hasRole('passenger')){
        $booking=Booking::find($id);
        if($booking){
            return response()->json($booking);
        }
        return response()->json(['message'=>'did not find this booking']);
        }
        return response()->json(['message'=>'you do not have role for this action']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(auth()->user()->hasRole('admin')){
            $request->validate([
            'status'=>'required|string'
            ]);
        $booking=Booking::find($id);
        $flight=Flight::find($booking->flight_id);
        if($booking){
            $booking->update([
            'status'=>$request->status,
            ]);
            if($request->status=='canceled'&&$booking->class=='A'){
            $flight->update(['class_A_seats'=>$flight->class_A_seats+$booking->seats_number]);
            $booking->delete();
            return response()->json(['message'=>'deleted']);
            }elseif($request->status=='canceled'&&$booking->class=='B'){
            $flight->update(['class_B_seats'=>$flight->class_B_seats-$booking->seats_number]);
            $booking->delete();
            return response()->json(['message'=>'deleted']);
            }
            return response()->json($booking);
        }
        return response()->json(['message'=>'did not find this booking']);
            
        }
        return response()->json(['message'=>'you do not have role for this action']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if(auth()->user()->hasRole('passenger')){
        $booking=Booking::find($id);
        $flight=Flight::find($booking->flight_id);
        if($booking){
            if($booking->class=='A'){
            $flight->update(['class_A_seats'=>$flight->class_A_seats+$booking->seats_number]);
            $booking->delete();
            return response()->json(['message'=>'deleted']);  
            }
            $flight->update(['class_B_seats'=>$flight->class_B_seats-$booking->seats_number]);
            $booking->delete();
            return response()->json(['message'=>'deleted']);
        }
        return response()->json(['message'=>'did not find this flight']);  
        }
        return response()->json(['message'=>'you do not have role for this action']);
    }

}








