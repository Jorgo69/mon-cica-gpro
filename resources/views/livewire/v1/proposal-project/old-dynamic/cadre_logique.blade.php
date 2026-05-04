{{-- Champs dynamiques pour cette section --}}
@if (isset($dynamicFormFields['cadre_logique']))
    <div class="mt-6 border-t pt-4 border-border">
        <h3 class="text-lg font-semibold mb-3 text-heading">Informations Complémentaires</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($dynamicFormFields['cadre_logique'] as $field)
                <div>
                    <label for="dynamic-{{ $field['field_name'] }}" class="block text-sm font-medium text-body">
                        {{ $field['question_text'] }} @if($field['is_required']) <span class="text-error">*</span> @endif
                    </label>
                    @if($field['input_type'] === 'textarea')
                        <textarea id="dynamic-{{ $field['field_name'] }}" wire:model.defer="dynamicFieldValues.{{ $field['field_name'] }}" 
                                    rows="3" class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent"></textarea>
                    @elseif($field['input_type'] === 'select')
                        <select id="dynamic-{{ $field['field_name'] }}" wire:model.defer="dynamicFieldValues.{{ $field['field_name'] }}" 
                                class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent">
                            <option value="">Sélectionner une option</option>
                            @foreach(explode(',', $field['options']) as $option)
                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="{{ $field['input_type'] }}" id="dynamic-{{ $field['field_name'] }}" wire:model.defer="dynamicFieldValues.{{ $field['field_name'] }}" 
                                class="mt-1 block w-full rounded-md shadow-sm border-border bg-card text-heading focus:border-accent focus:ring-accent">
                    @endif
                    @error('dynamicFieldValues.' . $field['field_name']) <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            @endforeach
        </div>
    </div>
@endif