<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Room;
use App\Models\User;
use App\Models\Category;
use App\Models\Activity;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Room::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $room=Room::findOrFail($id);
        $room->location;
        return $room;
    }
    // Get list of rooms for a location
    public function roomList($id)
    {
        
        $room=Room::where('location_id',$id)->get();;
        return $room;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $room =Room::findOrFail($id);
        $activity = new Activity();
        $activity->Location_id = $room->location_id;
        $activity->Performed_by = $request->user_id;
        $activity->Type =$request->type; //edit
        $activity->Change = $request->activity_performed; //change will be changed to activity performed later on
        if($activity->save()){
            $room->Room_num = $request->room_num;
            $room->Desc = $request->desc;
            $room->Floor = $request->floor;
            $room->room_name = $request->room_name;
            $room->category_id = $request->category_id;
            $room->location_id = $request->location_id;

            
            if(!$room->save()){
                $activity->delete();
                return ['status'=>'failed'];
            }
          return ['status'=>'success'];
        }else{
            return ['status'=>'failed'];
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $activity = new Activity();
        $activity->Location_id = $room->location_id;
        $activity->Performed_by = $request->user_id;
        $activity->Type =$request->type; //Delete
        $activity->Change = $request->activity_performed; //change will be changed to activity performed later on
        $a=$activity->save();
        if($a){

            
            if(!$room->delete()){
                $activity->delete();
                return ['status'=>'failed'];
            }
            echo Room::all();
          return ['status'=>'success'];
        }else{
            return ['status'=>'failed'];
        }
    
    }
}
