{{-- Champs dynamiques pour cette section --}}
@if (isset($dynamicFormFields['budget_previsionnel']))
    <div class="mt-6 border-t pt-4 border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">Informations Complémentaires</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($dynamicFormFields['budget_previsionnel'] as $field)
                <div>
                    <label for="dynamic-{{ $field['field_name'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $field['question_text'] }} @if($field['is_required']) <span class="text-red-500">*</span> @endif
                    </label>
                    @if($field['input_type'] === 'textarea')
                        <textarea id="dynamic-{{ $field['field_name'] }}" wire:model.defer="dynamicFieldValues.{{ $field['field_name'] }}" 
                                    rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"></textarea>
                    @elseif($field['input_type'] === 'select')
                        <select id="dynamic-{{ $field['field_name'] }}" wire:model.defer="dynamicFieldValues.{{ $field['field_name'] }}" 
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner une option</option>
                            @foreach(explode(',', $field['options']) as $option)
                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="{{ $field['input_type'] }}" id="dynamic-{{ $field['field_name'] }}" wire:model.defer="dynamicFieldValues.{{ $field['field_name'] }}" 
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                    @endif
                    @error('dynamicFieldValues.' . $field['field_name']) <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            @endforeach
        </div>
    </div>
@endif