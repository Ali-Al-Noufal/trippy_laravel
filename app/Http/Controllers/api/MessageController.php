<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
   public function index(){
    if(auth()->user()->hasRole('admin')){
    $messages=Message::aLL();
    return response()->json($messages);
            }
        return response()->json(['message'=>'you do not have role for this action']);
   }




   public function show(String $id){
    if(auth()->user()->hasRole('admin')){
    $message=Message::find($id);
    if($message){
       return response()->json($message); 
    }
    return response()->json(["message"=>"could't find this message!"]);
            }
        return response()->json(['message'=>'you do not have role for this action']);
   }




   public function store(Request $request){
    $request->validate([
        'name'=>'string|required',
        'email'=>'email|required',
        'subject'=>'string|required',
        'message'=>'string|required',
    ]);
    Message::create([
        'name'=>$request->name,
        'email'=>$request->email,
        'subject'=>$request->subject,
        'message'=>$request->message,
    ]);
    return response()->json(["message"=>"your message has been send successfuly!"]);
   }
}
