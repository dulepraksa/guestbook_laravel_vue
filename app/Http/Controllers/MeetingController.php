<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Meeting as MeetingCollection;
use App\User;
use \DateInterval;
use \DatePeriod;
use App\Meeting;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function index() 
    {
        $meetings = Meeting::with(['room','users.type'])->get();
        return response()->json($meetings);
    }
    public function show($id) 
    {
        $meeting = Meeting::findOrFail($id);
        return $meeting;
    }
    public function thisWeek()
    {
        $meetings = Meeting::whereBetween('start_time', $this->weekInterval())->with(['users.type','room'])->paginate(4);
        return new MeetingCollection($meetings);
    }
    public function today()
    {
        $meetings = Meeting::whereBetween('start_time', $this->todayInterval())->with(['users.type','room'])->paginate(4);
        return new MeetingCollection($meetings);
    }
    public function thisMonth()
    {
        $meetings = Meeting::whereBetween('start_time', $this->monthInterval())->with(['users.type','room'])->paginate(4);
        return new MeetingCollection($meetings);
    }
    public function countMeetings()
    {
        $meetings = Meeting::all()->count();
        return response()->json($meetings);
    }
    public function userMeetings($userId)
    {
        $meetings = Meeting::select('meetings.*')->leftJoin('meeting_user as mu','meeting_id','=','mu.meeting_id')
                            ->where('mu.user_id','=',$userId)->paginate(5);

        return new MeetingCollection($meetings);
    }
    public function countUserMeetings($userId)
    {
        $meetings = Meeting::select('meetings.*')->leftJoin('meeting_user as mu','meeting_id','=','mu.meeting_id')
                            ->where('mu.user_id','=',$userId)->count();

        return response()->json($meetings);
    }

    public function checkIn(Request $request,$id, $userId)
    {

        $meeting = DB::table('meeting_user')->where('user_id','=',$userId)->where('meeting_id','=',$id)->update([
            'checked_in' => 1
        ]);

        return response()->json($meeting);
    }
    public function store(Request $request)
    {

        $this->validate($request,[
            'name' => 'required',
            'description' => 'required',
            'start_time' =>'required|date',
            'room_id' => 'required'  
        ]);

        $start_time = $this->requestDateFormat($request->start_time);
        
        
        $meeting = new Meeting([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'start_time' => $start_time,
            'room_id' => $request->input('room_id')
        ]);

        $meeting->save();
        
        return response()->json([
            'data' => $meeting ,
            'message'=>'Meeting created',
            'status' => 200
        ]);
    }
    public function delete(Meeting $meeting)
    {
        $meeting->delete();

        return response()->json('Meeting deleted sucessfully');
    }
    public function update(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'start_time' => 'required',
            'room_id' => 'required'
        ]);

        $start_time = $this->requestDateFormat($request->start_time);
        
        $meeting->update([
            'name' => $request->input('name'),
            'room_id' => $request->input('room_id'),
            'description' => $request->input('description'),
            'start_time' => $start_time
        ]);
        
        $meeting->users()->attach($request->participants);
        
        $meeting2 = Meeting::with('users.type')->findOrFail($meeting->id);
        
        return response()->json([
            'meeting' => $meeting2
        ]);
    }
    public function meetingChart() {
        $days = Meeting::whereBetween('start_time', $this->weekInterval())->pluck('start_time');

        $meetings = [];

        foreach($days as $day) {
            $format_day = strtotime($day);
            $meetings[] = date('Y-m-d', $format_day);
        }
        $week[] = $this->weekInterval();
        $counter_interval = DateInterval::createFromDateString('1 day');
        $start_day = $week[0]['first_day'];
        $end_day = $week[0]['end_day'];
        $period = new DatePeriod($start_day, $counter_interval, $end_day);

        foreach ($period as $dt) {
            $date[] =  $dt->format("Y-m-d");
        }

        $meetingCounter = array_count_values($meetings);
        $dateSorted = [];
        foreach($date as $key => $day) {
            $dateSorted[$day] = 0;
            if(isset($meetingCounter[$day])){
                $dateSorted[$day] = $meetingCounter[$day];
            }            
        }
        
        $number_per_day_array = array_keys($dateSorted);
        $days_of_week = array_values($dateSorted);

        return response()->json([
            'dates' => $days_of_week,
            'numbers' => $number_per_day_array
        ]); 
    }
    public function detachParticipant(Request $request, $id) {
        $meeting = Meeting::findOrFail($id);
        
        $meeting->users()->detach($request->participants);


        return response()->json([
            'message' => 'Participant removed'
        ]);
    }
    public function attachParticipants(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);

        $meeting->users()->attach($request->participants);

        return response()->json($meeting);

    }
    public function returnMe($id) {
        $meeting = Meeting::findOrFail($id);

        $meeting->with('users.type')->get();
        // $meeting->type()->all();

        return response()->json([
            'meeting' => $meeting
        ]);
    }
}
