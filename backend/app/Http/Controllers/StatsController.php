<?php

namespace App\Http\Controllers;

use App\Models\Metric;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function overview(Request $req)
    {
        $params = $req->validate([
            'site_id' => 'required|exists:websites,id',
            'from'    => 'required|date',
            'to'      => 'required|date|after_or_equal:from',
        ]);

        return Metric::where('website_id', $params['site_id'])
            ->whereBetween('metric_date', [$params['from'], $params['to']])
            ->orderBy('metric_date')
            ->get([
                'metric_date',
                'pageviews',
                'unique_visitors',
                'sessions',
                'bounce_rate',
                'avg_session_duration'
            ]);
    }
}
