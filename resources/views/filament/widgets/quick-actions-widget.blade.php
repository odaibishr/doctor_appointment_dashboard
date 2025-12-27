<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon
                    icon="heroicon-o-bolt"
                    class="h-5 w-5 text-primary-500"
                />
                <span>إجراءات سريعة</span>
            </div>
        </x-slot>

        <x-slot name="description">
            الوصول السريع للمهام الأكثر استخداماً
        </x-slot>

        {{-- Horizontal Layout with Dark Mode Support --}}
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            @foreach ($this->getActions() as $action)
                <a
                    href="{{ $action['url'] }}"
                    class="quick-action-card group"
                >
                    {{-- Icon Container --}}
                    <div class="quick-action-icon">
                        <x-filament::icon
                            :icon="$action['icon']"
                            class="h-6 w-6"
                        />
                    </div>

                    {{-- Text Content --}}
                    <div class="quick-action-text">
                        <span class="quick-action-label">
                            {{ $action['label'] }}
                        </span>
                        <span class="quick-action-description">
                            {{ $action['description'] }}
                        </span>
                    </div>

                    {{-- Arrow Icon --}}
                    <div class="quick-action-arrow">
                        <x-filament::icon
                            icon="heroicon-m-arrow-left"
                            class="h-4 w-4 rtl:rotate-180"
                        />
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>

    <style>
        .quick-action-card {
            display: flex;
            flex: 1 1 220px;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .quick-action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #364989;
        }

        .quick-action-icon {
            display: flex;
            flex-shrink: 0;
            height: 3rem;
            width: 3rem;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: rgba(54, 73, 137, 0.1);
            color: #364989;
            transition: all 0.3s ease;
        }

        .quick-action-card:hover .quick-action-icon {
            transform: scale(1.1);
            background: rgba(54, 73, 137, 0.2);
        }

        .quick-action-text {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .quick-action-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
        }

        .quick-action-description {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .quick-action-arrow {
            margin-right: auto;
            opacity: 0;
            transition: opacity 0.3s ease;
            color: #364989;
        }

        [dir="ltr"] .quick-action-arrow {
            margin-right: 0;
            margin-left: auto;
        }

        .quick-action-card:hover .quick-action-arrow {
            opacity: 1;
        }

        /* Dark Mode Styles */
        .dark .quick-action-card {
            background: #1f2937;
            border-color: #374151;
        }

        .dark .quick-action-card:hover {
            border-color: #6366f1;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        .dark .quick-action-icon {
            background: rgba(99, 102, 241, 0.2);
            color: #818cf8;
        }

        .dark .quick-action-card:hover .quick-action-icon {
            background: rgba(99, 102, 241, 0.3);
        }

        .dark .quick-action-label {
            color: #f9fafb;
        }

        .dark .quick-action-description {
            color: #9ca3af;
        }

        .dark .quick-action-arrow {
            color: #818cf8;
        }
    </style>
</x-filament-widgets::widget>
