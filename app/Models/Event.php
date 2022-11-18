<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_live'
    ];

    public function scopeQ($query, $val)
    {
        return $query->where('title', 'like', '%' . $val . $val);
    }


    public function scopeFilters($query, $filter = null)
    {
        return $query->when($filter, function ($query, $value) {
            if ($value == 'finished_events') {
                $query->whereDate('end_date', '<', Carbon::now());
            }
            if ($value == 'upcoming_events') {
                $query->whereDate('start_date', '>', Carbon::now());
            }
            if ($value == 'upcoming_events_within_7_days') {
                $query->whereDate('start_date', '>', Carbon::now())->whereDate('start_date', '<', Carbon::now()->addDays(7));
            }

            if ($value == 'finished_events_of_7_days') {
                $query->whereDate('end_date', '<', Carbon::now())
                    ->whereDate('end_date', '>', Carbon::now()->subDays(7));
            }
        });

    }
}
