<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Schedules;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Presensi extends Component
{
    public $latitude;
    public $longitude;
    public $insideRadius = false;


    public function render()
    {
        $schedule = Schedules::where('user_id', Auth::id())->first();
        $insideRadius = $this->insideRadius;
        $attendance = Attendance::where('user_id', Auth::id())->whereDate('created_at', now())->first();
        return view('livewire.presensi', compact('schedule', 'insideRadius', 'attendance'))->layout('layouts.main');
    }

    public function store(){

        $this->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $schedule = Schedules::where('user_id', Auth::user()->id)->first();

        if ($schedule){

            $attendance = Attendance::where('user_id', Auth::id())->whereDate('created_at', now())->first();

            if(!$attendance){
                $attendance = Attendance::create([
                    'user_id' => Auth::id(),
                    'schedule_latitude' => $schedule->office->latitude,
                    'schedule_longitude' => $schedule->office->longitude,
                    'schedule_start_time' => $schedule->shift->start_time,
                    'schedule_end_time' => $schedule->shift->end_time,
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                    'start_time' => Carbon::now()->toTimeString(),
                    'end_time' => Carbon::now()->toTimeString(),

                    Notification::make()
                        ->title('presentasi Berhasil')
                        ->success()
                        ->body('Data presensi berhasil disimpan')
                        ->send()
                ]);
            } else {
                $attendance->update([
                    'latitude' => $this->latitude,
                    'longitude' => $this->longitude,
                    'end_time' => Carbon::now()->toTimeString(),

                Notification::make()
                    ->title('presentasi Berhasil')
                    ->success()
                    ->body('Data presensi berhasil diupdate')
                    ->send()
                ]);
            }

            

            return redirect('/dashboard/attendances');
            // return redirect()->route('presensi', [
            //     'schedule' => $schedule,
            //     'insideRadius' => false
            // ]);
        }
    }
}
