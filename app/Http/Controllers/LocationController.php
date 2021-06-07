<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Room;
use App\Models\User;
use App\Models\Category;
use App\Models\Activity;
use App\Models\RoomActivity;
use App\Models\CategoryActivity;
use App\Models\DeletedEntity;
use Illuminate\Support\Facades\Validator;


class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations =Location::where("category_id",null)->get();
        


        // foreach($categories as $cate){
            
        //     foreach($cate->location as $c){
        //         foreach($c->room as $r){
        //             $r->category;
        //         }
        //     }
        // }
        

        return $locations;
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
        $validate =$request->validate([
            'lname' => 'required|max:255',
            'latitude'  => 'required|max:255',
            'longitude'  => 'required|max:255',
            'type'  => 'required|max:255',
            'activity_performed'  => 'required|max:255',
            'rooms' => 'array',
            Validator::make($request->rooms,['room_num'=>'unique']),
           
        ]);
        if($validate->fails()){
            return $validate->errors();
        }else{
            $arr =array(); //array of failed to add rooms
        $location = new Location();
        $location->Name=$request->input('lname');
        $location->Latitude=$request->input('latitude');
        $location->Longitude=$request->input('longitude');
        $location->category_id=$request->input('category_id');
        $loc_saved = $location->save();
        $activity = new Activity();
        $activity->id = $location->Location_id;
        $activity->Type =$request->input('type'); //Create
        $activity->Change = $request->input('activity_performed'); //change will be changed to activity performed later on
        
        $act_saved = $activity->save();
        if($loc_saved && $act_saved ){
        
            foreach($request->input('rooms') as $r){
                
                $room = new Room();
                $room->Room_no =$r['room_num'];
                $room->Desc =$r['desc'];
                $room->Floor =$r['floor'];
                $room->room_name =$r['room_name'];
                $room->category_id=$r['rcategory'];
                $room->location_id =$location->Location_id;
                $room_saved = $room->save();
                if(!$room_saved){
                    array_push($arr,$r['room_num']);
                }
                $ractivity = new RoomActivity();
                $ractivity->id = $r['room_num'];
        
        $ractivity->Type =$request->input('type'); //Create
        $ractivity->Change = "Added Room".$r['room_num'];//change will be changed to activity performed later on
        $ractivity->save();
                
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
        $request->validate([
            'lname' => 'required|max:255',
            'latitude'  => 'required|max:255',
            'longitude'  => 'required|max:255',
            'type'  => 'required|max:255',
            'rooms' => 'array',
            Validator::make($request->rooms,['room_num'=>'unique']),
           
        ]);
        if($validate->fails()){
            return $validate->errors();
        }
        else{
            $obj = $request;
            $arr =array(); //array of failed to add rooms
            $location = Location::findOrFail($id);
            $location->Name=$obj['lname'];
            $location->Latitude=$obj['latitude'];
            $location->Longitude=$obj['longitude'];
            $location->category_id=$obj['category_id'];
            $loc_saved= $location->save();
            $activity = new Activity();
            $activity->id = $location->Location_id;
            $activity->Type =$request->type; //Edit
            $activity->Change = "updated ".$location->Location_id."to $location->Name: ".$obj['lname'].", $location->Latitude: ".$obj['latitude'].", $location->Longitude: ".$obj['longitude'].", $location->category_id: ".$obj['category_id'];//change will be changed to activity performed later on
            $act_saved = $activity->save();
            
            
            
            if($loc_saved && $act_saved){
                foreach($obj['rooms'] as $r){
                    $room = new Room();
                    $room->Room_no =$r['room_num'];
                    $room->Desc =$r['desc'];
                    $room->Floor =$r['floor'];
                    $room->room_name =$r['room_name'];
                    $room->category_id=$r['rcategory'];
                    $room->location_id =$location->Location_id;
                    $room_saved = $room->save();
                    if(!$room_saved){
                        array_push($arr,$r['room_num']);
                    }
                    $ractivity = new RoomActivity();
                    $ractivity->id = $r['room_num'];
            
            $ractivity->Type ="CREATE";
            $ractivity->Change = "Added Room".$r['room_num'];//change will be changed to activity performed later on
            $ractivity->save();
                    
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
          
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'deleted' => 'required|max:255',
        ]);
        if($validate->fails()){
            return $validate->errors();
        }else{
            $loc = Location::findOrFail($id);
            $loc->delete();
        
            $activity = new DeletedEntity();
            //$activity->Category_id = $cate->id;
            // $activity->Performed_by = $request->user_id;
            $activity->Deleted_entity=$request->input('deleted'); //Edit
            $act_saved=$activity->save();
        
            return ['status'=>'success'];
       
        }
    }
}
