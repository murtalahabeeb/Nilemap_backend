<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Room;
use App\Models\User;
use App\Models\Category;
use App\Models\Activity;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$locations =Location::all();
        $categories =Category::all();
        // foreach($locations as $location){
        //     $location->category;
        //     //$location->room;
        //     foreach($location->room as $r){
        //         $r->category;
        //     }
        // }


        // foreach($categories as $cate){
            
        //     foreach($cate->location as $c){
        //         foreach($c->room as $r){
        //             $r->category;
        //         }
        //     }
        // }
        

        foreach($categories as $category){
            foreach($category->room as $room){
                $room->location;
            }

            $category->location;
        }
        return $categories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  

        $obj = $request;
        $arr =array(); //array of failed to add rooms
        $location = new Location();
        $location->Name=$obj['lname'];
        $location->Latitude=$obj['latitude'];
        $location->Longitude=$obj['longitude'];
        $location->category_id=$obj['category_id'];

        $activity = new Activity();
        $activity->Location_id = $location->Location_id;
        $activity->Performed_by = $request->user_id;
        $activity->Type =$request->type; //Create
        $activity->Change = $request->activity_performed; //change will be changed to activity performed later on
        $loc_saved = $location->save();
        $act_saved = $activity->save();
        if($loc_saved && $act_saved ){
        
            foreach($obj['rooms'] as $r){
                $room = new Room();
                $room->Room_num =$r['room_num'];
                $room->Desc =$r['desc'];
                $room->Floor =$r['floor'];
                $room->room_name =r['room_name'];
                $room->category_id=$r['rcategory_id'];
                $room->location_id =$location->Location_id;
                $room_saved = $room->save()
                if(!$room_saved){
                    array_push($arr,$r['room_num']);
                }
            }

                return ['status'=>'success','room'=>$arr];
        }elseif($loc_saved && !$act_saved){
            $location->delete();
            return ['status'=>'failed'];
        }elseif(!$loc_saved && $act_saved){
            $activity->delete();
            return ['status'=>'failed'];
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    
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
        $obj = $request;
        $location = Location::findOrFail($id);
        $location->Name=$obj['lname'];
        $location->Latitude=$obj['latitude'];
        $location->Longitude=$obj['longitude'];
        $location->category_id=$obj['category_id'];
        $location->save();
        
        foreach($obj['rooms'] as $r){
            $room = new Room();
            $room->Room_num =$r['room_num'];
            $room->Desc =$r['desc'];
            $room->Floor =$r['floor'];
            $room->room_name =$r['room_name'];
            $room->category_id=$r['rcategory_id'];
            $room->location_id =$location->Location_id;
            $room->save();
        }
        $activity = new Activity();
        $activity->Location_id = $location->Location_id;
        $activity->Performed_by = $request->user_id;
        $activity->Type =$request->type; //Edit
        $activity->Change = $request->activity_performed; //change will be changed to activity performed later on
        if($activity->save()){
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
        
        $loc = Location::findOrFail($id);
            
    
        $activity = new Activity();
        $activity->Location_id = $id;
        $activity->Performed_by = $request->user_id;
        $activity->Type =$request->type; //Delete
        $activity->Change = $request->activity_performed; //change will be changed to activity performed later on
        if($activity->save()){

            
            if(!$loc->delete()){
                $activity->delete();
                return ['status'=>'failed'];
            }
          return ['status'=>'success'];
        }else{
            return ['status'=>'failed'];
        }

    }
}
