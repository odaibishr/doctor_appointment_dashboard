<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookAppointments\BookAppointmentResource;
use App\Filament\Resources\Doctors\DoctorResource;
use App\Filament\Resources\Patients\PatientResource;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.quick-actions-widget';

    public function getActions(): array
    {
        $user = Auth::user();
        $actions = [];

        if ($user?->isAdmin()) {
            $actions = [
                [
                    'label' => 'إضافة حجز جديد',
                    'icon' => 'heroicon-o-calendar-days',
                    'url' => BookAppointmentResource::getUrl('create'),
                    'color' => 'primary',
                    'description' => 'إنشاء حجز موعد جديد',
                ],
                [
                    'label' => 'تسجيل طبيب',
                    'icon' => 'heroicon-o-user-plus',
                    'url' => DoctorResource::getUrl('create'),
                    'color' => 'success',
                    'description' => 'إضافة طبيب جديد للنظام',
                ],
                [
                    'label' => 'تسجيل مريض',
                    'icon' => 'heroicon-o-users',
                    'url' => PatientResource::getUrl('create'),
                    'color' => 'info',
                    'description' => 'إضافة مريض جديد للنظام',
                ],
                [
                    'label' => 'عرض الحجوزات',
                    'icon' => 'heroicon-o-clipboard-document-list',
                    'url' => BookAppointmentResource::getUrl('index'),
                    'color' => 'warning',
                    'description' => 'عرض جميع الحجوزات',
                ],
            ];
        } elseif ($user?->isDoctor()) {
            $actions = [
                [
                    'label' => 'حجوزاتي',
                    'icon' => 'heroicon-o-calendar-days',
                    'url' => BookAppointmentResource::getUrl('index'),
                    'color' => 'primary',
                    'description' => 'عرض حجوزاتي اليومية',
                ],
            ];
        } elseif ($user?->isPatient()) {
            $actions = [
                [
                    'label' => 'حجز موعد جديد',
                    'icon' => 'heroicon-o-calendar-days',
                    'url' => BookAppointmentResource::getUrl('create'),
                    'color' => 'primary',
                    'description' => 'حجز موعد مع طبيب',
                ],
                [
                    'label' => 'مواعيدي',
                    'icon' => 'heroicon-o-clipboard-document-list',
                    'url' => BookAppointmentResource::getUrl('index'),
                    'color' => 'info',
                    'description' => 'عرض مواعيدي السابقة والقادمة',
                ],
            ];
        }

        return $actions;
    }
}
