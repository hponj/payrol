<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Override;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}


