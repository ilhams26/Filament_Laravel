<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('telepon')
                    ->tel(),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                Grid::make(2)
                    ->schema([
                        TextInput::make('latitude')
                            ->required()
                            ->numeric()
                            ->minValue(-90)
                            ->maxValue(90)
                            ->extraInputAttributes(['id' => 'branch-latitude'])
                            ->helperText('Contoh: -6.1754'),
                        TextInput::make('longitude')
                            ->required()
                            ->numeric()
                            ->minValue(-180)
                            ->maxValue(180)
                            ->extraInputAttributes(['id' => 'branch-longitude'])
                            ->helperText('Contoh: 106.8272'),
                    ])
                    ->columnSpanFull(),
                View::make('filament.forms.components.map-picker')
                    ->columnSpanFull(),
            ]);
    }
}
