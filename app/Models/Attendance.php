<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Attendance extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $with = ['user'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function isLate()
    {
        $scheduleStartTime = Carbon::parse($this->schedule_start_time);
        $startTime = Carbon::parse($this->start_time);

        return $startTime->greaterThan($scheduleStartTime);
        
    }

    public function WorkDuration()
    {
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        $duration = $startTime->diff($endTime);
        
        $hours = $duration->h;
        $minutes = $duration->i;

        return $hours . ' jam ' . $minutes . ' menit';
        
    }

    protected static function booted()
    {
        static::saving(function ($attendance){
            if ($attendance->start_time && $attendance->end_time){

                $startTime = Carbon::parse($attendance->start_time);
                $endTime = Carbon::parse($attendance->end_time);

                if ($endTime->lessThan($startTime)){
                    $endTime->addDay();
                }

                $totalSecond = $startTime->diffInSeconds($endTime);

                $attendance->duration = gmdate('H:i:s', $totalSecond);

            }
        });
    }
}
