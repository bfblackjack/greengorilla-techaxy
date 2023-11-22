<?php

namespace App\Http\Controllers;

use App\Models\BuyerEndpoint;
use App\Models\Contact;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $tokens = $request->user()->tokens;
        $role = $request->user()->roles[0]->name;

        // Metrics :-)
        $report = Contact::orderBy('created_at');
        if($role === 'buyer') {
            $report = $report->whereHas('buyer', function($q) use($request) {
                $q->where('buyer_id', $request->user()->buyer->id);
            });
        }
        else if($role === 'publisher') {
            $report = $report->whereHas('publisher', function($q) use($request) {
                $q->where('publisher_id', $request->user()->publisher->id);
            });
        }

        $report = $report->get()->map(function(Contact $contact) use($role) {
             return [
                 'id' => $contact->id,
                 'caller_id' => $contact->caller_id,
                 'external_id' => $role === 'publisher' ? $contact->external_id : null,
                 'status' => $contact->status,
                 'date' => $contact->created_at->format('m/d/Y'),
             ];
        })->values();

        return inertia()->render('Dashboard')->with([
            'tokens' => $tokens,
            'report' => $report,
        ]);
    }

    public function createWebhook(Request $request) {
        // Delete previous tokens, if on file.
        $request->user()->tokens()->delete();

        // NEW Token!
        $token = $request->user()->createToken($request->user()->roles[0]->name . ' Webhook');

        return response()->json(['status' => 'success', 'token' => $token->plainTextToken]);
    }

    public function updateBuyerEndpoint(Request $request) {
        $request->validate([
            'webhook' => 'required|url|active_url'
        ]);

        BuyerEndpoint::create([
            'buyer_id' => $request->user()->buyer->id,
            'endpoint_url' => $request->webhook,
        ]);
    }
}
