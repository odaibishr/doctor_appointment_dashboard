<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = TransactionResource::class;
}

