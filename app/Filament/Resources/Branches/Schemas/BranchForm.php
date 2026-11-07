<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('telepon')
                    ->tel(),
                TextInput::make('latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('longitude')
                    ->required()
                    ->numeric(),
            ]);
    }
}
