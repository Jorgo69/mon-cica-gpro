<x-ui.page-layout maxWidth="900px">
    <form wire:submit.prevent="save" class="space-y-6">

        {{-- Page Header --}}
        <x-ui.page-header
            :title="isset($projectTypeId) ? __('admin.types.edit_title') : __('admin.types.new_title')"
            :subtitle="__('admin.types.form_subtitle')" />

        {{-- Key Information --}}
        <x-ui.section :title="__('admin.types.key_info')" icon="info">
            <div class="space-y-4">
                <x-ui.input wire:model.defer="name" name="name" :label="__('admin.types.name_label')" required
                            :error="$errors->first('name')" :placeholder="__('admin.types.name_placeholder')" />
                
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-subtle uppercase tracking-wider ml-1">{{ __('admin.types.description') }}</label>
                    <textarea wire:model.defer="description" rows="3" 
                              class="block w-full border-border bg-card text-heading rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 px-4 transition-all"></textarea>
                </div>

                <x-ui.select wire:model.defer="category" name="category" :label="__('admin.types.category')" icon="tag">
                    <option value="">------</option>
                    @forelse ($projectCategories as $projectCategory)
                        <option value="{{ $projectCategory->name }}">{{ $projectCategory->name }}</option>
                    @empty
                        <option value="">{{ __('admin.types.no_category') }}</option>
                    @endforelse
                </x-ui.select>
            </div>
        </x-ui.section>

        {{-- Dynamic Fields --}}
        <x-ui.section :title="__('admin.types.dynamic_fields')" icon="puzzle">
            <div class="space-y-4">
                @foreach ($fields as $index => $field)
                    <div wire:key="field-{{ $index }}" class="p-4 border border-border rounded-xl bg-surface/50 dark:bg-surface-alt/30 relative">
                        <button type="button" wire:click="removeField({{ $index }})" class="absolute top-3 right-3 p-1 rounded-lg text-muted hover:text-error hover:bg-error/5 transition-all">
                            <x-lucide-x class="w-4 h-4" />
                        </button>

                        <div class="grid grid-cols-1 gap-4 pr-8">
                            <x-ui.input wire:model.defer="fields.{{ $index }}.question_text" :label="__('admin.types.question_label')" required
                                        :error="$errors->first('fields.' . $index . '.question_text')" />

                            <x-ui.select wire:model.live="fields.{{ $index }}.input_type" :label="__('admin.types.field_type')" icon="type">
                                <option value="text">{{ __('admin.types.type_text') }}</option>
                                <option value="textarea">{{ __('admin.types.type_textarea') }}</option>
                                <option value="select">{{ __('admin.types.type_select') }}</option>
                                <option value="date">{{ __('admin.types.type_date') }}</option>
                                <option value="number">{{ __('admin.types.type_number') }}</option>
                            </x-ui.select>

                            <x-ui.input wire:model.defer="fields.{{ $index }}.field_name" :label="__('admin.types.field_name')" required
                                        :error="$errors->first('fields.' . $index . '.field_name')" />

                            @if ($field['input_type'] === 'select')
                                <x-ui.select wire:model.defer="fields.{{ $index }}.render_as" :label="__('admin.types.render_type')" icon="layout" required
                                             :error="$errors->first('fields.' . $index . '.render_as')">
                                    <option value="">---</option>
                                    <option value="select">{{ __('admin.types.render_select') }}</option>
                                    <option value="radio">{{ __('admin.types.render_radio') }}</option>
                                    <option value="checkbox">{{ __('admin.types.render_checkbox') }}</option>
                                </x-ui.select>

                                <div class="bg-card p-4 rounded-xl border border-border">
                                    <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-3">{{ __('admin.types.options_title') }}</p>
                                    <div class="space-y-2">
                                        @foreach ($field['options'] as $optionIndex => $option)
                                            <div class="flex items-center gap-2">
                                                <input type="text" wire:model.defer="fields.{{ $index }}.options.{{ $optionIndex }}.label" 
                                                       class="flex-1 block border-border bg-card rounded-lg shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent text-sm py-2 px-3" placeholder="{{ __('admin.types.option_label') }}">
                                                <input type="text" wire:model.defer="fields.{{ $index }}.options.{{ $optionIndex }}.value"
                                                       class="flex-1 block border-border bg-card rounded-lg shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent text-sm py-2 px-3" placeholder="{{ __('admin.types.option_value') }}">
                                                <button type="button" wire:click="removeOption({{ $index }}, {{ $optionIndex }})" class="p-1.5 rounded-lg text-muted hover:text-error hover:bg-error/5 transition-all">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" wire:click="addOption({{ $index }})" class="mt-3 w-full flex justify-center items-center gap-2 px-4 py-2.5 border border-dashed border-border rounded-xl text-sm font-semibold text-subtle hover:bg-surface transition-colors">
                                        <x-lucide-plus class="w-4 h-4" />
                                        {{ __('admin.types.add_option') }}
                                    </button>
                                    @error('fields.' . $index . '.options') <span class="text-error text-xs font-medium mt-1">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-ui.input wire:model.defer="fields.{{ $index }}.order" type="number" :label="__('admin.types.order')" required
                                            :error="$errors->first('fields.' . $index . '.order')" />
                                <x-ui.input wire:model.defer="fields.{{ $index }}.target_project_field" :label="__('admin.types.target_field')"
                                            :placeholder="__('admin.types.target_placeholder')" />
                            </div>

                            <x-ui.input wire:model.defer="fields.{{ $index }}.section" :label="__('admin.types.section')" :placeholder="__('admin.types.section_placeholder')" />

                            <div class="flex items-center gap-2">
                                <input id="is_required-{{ $index }}" wire:model.defer="fields.{{ $index }}.is_required" type="checkbox" 
                                       class="rounded border-border text-accent focus:ring-accent h-4 w-4">
                                <label for="is_required-{{ $index }}" class="text-sm font-medium text-body">{{ __('admin.types.required') }}</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" wire:click="addField" class="mt-4 w-full flex justify-center items-center gap-2 px-4 py-3 border border-dashed border-border rounded-xl text-sm font-semibold text-subtle hover:bg-surface transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                {{ __('admin.types.add_field') }}
            </button>
        </x-ui.section>

        {{-- Submit --}}
        <div class="flex justify-end">
            <x-ui.button type="submit" variant="accent" icon="save" size="lg">
                {{ __('admin.types.save') }}
            </x-ui.button>
        </div>
    </form>
</x-ui.page-layout>