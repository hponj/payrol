<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\SchedulesResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use League\Uri\Builder;
use Override;

class ListSchedules extends ListRecords
{
    protected static string $resource = SchedulesResource::class;

    protected function getHeaderActions(): array
    {
        return [   
            CreateAction::make(),
        ];
    }

    #[Override]
    protected function getTableQuery(): EloquentBuilder|Relation|null
    {
        $query = parent::getTableQuery();
        if (Auth::user()->hasRole('super_admin')) {
            return $query;
        };

        return $query->where('user_id', Auth::id());

        
    }
}
