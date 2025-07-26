<?php

namespace App\Http\Controllers;


use App\Models\Event;
use App\Models\Website;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;

class WebsiteController extends Controller
{
    use AuthorizesRequests;

    // list current user's website
    public function index(Request $req)
    {
        return $req->user()
            ->websites()
            ->select('id', 'name', 'domain', 'tracking_token', 'created_at')
            ->orderByDesc('created_at')
            ->get();
    }

    public function show(Request $req, Website $website)
    {
        $this->authorize('view', $website);
        return $website->only(['id', 'name', 'domain', 'tracking_token', 'created_at']);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name'   => 'required|string|max:100',
            'domain' => 'required|url',
        ]);

        $website = $req->user()->websites()->create([
            'name'           => $data['name'],
            'domain'         => $data['domain'],
            'tracking_token' => Str::uuid(),
        ]);

        return response()->json($website, 201);
    }

    public function stats(Request $req, Website $website)
    {
        $this->authorize('view', $website);

        $cacheKey = "stats:site:{$website->id}";

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($website) {
            $uniqueVisits = Event::where('website_id', $website->id)
                ->where('event_type', 'pageview')
                ->distinct('session_id')
                ->count('session_id');

            $totalMs = Event::where('website_id', $website->id)
                ->where('event_type', 'heartbeat')
                ->sum(DB::raw("JSON_EXTRACT(extra,'$.duration_ms')"));
            $avgTimeSec = $uniqueVisits
                ? round(($totalMs / $uniqueVisits) / 1000, 1)
                : 0;


            $sessions = Event::where('website_id', $website->id)
                ->where('event_type', 'scroll')
                ->get()
                ->groupBy('session_id');
            $avgScroll = $sessions->count()
                ? round($sessions->map(fn($events) => $events
                    ->max(fn($e) => json_decode($e->extra)->scroll_depth))
                    ->avg(), 1)
                : 0;


            $countries = Event::where('website_id', $website->id)
                ->select('country_name', DB::raw('COUNT(DISTINCT session_id) as sessions'))
                ->groupBy('country_name')
                ->orderByDesc('sessions')
                ->limit(5)
                ->get();

            return [
                'unique_visits'      => $uniqueVisits,
                'avg_time_on_screen' => $avgTimeSec,
                'avg_scroll_depth'   => $avgScroll,
                'top_countries'      => $countries,
            ];
        });
    }
}
