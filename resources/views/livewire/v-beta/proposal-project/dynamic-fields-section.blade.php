{{-- Après vos champs fixes --}}
@if(!empty($dynamicFormFields))
    @foreach($dynamicFormFields as $section => $fields)
        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 animate-fadeIn">
            <h3 class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-4">
                {{ ucfirst(str_replace('_', ' ', $section)) }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($fields as $field)
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">
                            {{ $field['question_text'] }}
                            @if($field['is_required']) <span class="text-rose-500">*</span> @endif
                        </label>
                        
                        {{-- Logique pour les différents types d'input --}}
                        @if($field['input_type'] === 'textarea')
                            <textarea wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                      class="block w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                      rows="3"></textarea>
                        
                        @elseif($field['input_type'] === 'select')
                            @php
                                $options = is_array($field['options']) ? $field['options'] : [];
                            @endphp

                            @if(isset($field['render_as']) && $field['render_as'] === 'select')
                                <select wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                        class="block w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    <option value="">Sélectionner</option>
                                    @foreach($options as $option)
                                        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                            
                            @elseif(isset($field['render_as']) && $field['render_as'] === 'radio')
                                <div class="space-y-2 mt-2">
                                    @foreach($options as $option)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" value="{{ $option['value'] }}" wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                                   class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                            <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-primary transition-colors">{{ $option['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            
                            @elseif(isset($field['render_as']) && $field['render_as'] === 'checkbox')
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    @foreach($options as $option)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" value="{{ $option['value'] }}" wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                                   class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                                            <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-primary transition-colors">{{ $option['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        
                        @else
                            <input type="{{ $field['input_type'] }}"
                                   wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                   class="block w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        @endif
                        
                        @error('dynamicFieldValues.' . $field['field_name'])
                            <span class="text-[10px] text-rose-500 italic mt-0.5">{{ $message }}</span>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endif