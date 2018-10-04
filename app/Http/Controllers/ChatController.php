<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    //
    public function create (Request $request)
    {
	    if ($request->has('roomName'))
    	{
      	$data = $request->validate(['roomName' => 'required|max:127|unique:chatRoom,name']);
         $name = $data['roomName'];
      	$hash = md5( $name . time());
      	DB::table('chatroom')->insert(['name' => $name, 'hash' => $hash]);
       return redirect('/chat/' . $hash);
	    }
    }
    
    public function join ($room)
    {
      $roomData = DB::table('chatroom')->where('hash', '=', $room)->first();
      
      if (session()->has('user'))
      {
        $user = session()->get('user');
        $colour = session()->get('color');
      } else
      {
        $names = array("Darth Vader", "Captian Kirk", "Captian Picard", "Scotty", "Bones", "Luke", "Ray");
        $user = $names[array_rand($names)];
        session()->put('user', '' . $user);
        $colours = array("Blue", "Cyan", "Red", "DarkCyan", "DarkViolet", "BlueViolet", "Fuchsia", "RebeccaPurple", "Purple" );
      $colour = $colours[array_rand($colours)];
      session()->put('color', '' . $colour);
      }
      
      
      
      
      
      $name = $roomData->name;
      $hash = $room;
      
      
      return view('chat', ['name' => $name, 'hash' => $hash, 'user' => $user, 'color' => $colour]);
    }
    
    public function send (Request $request)
    {
      $user = htmlspecialchars($request->input('user'));
      $color = htmlspecialchars($request->input('color'));
      $message = htmlspecialchars($request->input('message'));
      $hash = htmlspecialchars($request->input('hash'));
      
      $html = '<p><span style="color: ' . $color . ';">' . $user . '</span>: ' . $message . '</p>';
      DB::table('chat')->insert(['room' => $hash, 'message' => $html, 'timestamp' => time()]);
    }
    
    public function getMessages ($room, $id)
    {
       $msgs = DB::table('chat')->where('room', '=', $room)->where('id', '>', $id)->orderBy('id', 'asc')->get();
       $array = array();
       
       foreach ($msgs as $msg)
       {
         $array[$msg->id] = $msg->message;
       }  
       $jsonArray = json_encode($array, JSON_UNESCAPED_SLASHES);
       
       return $jsonArray;
    }
    
    public function setUser (Request $request)
    {
      $user_data = $request->validate(['user'=> 'required|max:127|alphanum','color'=> 'required|max:127|alphanum']);
      
      $user = $user_data['user'];
      $color = $user_data['color'];
      session()->forget('user');
      session()->forget('color');
      session()->put('user', '' . $user);
      session()->put('color', '' . $color);
      //dd(session()->all());
      return session('user');
    }
}
