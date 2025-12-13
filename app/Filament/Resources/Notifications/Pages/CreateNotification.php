<?php

namespace App\Filament\Resources\Notifications\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Notifications\NotificationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNotification extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = NotificationResource::class;
}

