<?php

namespace App\Filament\Resources\Reviews\Pages;

use App\Filament\Resources\Concerns\RedirectsToIndex;
use App\Filament\Resources\Reviews\ReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReview extends CreateRecord
{
    use RedirectsToIndex;

    protected static string $resource = ReviewResource::class;
}

