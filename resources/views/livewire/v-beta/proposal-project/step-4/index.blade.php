{{-- Fenêtre 4 : Résultats Attendus --}}
<div x-show="currentStep === 4" class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Résultats Attendus</h2>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Décrivez les résultats concrets que le projet doit atteindre.</p>

    <div class="space-y-4">
        @foreach($expectedResults as $index => $result)
            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg relative">
                {{-- <button type="button" wire:click="removeExpectedResult({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-lg">&times;</button> --}}

                <div>
                    <input type="hidden" wire:model="expectedResults.{{ $index }}.id">

                    <label for="expected-result-description-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Description du Résultat <span class="text-red-500">*</span>
                    </label>

                    {{-- Textarea ignoré par Livewire, géré par Summernote --}}
                    <div wire:ignore>
                        <textarea
                            id="expected-result-description-{{ $index }}"
                            class="summernote"
                            data-field="expectedResults.{{ $index }}.description"
                            placeholder="Ex: 500 femmes enceintes ont accès à des consultations prénatales régulières."
                        >{!! old('expectedResults.'.$index.'.description', $result['description'] ?? '') !!}</textarea>

                        @error('expectedResults.' . $index . '.description')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        @endforeach

        {{-- <button type="button" wire:click="addExpectedResult"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold dark:bg-blue-600 dark:text-gray-100 dark:hover:bg-blue-700">
            Ajouter un Résultat
        </button> --}}
    </div>
    
</div>

{{-- <div class="space-y-4">
        @foreach($expectedResults as $index => $result)
            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg relative">
                <button type="button" wire:click="removeExpectedResult({{ $index }})" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-lg">&times;</button>
                <div>
                    <input type="hidden" wire:model="expectedResults.{{ $index }}.id">
                    <label for="expected-result-description-{{ $index }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description du Résultat <span class="text-red-500">*</span></label>
                    <textarea id="expected-result-description-{{ $index }}" wire:model.defer="expectedResults.{{ $index }}.description" rows="2"
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Ex: 500 femmes enceintes ont accès à des consultations prénatales régulières."></textarea>
                    @error('expectedResults.' . $index . '.description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        @endforeach

        <button type="button" wire:click="addExpectedResult" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-semibold dark:bg-blue-600 dark:text-gray-100 dark:hover:bg-blue-700">
            Ajouter un Résultat
        </button>
</div> --}}