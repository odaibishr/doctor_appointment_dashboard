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

        {{-- Horizontal Layout using inline styles to ensure proper flex display --}}
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            @foreach ($this->getActions() as $action)
                <a
                    href="{{ $action['url'] }}"
                    style="display: flex; flex: 1 1 220px; align-items: center; gap: 1rem; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; background: white; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.3s ease; text-decoration: none;"
                    class="group hover:-translate-y-1 hover:shadow-lg hover:border-primary-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-primary-600"
                    onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)';"
                >
                    {{-- Icon Container --}}
                    <div style="display: flex; flex-shrink: 0; height: 3rem; width: 3rem; align-items: center; justify-content: center; border-radius: 0.75rem; background: rgba(54, 73, 137, 0.1); color: #364989; transition: all 0.3s ease;">
                        <x-filament::icon
                            :icon="$action['icon']"
                            class="h-6 w-6"
                        />
                    </div>

                    {{-- Text Content --}}
                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-size: 0.875rem; font-weight: 600; color: #111827;">
                            {{ $action['label'] }}
                        </span>
                        <span style="font-size: 0.75rem; color: #6b7280;">
                            {{ $action['description'] }}
                        </span>
                    </div>

                    {{-- Arrow Icon --}}
                    <div style="margin-right: auto; opacity: 0; transition: opacity 0.3s ease;" class="group-hover:opacity-100">
                        <x-filament::icon
                            icon="heroicon-m-arrow-left"
                            class="h-4 w-4 text-primary-500 rtl:rotate-180"
                        />
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
