<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Event;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function myEvents(Request $request)
    {
        // return all signed in users events
        return response()->json(['events' => $request->user()->events]);
    }

    public function allEvents(Request $request)
    {
        // return all events
        $events = Event::all();
        return response()->json(['events' => $events]);
    }

    public function add(Request $request)
    {
        $newEvent = new Event;
        $newEvent->user_id = $request->user()->id;
        $newEvent->event_name = $request->event_name;
        $newEvent->event_date = $request->event_date;
        $newEvent->venue = $request->venue;
        $newEvent->start_time = $request->start_time;
        $newEvent->description = $request->description;

        if ($newEvent->save()) {
            return response()->json(['success' => 'Event saved']);
        } else {
            return response()->json(['error' => 'Error saving event'], 401);
        }
    }

    public function delete($id)
    {
        $delete = Event::destroy($id);

        if ($delete) {
            return response()->json(['success' => 'Event deleted']);
        }
        
        return response()->json(['error' => 'Failed to delete event']);
    }

    public function update(Request $request)
    {
        $updatedEvent = Event::where('id', $request->id)
                        ->update([
                            'event_name' => $request->event_name,
                            'event_date' => $request->event_date,
                            'venue' => $request->venue,
                            'start_time' => $request->start_time,
                            'description' => $request->description
                        ]);

        if ($updatedEvent) {
            return response()->json(['events' => $request->user()->events]);
        } else {
            return response()->json(['error' => 'Error updating event'], 401);
        }
    }

    public function book(Request $request)
    {
        $newBooking = DB::table('event_booking')
            ->insert([
                'event_id' => $request->id,
                'user_id' => $request->user()->id
            ]);

        if ($newBooking) {
            return response()->json(['success' => 'Booking successful']);
        }

        return response()->json(['error' => 'Error booking for event']);
    }

    public function bookedEvents(Request $request)
    {
        $events = collect([]);
        $getEvents = DB::table('event_booking')->select('event_id')->where('user_id', $request->user()->id)->get();
        
        foreach ($getEvents as $event) {
            $events->push(Event::where('id', $event->event_id)->get());
        }

        return response()->json(['events' => json_encode($events)]);
    }

    public function deleteBookedEvent(Request $request, $id)
    {
        $deleteEvent = DB::table('event_booking')
            ->where('event_id', '=', $id)
            ->where('user_id', '=', $request->user()->id)
            ->delete();

        if($deleteEvent) {
            return response()->json(['success' => 'Deleted event']);
        }

        return response()->json(['error' => 'Error deleting the event']);
    }
}
