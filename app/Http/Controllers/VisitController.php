<?php

namespace App\Http\Controllers;

use App\Visit;
use App\user;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use App\Http\Resources\Visit as VisitCollection;
use \DateTime;
use \DatePeriod;
use \DateInterval;
use \DateTimeZone;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAll() {
        Visit::truncate();
    }
    public function userWithVisits($id)
    {
        $visit = Visit::with('user')->findOrFail($id);
        return response()->json($visit);
    }
    public function index()
    {
        $visit = Visit::with('user.type')->where('user.type.name','=','Guest')->get();
        return response()->json($visit);
    }
    public function active()
    {
        $visits = Visit::with('user.type')->where([['left_at','=',null],['planned','=', false]])->paginate(4);
        return new VisitCollection($visits);
    }
    public function filterByDate(Request $request, $date_interval = null)
    {
        $visits = Visit::with('user.type')->whereBetween([['arrived_at', $date_interval],['planned','=', false]])->get();
        return response()->json($visits);
    }
    public function planned()
    {
        $visits = Visit::with('user.type')->where('planned','=', true)->paginate(4);
        return new VisitCollection($visits);
    }

    public function latest() 
    {
        $visit = Visit::all()->sortBy('created_at')->latest();
        return response()->json($visit);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->image != '') {
            $photo = $this->takePhoto($request->image);
        } else {
            $photo = null;
        }
        
        $this->validate($request, [
            'planned' => 'required',
            'arrived_at' => 'required_if:planned,false',
            'planned_time' => 'required_if:planned,true',
            'user_id' => 'required'
        ]);

        $arrived_at = $this->requestDateFormat($request->arrived_at);
        $left_at = $this->requestDateFormat($request->left_at);
        $planned_time = $this->requestDateFormat($request->planned_time);
        
        $visit = new Visit([
            'arrived_at' => $arrived_at,
            'left_at' => $left_at,
            'planned_time' => $planned_time,
            'image' => $photo,
            'planned' => $request->input('planned'),
            'user_id' => $request->input('user_id')
        ]);
        
        $visit->save();
        $visit->user->all();

        return response()->json([
            'data' => $visit,
            'message' => 'Visit created'
        ], 201);
    }
    public function checkin(Request $request)
    {
        if ($request->image != '') {
            $photo = $this->takePhoto($request->image);
        } else {
            $photo = null;
        }
        
        $this->validate($request, [
            'planned' => 'required',
            'user_id' => 'required'
        ]);

        $tz_object = new DateTimeZone('Europe/Belgrade');
        
        $arrived_at = new DateTime('Europe/Belgrade');

        $arrived_at_new = $arrived_at->format('Y-m-d H:i:s');
        
        $visit = new Visit([
            'arrived_at' => $arrived_at_new,
            'planned' => $request->input('planned'),
            'user_id' => $request->input('user_id')
        ]);
        
        $visit->save();
        $visit->user->all();

        return response()->json([
            'data' => $visit,
            'message' => 'Visit created'
        ], 201);
    }
    public function countVisits()
    {
        $visits = Visit::all()->count();
        return response()->json($visits);
    }
    public function show(Visit $visit)
    {
        return response()->json($visit);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function filterByYesterday()
    {
        $visits = Visit::with('user')->whereBetween('arrived_at',$this->yesterdayInterval())->paginate(4);
        return new VisitCollection($visits);
    }
    public function filterByToday(Request $request)
    {
        $visits = Visit::with('user')->whereBetween('arrived_at', $this->todayInterval())->paginate(4);
        return new VisitCollection($visits);
    }
    public function filterByThisWeek()
    {
        $visits = Visit::with('user')->whereBetween('arrived_at', $this->weekInterval())->paginate(4);
        return new VisitCollection($visits);
    }
    public function notPlanned($userId) 
    {
        $visits = Visit::where([['planned','=', false],['user_id','=', $userId]])->paginate(5);
        return new VisitCollection($visits);
    }
    public function plannedForUser($userId)
    {
        $visits = Visit::where([['user_id','=', $userId],['planned','=', true]])->paginate(5);
        return new VisitCollection($visits);
    }
    public function filter(Request $request)
    {
        $visits = [];
        
        $filter = $request->all();

        if(isset($filter['filter']) && $filter['filter'] == 'yesterday') {
            $visits = Visit::with('user.type')->whereBetween('arrived_at', $this->yesterdayInterval())->paginate(4);
        }
        else if(isset($filter['filter']) && $filter['filter'] == 'today') {
            $visits = Visit::with('user.type')->whereBetween('arrived_at', $this->todayInterval())->paginate(4);
        }
        else if(isset($filter['filter']) && $filter['filter'] == 'this_week') {
            $visits = Visit::with('user.type')->whereBetween('arrived_at', $this->weekInterval())->paginate(4);
        }
        else if(isset($filter['filter'])&& $filter['filter'] == 'active') {
            $visits = Visit::with('user.type')->where([['left_at','=',null],['planned','=', false]])->paginate(4);
        }
        else if (isset($filter['filter'])&& $filter['filter'] == 'planned') {
            $visits = Visit::with('user.type')->where('planned','=', true)->paginate(4);
        }
        else {
            $visits = Visit::with('user.type')->paginate(4);
        }
        return new VisitCollection($visits);
    }
    public function visitChart() 
    {
        $tz_object = new DateTimeZone('Europe/Belgrade');

        $days = Visit::whereBetween('arrived_at', $this->weekInterval())->pluck('arrived_at');

        $visits = [];
        foreach($days as $day) {
            $format_day = strtotime($day);
            $visits[] = date('Y-m-d', $format_day);
        }
        $week[] = $this->weekInterval();
        $counter_interval = DateInterval::createFromDateString('1 day');
        $start_day = $week[0]['first_day'];
        $end_day = $week[0]['end_day'];
        $period = new DatePeriod($start_day, $counter_interval, $end_day);

        foreach ($period as $dt) {
            $date[] =  $dt->format("Y-m-d");
        }

        $visitCounter = array_count_values($visits);
        $dateSorted = [];
        foreach($date as $key => $day) {
            $dateSorted[$day] = 0;
            if(isset($visitCounter[$day])){
                $dateSorted[$day] = $visitCounter[$day];
            }            
        }
        
        $number_per_day_array = array_keys($dateSorted);
        $days_of_week = array_values($dateSorted);

        return response()->json([
            'dates' => $days_of_week,
            'numbers' => $number_per_day_array
        ]); 
    }

    public function closeVisit(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);
        
        
        $tz_object = new DateTimeZone('Europe/Belgrade');
        
        $left_at = new DateTime('Europe/Belgrade');

        $left_at_new = $left_at->format('Y-m-d H:i:s');

        $visit->update([
            'left_at' => $left_at_new
        ]);
    }
    public function update(Request $request, $id)
    {
        $visit = Visit::findOrFail($id);

        $this->validate($request,[
            'user_id' => 'required',
            'arrived_at' => 'required',
            'planned' => 'required',
            'description' => 'required'
        ]);
        
        $arrived_at = $this->requestDateFormat($request->arrived_at);
        $left_at = $this->requestDateFormat($request->left_at);
        $planned_time = $this->requestDateFormat($request->planned_time);

        $visit->update([
            'user_id' => $request->input('user_id'),
            'arrived_at' => $arrived_at,
            'left_at' => $left_at,
            'planned_time' => $planned_time,
            'planned' => $request->input('planned'),
            'description' => $request->input('description')
        ]);
        $visit->user->all();

        return response()->json([
            'data' => $visit
        ]);
    }
    public function makeNormalVisit($id, Request $request) 
    {

        $visit = Visit::findOrFail($id);
        $date = new DateTime("now", new DateTimeZone('Europe/Belgrade') );
        $date->format('Y-m-d H:i:s');

        $visit->update([
            'arrived_at'=> $date,
            'description' => 'Normal visit',
            'left_at' => '',
            'planned' => false,
            'planned_time' => ''   
        ]);

        return response()->json($visit);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visit $visit)
    {
        $visit->delete();
        return response()->json([
            'message' => 'Visit deleted',
            'status' => '204'
        ]);
    }
}
