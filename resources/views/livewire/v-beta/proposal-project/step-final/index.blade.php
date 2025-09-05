<div x-show="currentStep === 6" class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Finalisation de la Proposition</h2>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Veuillez vérifier toutes les informations avant de soumettre votre proposition de projet pour validation.</p>

    <div class="p-6 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg text-yellow-700 dark:text-yellow-200">
        <p class="font-medium">Une fois soumis, votre projet passera au statut "Brouillon" et sera visible par les validateurs.</p>
    </div>

    {{-- Résumé des données saisies --}}
    <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Résumé du Projet</h3>
        <div class="space-y-2 text-gray-700 dark:text-gray-300">
            <p><strong>Titre:</strong> {{ $projectTitle }}</p>
            <p><strong>Code:</strong> {{ $projectCode }}</p>
            <p><strong>Type:</strong> {{ $allProjectTypes->where('id', $selectedProjectTypeId)->first()->name ?? 'N/A' }}</p>
            <p><strong>Période:</strong> Du {{ $projectStartDate }} au {{ $projectEndDate }}</p>
            
            @if ($contextDescription)
                <p><strong>Contexte:</strong> {!! Str::limit($contextDescription, 100) !!}</p>
            @endif

            @if (!empty($initialLogicalFramework['general_objective']))
                <p><strong>Objectif Général:</strong> {{ Str::limit($initialLogicalFramework['general_objective'], 100) }}</p>
            @endif

            @if (count($specificObjectives) > 0)
                <p><strong>Objectifs Spécifiques:</strong> {{ count($specificObjectives) }}</p>
            @endif

            @if (count($expectedResults) > 0)
                <p><strong>Résultats Attendus:</strong> {!! count($expectedResults) !!}</p>
            @endif

            @if (count($activities) > 0)
                <p><strong>Activités:</strong> {{ count($activities) }}</p>
            @endif

            {{-- @if (count($budgets) > 0)
                <p><strong>Lignes Budgétaires:</strong> {{ count($budgets) }}</p>
            @endif --}}

            @php
                $hasDynamicValues = false;
                foreach ($dynamicFieldValues as $value) {
                    if (!empty($value)) {
                        $hasDynamicValues = true;
                        break;
                    }
                }
            @endphp
            @if ($hasDynamicValues)
                <h4 class="font-semibold mt-4">Champs Dynamiques Saisis:</h4>
                <ul class="list-disc ml-5">
                    @foreach ($dynamicFormFields as $section => $fields)
                        @foreach ($fields as $field)
                            @if (isset($dynamicFieldValues[$field['field_name']]))
                                @php
                                    $value = $dynamicFieldValues[$field['field_name']];
                                    // Convertir les tableaux (checkboxes) en une chaîne de caractères
                                    if (is_array($value)) {
                                        $value = implode(', ', $value);
                                    }
                                @endphp
                                <li><strong>{{ $field['question_text'] }}:</strong> {{ Str::limit($value, 70) }}</li>
                            @endif
                        @endforeach
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>