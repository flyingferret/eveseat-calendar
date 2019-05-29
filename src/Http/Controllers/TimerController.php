<?php
namespace Seat\Kassie\Calendar\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Web\Http\Controllers\Controller;
use Carbon\Carbon;
use Seat\Kassie\Calendar\Models\Timers;

class TimerController extends Controller
{
    const LAYOUT = 'calendar::layouts.home';

    public function listAllTimersView()
    {
        $activeTimers = Timers::where('timeExiting', '>', Carbon::now())->orderBy('timeExiting', 'asc')->get();

        $oldTimers = Timers::where('timeExiting', '<=', Carbon::now())->orderBy('timeExiting', 'desc')->paginate(30);

        return view('calendar::timers.index')
            ->with(array('activeTimers' => $activeTimers))
            ->nest('timer_table_new', 'calendar::timers.includes.timer_table', array('timers' => $activeTimers, 'paginate' => false))
            ->nest('timer_table_old', 'calendar::timers.includes.timer_table', array('timers' => $oldTimers, 'paginate' => true));
    }

    public function listActiveTimersView()
    {
        $activeTimers = Timers::where('outcome', '=', '0')->orderBy('timeExiting', 'asc')->get();

        return view('calendar::timers.active')
            ->with(array('activeTimers' => $activeTimers))
            ->nest('timer_table', 'calendar::timers.includes.timer_table', array('timers' => $activeTimers, 'paginate' => false));
    }

    public function listExpiredTimersView()
    {
        $oldTimers = Timers::where('bashed', '!=', '0')->where('outcome', '!=', '0')->orderBy('timeExiting', 'desc')->paginate(30);

        return view('calendar::timers.expired')
            ->nest('timer_table', 'calendar::timers.includes.timer_table', array('timers' => $oldTimers, 'paginate' => true));
    }

    public function addTimerAction(Request $request)
    {
        $this->validate($request, [
            'itemID' => 'required',
            'structureType' => 'required',
            'timerType' => 'required',
            'days' => 'required',
            'hours' => 'required',
            'mins' => 'required',
        ]);

        $time = Carbon::now()->addDays((int)$request->days)->addHours((int)$request->hours)->addMinutes((int)$request->mins);

        return Timers::create(array(
            'itemID' => $request->itemID,
            'structureType' => (int)$request->structureType,
            'structureStatus' => (int)$request->structureStatus,
            'timeExiting' => date('Y-m-d H:i:s', $time->timestamp),
            'bashed' => '0',
            'timerType' => $request->timerType,
            'outcome' => '0',
            'type' => 0,
            'user_id' => auth()->user()->id,
        ));
    }

    public function bashTimerAction($id)
    {
        $timer = Timers::find($id);
        if($timer != false and $timer->bashed !== '1')
        {
            $timer->bashed = 1;
            $timer->save();

            return Redirect::route('home')
                ->with('flash_msg', 'Timer Marked as Bashed');
        }
        else
        {
            return Redirect::route('home')
                ->with('flash_error', 'Timer Not Found or Already Bashed');
        }
    }

    public function winTimerAction($id)
    {
        $timer = Timers::find($id);
        if($timer != false and $timer->outcome === '0')
        {
            $timer->bashed = 1;
            $timer->outcome = 1;
            $timer->save();

            return Redirect::route('home')
                ->with('flash_msg', 'Timer Marked as WIN');
        }
        else
        {
            return Redirect::route('home')
                ->with('flash_error', 'Timer Not Found or Already Marked');
        }
    }

    public function failTimerAction($id)
    {
        $timer = Timers::find($id);
        if($timer != false and $timer->outcome === '0')
        {
            $timer->bashed = 1;
            $timer->outcome = 2;
            $timer->save();

            return Redirect::route('home')
                ->with('flash_msg', 'Timer Marked as FAIL');
        }
        else
        {
            return Redirect::route('home')
                ->with('flash_error', 'Timer Not Found or Already Marked');
        }
    }

    public function deleteTimerAction($id)
    {
        $timer = Timers::find($id);
        if($timer != false and $timer->bashed !== '1')
        {
            $timer->delete();
            return Redirect::route('home')
                ->with('flash_msg', 'Timer Deleted');
        }
        else
        {
            return Redirect::route('home')
                ->with('flash_error', 'Timer Not Found or Already Marked');
        }
    }

    public function searchCelestialAction(Request $request)
    {
        $mapItems = MapDenormalize::where('itemID', '=', $request->term)
            ->orWhere('itemName', 'like', $request->term . '%')
            ->whereIn('groupID', array('5', '7', '8'))
            ->where('security', '<', '0.8')
            ->get();

        $results = array();

        foreach ($mapItems as $mapItem) {
            array_push($results, array(
                "id" => $mapItem->itemID,
                "text" => $mapItem->itemName
            ));
        }

        return response()->json(array('results' => $results, 'pagination' => false));
    }
}