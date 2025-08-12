<main class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-100 dark:bg-gray-900">
    <div class="max-w-4xl w-full space-y-8 bg-white dark:bg-gray-800 p-10 rounded-xl shadow-2xl">
        
        <h1 class="text-3xl font-extrabold text-center text-gray-900 dark:text-white">
            Détails du type de projet : {{ $projectType->name }}
        </h1>
        
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-inner">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informations Clés</h3>
            <dl class="space-y-2 text-gray-700 dark:text-gray-200">
                <div>
                    <dt class="font-medium">Nom :</dt>
                    <dd>{{ $projectType->name }}</dd>
                </div>
                <div>
                    <dt class="font-medium">Description :</dt>
                    <dd>{{ $projectType->description ?? 'Non renseignée' }}</dd>
                </div>
                <div>
                    <dt class="font-medium">Catégorie :</dt>
                    <dd>{{ $projectType->category ?? 'Non renseignée' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-inner">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Champs Dynamiques</h3>
            @if ($projectType->dynamicFields->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">Aucun champ dynamique n'est défini pour ce type de projet.</p>
            @else
                <div class="space-y-6">
                    @foreach ($projectType->dynamicFields as $field)
                        <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $field->question_text }}</h4>
                            <dl class="space-y-1 text-gray-700 dark:text-gray-200 text-sm">
                                <div>
                                    <dt class="font-medium">Nom du champ :</dt>
                                    <dd>{{ $field->field_name }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Type :</dt>
                                    <dd>{{ ucfirst($field->input_type) }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Obligatoire :</dt>
                                    <dd>{{ $field->is_required ? 'Oui' : 'Non' }}</dd>
                                </div>
                                @if ($field->input_type === 'select' && $field->options)
                                    <div>
                                        <dt class="font-medium">Options :</dt>
                                        <dd>
                                            <ul class="list-disc list-inside ml-4">
                                                @php
                                                    $options = json_decode($field->options, true);
                                                @endphp
                                                @foreach ($options as $option)
                                                    <li>Libellé: {{ $option['label'] }} / Valeur: {{ $option['value'] }}</li>
                                                @endforeach
                                            </ul>
                                        </dd>
                                    </div>
                                @endif
                                @if ($field->delimiter_start)
                                    <div>
                                        <dt class="font-medium">Délimiteurs :</dt>
                                        <dd>Début: '{{ $field->delimiter_start }}' / Fin: '{{ $field->delimiter_end }}'</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="flex justify-end mt-6">
            <a href="{{ route('admin.it.project.types.edit', ['projectTypeId' => $projectType->id]) }}" class="px-6 py-3 bg-indigo-600 text-black font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors">
                Modifier le type de projet
            </a>
        </div>
    </div>
</main>