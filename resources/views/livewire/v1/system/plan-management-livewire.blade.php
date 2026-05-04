<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-black text-heading">{{ __('plans.plan_management') }}</h2>
            <p class="text-xs text-muted">{{ __('plans.plan_management_desc') }}</p>
        </div>
        <x-ui.button variant="accent" icon="plus" wire:click="openCreate">
            {{ __('plans.new_plan') }}
        </x-ui.button>
    </div>

    {{-- Plans grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($plans as $plan)
            <div class="bg-card rounded-2xl border {{ $plan->is_active ? 'border-border-light' : 'border-error/30 opacity-60' }} p-5 relative">
                {{-- Status badges --}}
                <div class="flex items-center gap-2 mb-3">
                    @if($plan->is_default)
                        <span class="px-2 py-0.5 bg-accent/10 text-accent text-[9px] font-black rounded-full uppercase">{{ __('plans.default') }}</span>
                    @endif
                    @if(!$plan->is_active)
                        <span class="px-2 py-0.5 bg-error/10 text-error text-[9px] font-black rounded-full uppercase">{{ __('common.inactive') }}</span>
                    @endif
                </div>

                {{-- Plan info --}}
                <h3 class="text-lg font-black {{ $plan->color() }}">{{ $plan->name }}</h3>
                <p class="text-2xl font-black text-heading mt-1">
                    {{ $plan->formattedPrice() }}
                    <span class="text-xs text-muted font-normal">/{{ $plan->billing_period === 'month' ? __('plans.month') : __('plans.year') }}</span>
                </p>

                {{-- Limits --}}
                <div class="mt-4 space-y-2 text-xs text-body">
                    <div class="flex items-center gap-2">
                        <x-lucide-folder class="w-3.5 h-3.5 text-accent" />
                        <span>{{ $plan->maxProjects() === -1 ? '∞' : $plan->maxProjects() }} {{ __('plans.projects') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-lucide-users class="w-3.5 h-3.5 text-accent" />
                        <span>{{ $plan->maxMembers() === -1 ? '∞' : $plan->maxMembers() }} {{ __('plans.members') }}</span>
                    </div>
                </div>

                {{-- Features --}}
                <div class="mt-3 flex flex-wrap gap-1">
                    @foreach($plan->features ?? [] as $feature)
                        <span class="px-1.5 py-0.5 bg-surface text-[8px] font-bold text-muted rounded">{{ $feature }}</span>
                    @endforeach
                </div>

                {{-- Actions --}}
                <div class="mt-4 pt-3 border-t border-border-light flex items-center gap-2">
                    <button wire:click="openEdit('{{ $plan->id }}')" class="text-xs text-accent hover:underline font-bold">
                        {{ __('common.edit') }}
                    </button>
                    <button wire:click="toggleActive('{{ $plan->id }}')" class="text-xs {{ $plan->is_active ? 'text-warning' : 'text-success' }} hover:underline font-bold">
                        {{ $plan->is_active ? __('common.deactivate') : __('common.activate') }}
                    </button>
                    @if(!$plan->is_default)
                        <button wire:click="deletePlan('{{ $plan->id }}')"
                                wire:confirm="{{ __('plans.confirm_delete') }}"
                                class="text-xs text-error hover:underline font-bold">
                            {{ __('common.delete') }}
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="$set('showModal', false)">
        <div class="bg-card rounded-2xl shadow-2xl border border-border-light w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
            <h3 class="text-lg font-black text-heading mb-6">
                {{ $editingId ? __('plans.edit_plan') : __('plans.new_plan') }}
            </h3>

            <form wire:submit="save" class="space-y-4">
                {{-- Name --}}
                <div>
                    <label class="text-xs font-bold text-heading block mb-1">{{ __('common.name') }}</label>
                    <input type="text" wire:model.blur="name" class="input-field w-full" placeholder="Ex: Pro Plus">
                    @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Slug --}}
                <div>
                    <label class="text-xs font-bold text-heading block mb-1">Slug</label>
                    <input type="text" wire:model.blur="slug" class="input-field w-full" placeholder="pro-plus">
                    @error('slug') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Price + Currency --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-heading block mb-1">{{ __('plans.price') }}</label>
                        <input type="number" wire:model.blur="price" class="input-field w-full" min="0">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-heading block mb-1">{{ __('plans.currency') }}</label>
                        <input type="text" wire:model.blur="currency" class="input-field w-full" placeholder="FCFA">
                    </div>
                </div>

                {{-- Billing period --}}
                <div>
                    <label class="text-xs font-bold text-heading block mb-1">{{ __('plans.billing_period') }}</label>
                    <select wire:model="billingPeriod" class="input-field w-full">
                        <option value="month">{{ __('plans.month') }}</option>
                        <option value="year">{{ __('plans.year') }}</option>
                    </select>
                </div>

                {{-- Limits --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-heading block mb-1">{{ __('plans.max_projects') }}</label>
                        <input type="number" wire:model.blur="maxProjects" class="input-field w-full" min="-1">
                        <p class="text-[9px] text-muted mt-0.5">-1 = {{ __('plans.unlimited') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-heading block mb-1">{{ __('plans.max_members') }}</label>
                        <input type="number" wire:model.blur="maxMembers" class="input-field w-full" min="-1">
                        <p class="text-[9px] text-muted mt-0.5">-1 = {{ __('plans.unlimited') }}</p>
                    </div>
                </div>

                {{-- Features --}}
                <div>
                    <label class="text-xs font-bold text-heading block mb-2">{{ __('plans.features') }}</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($allFeatures as $feature)
                            <label class="flex items-center gap-2 text-xs text-body cursor-pointer">
                                <input type="checkbox" wire:model="features" value="{{ $feature }}"
                                       class="rounded border-gray-300 text-accent focus:ring-accent">
                                {{ $feature }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Options --}}
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 text-xs text-body cursor-pointer">
                        <input type="checkbox" wire:model="isDefault" class="rounded border-gray-300 text-accent focus:ring-accent">
                        {{ __('plans.default') }}
                    </label>
                    <label class="flex items-center gap-2 text-xs text-body cursor-pointer">
                        <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-accent focus:ring-accent">
                        {{ __('common.active') }}
                    </label>
                </div>

                {{-- Sort order --}}
                <div>
                    <label class="text-xs font-bold text-heading block mb-1">{{ __('plans.sort_order') }}</label>
                    <input type="number" wire:model.blur="sortOrder" class="input-field w-full" min="0">
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border-light">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-bold text-muted hover:text-heading transition-colors">
                        {{ __('common.cancel') }}
                    </button>
                    <button type="submit" class="px-4 py-2 bg-accent text-white text-xs font-bold rounded-xl hover:bg-accent/90 transition-colors">
                        {{ $editingId ? __('common.save') : __('common.create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
