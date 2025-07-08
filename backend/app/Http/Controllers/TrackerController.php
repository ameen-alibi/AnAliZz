<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Website;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    public function store(Request $req)
    {
        $data = $req->validate([
            'token'      => 'required|exists:websites,tracking_token',
            'session_id' => 'required|uuid',
            'event_type' => 'required|string|in:pageview,scroll,click,heartbeat',
            'url'        => 'required|url',
            'scroll_depth' => 'nullable|numeric|min:0|max:100',
            'duration_ms' => 'nullable|integer|min:0',  // heartbeat sends elapsed ms
            'payload'    => 'nullable|array',
        ]);

        $website = Website::where('tracking_token', $data['token'])->firstOrFail();
        $geo     = geoip($req->ip());

        Event::create([
            'website_id'   => $website->id,
            'session_id'   => $data['session_id'],
            'event_type'   => $data['event_type'],
            'url'           => $data['url'],
            'payload'      => $data['payload'] ?? null,
            'ip_address'   => $req->ip(),
            'country_code' => $geo->iso_code,
            'country_name' => $geo->country,
            'extra'        => json_encode([
                'scroll_depth'  => $data['scroll_depth'] ?? null,
                'duration_ms'   => $data['duration_ms']  ?? null,
            ]),
        ]);

        return response()->json(['status' => 'ok']);
    }
}
