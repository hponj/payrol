<?php

namespace App\Filament\Resources\Schedules\Schemas;

use App;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SchedulesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                ->components([
                    Section::make()
                    ->components([
                        Select::make('user_id')
                            ->options(User::query()->pluck('name','id'))
                            ->label('Nama_Pegawai')
                            ->preload()
                            ->searchable(),
                        Select::make('shift_id')
                            ->relationship('shift', 'name')
                            ->label('Shift')
                            ->preload()
                            ->searchable()
                            ->required(),
                        Select::make('office_id')
                            ->relationship('office', 'name')
                            ->label('Kantor')
                            ->preload()
                            ->searchable()
                            ->required(),
                        Toggle::make('is_wfa')
                            ->label('WFA')
                            ->onIcon(Heroicon::Home)
                            ->offIcon(Heroicon::BuildingOffice2),
                        
                    ])
                ]),
            ]);
    }
}
