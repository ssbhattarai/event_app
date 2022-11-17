<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{

    public function index(): JsonResponse
    {
        $events = Event::query();
        $events = $events->order('start_date', 'asc')
            ->paginate(25);

        return EventResource::collection($events)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            Event::create($request->all());

            DB::commit();

            return \response()->json([
                'message' => 'Event Created Successfully'
            ], 201);
        } catch (\Exception $exception) {

            DB::rollBack();
            return \response()->json([
                'message' => 'Something Went Wrong'
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(EventRequest $request, Event $event): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $event->update($data);

            DB::commit();

            return \response()->json([
                'message' => 'Event Updated Successfully'
            ], 200);

        } catch (\Exception $exception) {

            DB::rollBack();
            return \response()->json([
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     *
     */
    public function destroy(Event $event): JsonResponse
    {
        $event->delete();

        return \response()->json([
            'message' => 'event Delete Successfully'
        ], 200);
    }
}
