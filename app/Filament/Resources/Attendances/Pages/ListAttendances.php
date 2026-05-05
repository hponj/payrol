<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use Override;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Presensi')
                ->label('Presensi')
                ->color('success')
                ->url('/presensi'),
            CreateAction::make(),
        ];
    }

    #[Override]
    protected function getTableQuery(): Builder|Relation|null
    {
        $query = parent::getTableQuery();
        
        if (Auth::user()->hasRole('super_admin')) {
            return $query;
        };

        return $query->where('user_id', Auth::id());
    }
}
