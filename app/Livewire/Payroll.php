<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Schedules;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Payroll extends Component
{
    public $user_id;
    public $start_date;
    public $end_date;

    public $pegawai;

    public $total_duration = "00:00:00";
    public $total_hours = 0;
    public $total_salary = 0;

    public $rate_per_hour = 35000;

    public $leave_pay = 0;

    public function render()
    {
        $users = User::all();

        return view('livewire.payroll', compact('users'))
            ->layout('layouts.main');
    }

    public function calculate()
    {
        $this->validate([
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $this->pegawai = User::find($this->user_id);

        if (!$this->pegawai) {
            session()->flash('error', 'Pegawai tidak ditemukan');
            return;
        }

        $start = Carbon::parse($this->start_date)->startOfDay();
        $end = Carbon::parse($this->end_date)->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Attendance
        |--------------------------------------------------------------------------
        */

        $attendances = Attendance::where('user_id', $this->user_id)
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('duration')
            ->get();

        $attendanceSeconds = $attendances->sum(function ($item) {

            if (!$item->duration || !str_contains($item->duration, ':')) {
                return 0;
            }

            [$hours, $minutes, $seconds] = explode(':', $item->duration);

            return ((int) $hours * 3600)
                + ((int) $minutes * 60)
                + (int) $seconds;
        });

        /*
        |--------------------------------------------------------------------------
        | Schedule
        |--------------------------------------------------------------------------
        */

        $schedule = Schedules::where('user_id', $this->user_id)
            ->with('shift')
            ->first();

        if (!$schedule || !$schedule->shift) {
            session()->flash('error', 'Schedule atau shift tidak ditemukan');
            return;
        }

        $scheduleStart = Carbon::parse($schedule->shift->start_time);
        $scheduleEnd = Carbon::parse($schedule->shift->end_time);

        // handle shift malam
        if ($scheduleEnd->lessThan($scheduleStart)) {
            $scheduleEnd->addDay();
        }

        $scheduleSeconds = $scheduleStart->diffInSeconds($scheduleEnd);

        /*
        |--------------------------------------------------------------------------
        | Leave
        |--------------------------------------------------------------------------
        */

        $leaves = Leave::where('user_id', $this->user_id)
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'approved')
            ->get();

        // jumlah hari cuti
        $totalLeaveDays = $leaves->count();

        // total detik cuti
        $leaveSeconds = $totalLeaveDays * $scheduleSeconds;

        // total bayaran cuti
        $this->leave_pay = ($leaveSeconds / 3600) * $this->rate_per_hour;

        /*
        |--------------------------------------------------------------------------
        | Total Duration
        |--------------------------------------------------------------------------
        */

        $totalSeconds = $attendanceSeconds + $leaveSeconds;

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $this->total_duration = sprintf(
            '%02d:%02d:%02d',
            $hours,
            $minutes,
            $seconds
        );

        /*
        |--------------------------------------------------------------------------
        | Total Hours
        |--------------------------------------------------------------------------
        */

        $this->total_hours = round($totalSeconds / 3600, 2);

        /*
        |--------------------------------------------------------------------------
        | Total Salary
        |--------------------------------------------------------------------------
        */

        $this->total_salary = round(
            $this->total_hours * $this->rate_per_hour,
            0
        );
    }

    public function getFormattedDurationProperty()
    {
        if (!$this->total_duration) {
            return null;
        }

        [$jam, $menit, $detik] = explode(':', $this->total_duration);

        return (int) $jam . ' jam '
            . (int) $menit . ' menit '
            . (int) $detik . ' detik';
    }
}