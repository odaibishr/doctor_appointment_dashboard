<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('doctor_id')
                    ->label('الطبيب')
                    ->relationship('doctor', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(2),

                Select::make('user_id')
                    ->label('المريض')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(2),

                TextInput::make('rating')
                    ->label('التقييم')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(5)
                    ->default(0)
                    ->columnSpan(1),

                Textarea::make('comment')
                    ->label('التعليق')
                    ->columnSpanFull(),
            ]);
    }
}
