<x-ui.page-layout>
    <x-ui.page-header :title="__('import.title')" :subtitle="__('import.subtitle')">
    </x-ui.page-header>

    {{-- Step: Upload --}}
    @if($step === 'upload')
    <div class="space-y-6">
        {{-- Import type --}}
        <x-ui.section :title="__('import.select_type')" icon="file-spreadsheet" :noPadding="false">
            <div class="grid grid-cols-2 gap-3 mb-4">
                <button wire:click="$set('importType', 'projects')"
                        class="p-4 rounded-xl border-2 text-center transition-all {{ $importType === 'projects' ? 'border-accent bg-accent/5' : 'border-border-light hover:border-accent/30' }}">
                    <x-lucide-folder class="w-6 h-6 mx-auto mb-2 {{ $importType === 'projects' ? 'text-accent' : 'text-muted' }}" />
                    <p class="text-xs font-bold {{ $importType === 'projects' ? 'text-accent' : 'text-heading' }}">{{ __('import.projects') }}</p>
                </button>
                <button wire:click="$set('importType', 'activities')"
                        class="p-4 rounded-xl border-2 text-center transition-all {{ $importType === 'activities' ? 'border-accent bg-accent/5' : 'border-border-light hover:border-accent/30' }}">
                    <x-lucide-list-checks class="w-6 h-6 mx-auto mb-2 {{ $importType === 'activities' ? 'text-accent' : 'text-muted' }}" />
                    <p class="text-xs font-bold {{ $importType === 'activities' ? 'text-accent' : 'text-heading' }}">{{ __('import.activities') }}</p>
                </button>
            </div>

            {{-- Result selector for activities --}}
            @if($importType === 'activities')
                <div class="mb-4">
                    <label class="text-xs font-bold text-heading block mb-1">{{ __('import.target_result') }}</label>
                    <select wire:model="resultId" class="input-field w-full text-xs">
                        <option value="">{{ __('import.select_result') }}</option>
                        @foreach($availableResults as $result)
                            <option value="{{ $result->id }}">
                                {{ $result->specificObjective?->logicalFramework?->project?->title }} → {{ Str::limit($result->description, 50) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Download template --}}
            <button wire:click="downloadTemplate" class="flex items-center gap-2 text-xs text-accent font-bold hover:underline mb-4">
                <x-lucide-download class="w-3.5 h-3.5" />
                {{ __('import.download_template') }}
            </button>
        </x-ui.section>

        {{-- File upload --}}
        <x-ui.section :title="__('import.upload_file')" icon="upload" :noPadding="false">
            <div class="space-y-4">
                <input type="file" wire:model="file" accept=".xlsx,.xls,.csv"
                       class="block w-full text-xs text-body file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                @error('file') <p class="text-xs text-error">{{ $message }}</p> @enderror

                <p class="text-[10px] text-muted">{{ __('import.file_hint') }}</p>

                <x-ui.button wire:click="parseFile" variant="accent" icon="eye" :disabled="!$file">
                    <span wire:loading.remove wire:target="parseFile">{{ __('import.preview_data') }}</span>
                    <span wire:loading wire:target="parseFile">{{ __('import.processing') }}</span>
                </x-ui.button>
            </div>
        </x-ui.section>
    </div>
    @endif

    {{-- Step: Preview --}}
    @if($step === 'preview')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-heading">{{ __('import.preview_title') }}</p>
                <p class="text-xs text-muted">
                    {{ count(array_filter($preview, fn($r) => $r['valid'])) }} {{ __('import.valid_rows') }}
                    · {{ count(array_filter($preview, fn($r) => !$r['valid'])) }} {{ __('import.invalid_rows') }}
                </p>
            </div>
            <button wire:click="resetImport" class="text-xs text-muted hover:text-heading font-bold">
                <x-lucide-arrow-left class="w-3.5 h-3.5 inline" /> {{ __('common.back') }}
            </button>
        </div>

        {{-- Preview table --}}
        <div class="overflow-x-auto bg-card rounded-xl border border-border-light">
            <table class="w-full text-xs">
                <thead>
                    <tr class="bg-surface text-muted font-bold uppercase tracking-wider">
                        <th class="px-3 py-2 text-left">#</th>
                        <th class="px-3 py-2 text-left">{{ __('common.status') }}</th>
                        @if($importType === 'projects')
                            <th class="px-3 py-2 text-left">{{ __('common.title') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('common.code') }}</th>
                        @else
                            <th class="px-3 py-2 text-left">{{ __('common.description') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('activities.responsible') }}</th>
                        @endif
                        <th class="px-3 py-2 text-left">{{ __('common.details') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($preview as $row)
                        <tr class="{{ $row['valid'] ? '' : 'bg-error/5' }} border-t border-border-light">
                            <td class="px-3 py-2 text-muted">{{ $row['row'] }}</td>
                            <td class="px-3 py-2">
                                @if($row['valid'])
                                    <x-lucide-check-circle class="w-4 h-4 text-success" />
                                @else
                                    <x-lucide-x-circle class="w-4 h-4 text-error" />
                                @endif
                            </td>
                            @if($importType === 'projects')
                                <td class="px-3 py-2 font-bold text-heading">{{ $row['title'] ?? '' }}</td>
                                <td class="px-3 py-2 text-muted">{{ $row['code'] ?? '' }}</td>
                            @else
                                <td class="px-3 py-2 font-bold text-heading">{{ Str::limit($row['description'] ?? '', 50) }}</td>
                                <td class="px-3 py-2 text-muted">{{ $row['responsible_email'] ?? '' }}</td>
                            @endif
                            <td class="px-3 py-2">
                                @foreach($row['errors'] as $err)
                                    <span class="text-error text-[10px]">{{ $err['message'] }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Confirm --}}
        @if(count(array_filter($preview, fn($r) => $r['valid'])) > 0)
            <div class="flex justify-end">
                <x-ui.button wire:click="confirmImport" variant="accent" icon="check">
                    {{ __('import.confirm_import', ['count' => count(array_filter($preview, fn($r) => $r['valid']))]) }}
                </x-ui.button>
            </div>
        @endif
    </div>
    @endif

    {{-- Step: Result --}}
    @if($step === 'result')
    <div class="space-y-6">
        <x-ui.section :title="__('import.result_title')" icon="check-circle" :noPadding="false">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="p-4 bg-success/10 rounded-xl text-center">
                    <p class="text-2xl font-black text-success">{{ $importedCount }}</p>
                    <p class="text-[10px] font-bold text-success uppercase">{{ __('import.imported') }}</p>
                </div>
                <div class="p-4 bg-error/10 rounded-xl text-center">
                    <p class="text-2xl font-black text-error">{{ $errorCount }}</p>
                    <p class="text-[10px] font-bold text-error uppercase">{{ __('import.errors') }}</p>
                </div>
            </div>

            @if(count($importErrors ?? []) > 0)
                <div class="space-y-1 mb-4">
                    @foreach($importErrors as $err)
                        <p class="text-xs text-error">
                            <span class="font-bold">{{ __('import.row') }} {{ $err['row'] }} :</span> {{ $err['message'] }}
                        </p>
                    @endforeach
                </div>
            @endif

            <x-ui.button wire:click="resetImport" variant="accent" icon="plus">
                {{ __('import.import_another') }}
            </x-ui.button>
        </x-ui.section>
    </div>
    @endif
</x-ui.page-layout>
