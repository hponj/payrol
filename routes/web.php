<?php

use App\Livewire\Payroll;
use App\Livewire\Presensi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/presensi', Presensi::class)->name('presensi')->middleware("auth", "isLeave");
Route::get('/payroll', Payroll::class)->name('payroll')->middleware("auth", "isAdmin");

Route::get('/login', function (){
    return redirect('/dashboard');
});
