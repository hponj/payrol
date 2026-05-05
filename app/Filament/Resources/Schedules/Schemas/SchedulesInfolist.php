<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use phpDocumentor\Reflection\Types\Boolean;

class SchedulesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('shift.name')
                    ->label('Shift'),
                TextEntry::make('office.name')
                    ->label('Office'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                
            ]);
    }
}
