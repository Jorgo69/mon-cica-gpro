<div class="space-y-8">
    <div class="px-6 py-5 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800 rounded-[2rem] flex gap-4 shadow-sm animate-pulse-subtle">
        <x-lucide-alert-circle class="w-6 h-6 text-amber-500 flex-shrink-0 mt-0.5" />
        <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed">
            <span class="font-black uppercase tracking-widest block mb-1">Dernière étape de vérification</span>
            Une fois soumis, votre projet passera au statut <span class="font-black text-amber-900 dark:text-amber-100 uppercase">Brouillon</span>. 
            Le système va orchestrer la création automatique du cadre logique, des objectifs et des activités planifiées.
        </p>
    </div>

    {{-- KEY METRICS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Objectifs', 'val' => count($specificObjectives), 'variant' => 'primary', 'icon' => 'target'],
                ['label' => 'Résultats', 'val' => count($expectedResults), 'variant' => 'success', 'icon' => 'check-square'],
                ['label' => 'Activités', 'val' => count($activities), 'variant' => 'accent', 'icon' => 'list-checks'],
                ['label' => 'Fichiers', 'val' => count($existingDocuments) + count($uploadedDocuments), 'variant' => 'slate', 'icon' => 'paperclip'],
            ];
        @endphp
        @foreach($stats as $stat)
            <x-ui.stat-card :value="$stat['val']" :label="$stat['label']" :icon="$stat['icon']" :variant="$stat['variant']" />
        @endforeach
    </div>

    {{-- SUMMARY DATA --}}
    <x-ui.section title="Résumé de la Proposition" icon="file-signature" :noPadding="false">
        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] block mb-1.5">Titre du Projet</span>
                        <p class="text-base font-bold text-slate-800 dark:text-slate-100 leading-relaxed">{{ $projectTitle ?: 'Non défini' }}</p>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] block mb-2">Code & Type</span>
                        <div class="flex flex-wrap gap-2">
                            <x-ui.badge variant="accent" size="md">{{ $projectCode ?: 'N/A' }}</x-ui.badge>
                            <x-ui.badge variant="slate" size="md">{{ $allProjectTypes->where('id', $selectedProjectTypeId)->first()->name ?? 'N/A' }}</x-ui.badge>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] block mb-2">Période d'exécution</span>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <span class="block text-[8px] text-slate-400 uppercase font-black mb-1">Début</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $projectStartDate ?: '...' }}</span>
                            </div>
                            <div class="flex-1 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                                <span class="block text-[8px] text-slate-400 uppercase font-black mb-1">Fin</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $projectEndDate ?: '...' }}</span>
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
                <div class="pt-8 border-t border-slate-50 dark:border-slate-800">
                    <h4 class="text-[9px] font-black text-slate-300 dark:text-slate-600 uppercase tracking-[0.3em] mb-4">Détails spécifiques</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($dynamicFormFields as $section => $fields)
                            @foreach ($fields as $field)
                                @if (isset($dynamicFieldValues[$field['field_name']]))
                                    @php
                                        $val = $dynamicFieldValues[$field['field_name']];
                                        if (is_array($val)) $val = implode(', ', $val);
                                    @endphp
                                    @if(!empty($val))
                                        <div class="p-4 bg-slate-50/50 dark:bg-slate-800/20 rounded-2xl border border-slate-100 dark:border-slate-800/50 group hover:border-accent transition-colors">
                                            <span class="text-[9px] font-black text-slate-400 group-hover:text-accent block tracking-tight uppercase mb-1 transition-colors">{{ $field['question_text'] }}</span>
                                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400 mt-0.5 italic truncate">{{ $val }}</p>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-ui.section>
</div>