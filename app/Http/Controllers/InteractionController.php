<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Interaction;
use App\Models\InteractionEvent;

class InteractionController extends Controller
{
    public function index()
    {
        $interactions = Interaction::all();

        return response(['interactions' => $interactions]);
    }

    public function show($id)
    {
        $interaction = Interaction::find($id);

        return response(['interaction' => $interaction]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'label' => 'required',
            'type' => 'required',
        ]);

        $interaction = Interaction::create($validatedData);

        return response(['interaction' => $interaction, 'message' => 'Interaction created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'label' => 'required',
            'type' => 'required',
        ]);

        $interaction = Interaction::find($id);
        $interaction->update($validatedData);

        return response(['interaction' => $interaction, 'message' => 'Interaction updated successfully']);
    }

    public function destroy($id)
    {
        $interaction = Interaction::find($id);
        $interaction->delete();

        return response(['message' => 'Interaction deleted']);
    }

    public function triggerEvent(Request $request)
    {
        $validatedData = $request->validate([
            'interaction_id' => 'required|exists:interactions,id',
            'event_type' => 'required|in:click,hover',
        ]);

        $interaction = Interaction::find($validatedData['interaction_id']);

        if (!$interaction) {
            return response(['message' => 'Interaction not found'], 404);
        }

     
        $interactionEvent = InteractionEvent::create([
            'interaction_id' => $interaction->id,
            'event_type' => $validatedData['event_type'],
        ]);

        

        return response(['interaction_event' => $interactionEvent, 'message' => 'Interaction event recorded']);
    }

}

