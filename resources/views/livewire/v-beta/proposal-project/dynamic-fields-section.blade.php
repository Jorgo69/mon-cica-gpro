{{-- Après vos champs fixes --}}
@if(!empty($dynamicFormFields))
    @foreach($dynamicFormFields as $section => $fields)
        <div class="mt-6 border-t pt-4 border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">
                {{ ucfirst(str_replace('_', ' ', $section)) }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($fields as $field)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ $field['question_text'] }}
                            @if($field['is_required']) <span class="text-red-500">*</span> @endif
                        </label>
                        
                        {{-- Logique pour les différents types d'input --}}
                        @if($field['input_type'] === 'textarea')
                            <textarea wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                      class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                      rows="3"></textarea>
                        
                        @elseif($field['input_type'] === 'select')
                            @php
                                // On s'assure que les options sont un tableau valide
                                $options = is_array($field['options']) ? $field['options'] : [];
                            @endphp

                            {{-- Rendu dynamique des options en fonction de 'render_as' --}}
                            @if(isset($field['render_as']) && $field['render_as'] === 'select')
                                <select wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Sélectionner une option</option>
                                    @foreach($options as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                            
                            @elseif(isset($field['render_as']) && $field['render_as'] === 'radio')
                                <div class="mt-2 space-y-2">
                                    @foreach($options as $option)
                                        <div class="flex items-center">
                                            <input id="radio-{{ $field['field_name'] }}-{{ $option['value'] }}"
                                                   type="radio"
                                                   value="{{ $option['value'] }}"
                                                   wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="radio-{{ $field['field_name'] }}-{{ $option['value'] }}" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $option['label'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            
                            @elseif(isset($field['render_as']) && $field['render_as'] === 'checkbox')
                                <div class="mt-2 space-y-2">
                                    @foreach($options as $option)
                                        <div class="flex items-center">
                                            <input id="checkbox-{{ $field['field_name'] }}-{{ $option['value'] }}"
                                                   type="checkbox"
                                                   value="{{ $option['value'] }}"
                                                   {{-- La liaison `wire:model` est sur le tableau. Livewire ajoute/retire la valeur automatiquement. --}}
                                                   wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            <label for="checkbox-{{ $field['field_name'] }}-{{ $option['value'] }}" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $option['label'] }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        
                        {{-- Gère tous les autres types d'input (text, number, date, etc.) --}}
                        @else
                            <input type="{{ $field['input_type'] }}"
                                   wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                   class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500">
                        @endif
                        
                        @error('dynamicFieldValues.' . $field['field_name'])
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@else
    <div class="p-4 bg-yellow-100 text-yellow-800 text-sm">
        Aucun champ dynamique configuré pour ce type de projet.
        Type sélectionné: {{ $selectedProjectTypeId ?? 'Aucun' }}
    </div>
@endif

{{-- Autres sections du formulaire... --}}