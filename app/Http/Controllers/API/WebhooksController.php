<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Contact;
use App\Models\ContactLog;
use App\Models\Publisher;
use App\Models\PublisherContact;
use App\Models\SettingsLogAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebhooksController extends Controller
{
    // Publishers post here.
    public function incomingLeads(Publisher $publisher, Request $request) {
        $request->validate([
            'caller_id' => 'required',
            'identifier' => 'sometimes|nullable',
        ]);

        $contact = new Contact();
        $contact->internal_id = Str::uuid();
        $contact->external_id = isset($request->identifier) ? $request->identifier : null;
        $contact->status = "unknown";
        $contact->save();

        PublisherContact::create([
            'contact_id' => $contact->id,
            'publisher_id' => $publisher->id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data received.']);
    }

    // Callers update the leads we sent them with the status here
    public function leadUpdate(Buyer $buyer, Request $request) {
        $request->validate([
            'status' => 'in:billed,nonbillable',
            'caller_id' => 'required|exists:contacts,caller_id'
        ]);

        $contact = Contact::where('caller_id', $request->caller_id)->first();
        $contact->status = $request->status;
        $contact->save();

        $author = $buyer->user_id;

        ContactLog::create([
            'contact_id' => $contact->id,
            'action_id' => SettingsLogAction::firstOrCreate(['action' => 'Contact updated by buyer.'])->id,
            'author_id' => $author,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data received.']);
    }
}
