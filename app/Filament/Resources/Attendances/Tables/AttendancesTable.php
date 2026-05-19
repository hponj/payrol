<?php

namespace App\Filament\Resources\Attendances\Tables;

use App\Models\Attendance;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('schedule_latitude')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('schedule_longitude')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('schedule_start_time')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->time()
                    ->sortable(),
                TextColumn::make('schedule_end_time')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->time()
                    ->sortable(),
                TextColumn::make('latitude')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Start Time')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('End Time')
                    ->time()
                    ->sortable(),
                TextColumn::make('duration')
                    ->label('Duration')
                    ->time()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('is_Late')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->isLate() ? 'Terlambat' : 'Tepat Waktu')
                    ->colors([
                        'danger' => fn ($state) => $state === 'Terlambat',
                        'success' => fn ($state) => $state === 'Tepat Waktu',
                    ])
                    ->sortable()
                    ->description(function (Attendance $record){
                        return 'Durasi: ' . $record->WorkDuration();
                    }),
                    
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
