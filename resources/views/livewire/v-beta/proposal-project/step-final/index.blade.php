<div class="space-y-6">
    <div class="px-5 py-4 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100/50 dark:border-amber-800 rounded-3xl flex gap-3 italic">
        <i class="fas fa-exclamation-circle text-amber-500 mt-1"></i>
        <p class="text-[11px] text-amber-700 dark:text-amber-300 leading-relaxed">
            <span class="font-black uppercase tracking-tighter">Note importante :</span>
            Une fois soumis, votre projet passera au statut <span class="font-bold">"Brouillon"</span>. Assurez-vous d'avoir bien relu toutes les étapes avant de finaliser.
        </p>
    </div>

    {{-- KEY METRICS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Objectifs', 'val' => count($specificObjectives), 'color' => 'text-indigo-600'],
                ['label' => 'Résultats', 'val' => count($expectedResults), 'color' => 'text-emerald-500'],
                ['label' => 'Activités', 'val' => count($activities), 'color' => 'text-amber-500'],
                ['label' => 'Fichiers', 'val' => count($existingDocuments) + count($uploadedDocuments), 'color' => 'text-gray-500'],
            ];
        @endphp
        @foreach($stats as $stat)
            <div class="p-4 bg-gray-50/50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-2xl text-center shadow-sm">
                <span class="block text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">{{ $stat['label'] }}</span>
                <span class="text-xl font-black {{ $stat['color'] }}">{{ $stat['val'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- SUMMARY DATA --}}
    <div class="p-8 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-3xl space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <span class="text-[9px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-[0.2em] block mb-1">Titre du Projet</span>
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100 leading-relaxed">{{ $projectTitle ?: 'Non défini' }}</p>
                </div>
                <div>
                    <span class="text-[9px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-[0.2em] block mb-1.5">Code & Type</span>
                    <div class="flex flex-wrap gap-2">
                        <span class="text-[10px] font-mono font-bold text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-1 rounded-lg">{{ $projectCode ?: 'N/A' }}</span>
                        <span class="text-[10px] font-bold text-gray-500 bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-lg">{{ $allProjectTypes->where('id', $selectedProjectTypeId)->first()->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <span class="text-[9px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-[0.2em] block mb-1.5">Période d'exécution</span>
                    <div class="flex items-center gap-3">
                        <div class="flex-1 p-3 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border border-gray-100/50 dark:border-gray-700/50">
                            <span class="block text-[8px] text-gray-400 uppercase font-black mb-0.5">Début</span>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $projectStartDate ?: '...' }}</span>
                        </div>
                        <div class="flex-1 p-3 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border border-gray-100/50 dark:border-gray-700/50">
                            <span class="block text-[8px] text-gray-400 uppercase font-black mb-0.5">Fin</span>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $projectEndDate ?: '...' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $hasDynamicValues = false;
            foreach ($dynamicFieldValues as $value) { if (!empty($value)) { $hasDynamicValues = true; break; } }
        @endphp
        
        @if ($hasDynamicValues)
            <div class="pt-8 border-t border-gray-50 dark:border-gray-800">
                <h4 class="text-[9px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-[0.2em] mb-4">Détails spécifiques</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($dynamicFormFields as $section => $fields)
                        @foreach ($fields as $field)
                            @if (isset($dynamicFieldValues[$field['field_name']]))
                                @php
                                    $val = $dynamicFieldValues[$field['field_name']];
                                    if (is_array($val)) $val = implode(', ', $val);
                                @endphp
                                @if(!empty($val))
                                    <div class="p-4 bg-gray-50/30 dark:bg-gray-800/20 rounded-2xl border border-gray-100/50 dark:border-gray-800/50">
                                        <span class="text-[9px] font-black text-gray-400 block tracking-tight uppercase mb-0.5">{{ $field['question_text'] }}</span>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400 mt-0.5 italic truncate">{{ $val }}</p>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>