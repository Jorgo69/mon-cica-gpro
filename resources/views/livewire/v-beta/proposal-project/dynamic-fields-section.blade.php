{{-- Après vos champs fixes --}}
@if(!empty($dynamicFormFields))
    @foreach($dynamicFormFields as $section => $fields)
        <div class="mt-8 pt-6 border-t border-border-light animate-fadeIn">
            <h3 class="text-[10px] font-black text-muted uppercase tracking-[0.2em] mb-4">
                {{ ucfirst(str_replace('_', ' ', $section)) }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($fields as $field)
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-body">
                            {{ $field['question_text'] }}
                            @if($field['is_required']) <span class="text-rose-500">*</span> @endif
                        </label>
                        
                        {{-- Logique pour les différents types d'input --}}
                        @if($field['input_type'] === 'textarea')
                            <textarea wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                      class="block w-full px-4 py-3 rounded-xl border-border bg-card text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                      rows="3"></textarea>
                        
                        @elseif($field['input_type'] === 'select')
                            @php
                                $options = is_array($field['options']) ? $field['options'] : [];
                            @endphp

                            @if(isset($field['render_as']) && $field['render_as'] === 'select')
                                <select wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                        class="block w-full px-4 py-3 rounded-xl border-border bg-card text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
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
                                                   class="w-4 h-4 text-primary border-border focus:ring-primary">
                                            <span class="text-xs text-subtle group-hover:text-primary transition-colors">{{ $option['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            
                            @elseif(isset($field['render_as']) && $field['render_as'] === 'checkbox')
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    @foreach($options as $option)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" value="{{ $option['value'] }}" wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                                   class="w-4 h-4 text-primary border-border rounded focus:ring-primary">
                                            <span class="text-xs text-subtle group-hover:text-primary transition-colors">{{ $option['label'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        
                        @else
                            <input type="{{ $field['input_type'] }}"
                                   wire:model="dynamicFieldValues.{{ $field['field_name'] }}"
                                   class="block w-full px-4 py-3 rounded-xl border-border bg-card text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
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