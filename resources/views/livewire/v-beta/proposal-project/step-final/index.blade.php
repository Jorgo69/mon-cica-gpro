<div class="space-y-8">
    <div class="px-6 py-5 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800 rounded-[2rem] flex gap-4 shadow-sm">
        <x-lucide-alert-circle class="w-6 h-6 text-warning flex-shrink-0 mt-0.5" />
        <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed">
            <span class="font-black uppercase tracking-widest block mb-1">Derniere etape de verification</span>
            Une fois soumis, votre projet passera au statut <span class="font-black text-amber-900 dark:text-amber-100 uppercase">Brouillon</span>.
            Le systeme va orchestrer la creation automatique du cadre logique, des objectifs et des activites planifiees.
        </p>
    </div>

    {{-- KEY METRICS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Objectifs', 'val' => count($specificObjectives), 'variant' => 'primary', 'icon' => 'target'],
                ['label' => 'Resultats', 'val' => count($expectedResults), 'variant' => 'success', 'icon' => 'check-square'],
                ['label' => 'Activites', 'val' => count($activities), 'variant' => 'accent', 'icon' => 'list-checks'],
                ['label' => 'Fichiers', 'val' => count($existingDocuments) + count($uploadedDocuments), 'variant' => 'slate', 'icon' => 'paperclip'],
            ];
        @endphp
        @foreach($stats as $stat)
            <x-ui.stat-card :value="$stat['val']" :label="$stat['label']" :icon="$stat['icon']" :variant="$stat['variant']" />
        @endforeach
    </div>

    {{-- INFORMATIONS GENERALES --}}
    <x-ui.section title="Informations generales" icon="info" :noPadding="false">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-1.5">Titre du Projet</span>
                    <p class="text-base font-bold text-heading leading-relaxed">{{ $projectTitle ?: 'Non defini' }}</p>
                </div>
                <div>
                    <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-2">Code & Type</span>
                    <div class="flex flex-wrap gap-2">
                        <x-ui.badge variant="accent" size="md">{{ $projectCode ?: 'N/A' }}</x-ui.badge>
                        <x-ui.badge variant="slate" size="md">{{ $allProjectTypes->where('id', $selectedProjectTypeId)->first()->name ?? 'N/A' }}</x-ui.badge>
                    </div>
                </div>
                @if($projectShortTitle)
                <div>
                    <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-1.5">Titre abrege</span>
                    <p class="text-sm font-bold text-body">{{ $projectShortTitle }}</p>
                </div>
                @endif
            </div>
            <div class="space-y-4">
                <div>
                    <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-2">Periode d'execution</span>
                    <div class="flex items-center gap-4">
                        <div class="flex-1 p-4 bg-surface rounded-2xl border border-border-light">
                            <span class="block text-[8px] text-muted uppercase font-black mb-1">Debut</span>
                            <span class="text-sm font-bold text-body">{{ $projectStartDate ? \Carbon\Carbon::parse($projectStartDate)->format('d/m/Y') : '...' }}</span>
                        </div>
                        <x-lucide-arrow-right class="w-4 h-4 text-muted flex-shrink-0" />
                        <div class="flex-1 p-4 bg-surface rounded-2xl border border-border-light">
                            <span class="block text-[8px] text-muted uppercase font-black mb-1">Fin</span>
                            <span class="text-sm font-bold text-body">{{ $projectEndDate ? \Carbon\Carbon::parse($projectEndDate)->format('d/m/Y') : '...' }}</span>
                        </div>
                    </div>
                </div>
                @if($projectStartDate && $projectEndDate)
                <div>
                    <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-1.5">Duree estimee</span>
                    <p class="text-sm font-bold text-accent">{{ (int) \Carbon\Carbon::parse($projectStartDate)->diffInMonths(\Carbon\Carbon::parse($projectEndDate)) }} mois</p>
                </div>
                @endif
            </div>
        </div>
    </x-ui.section>

    {{-- CONTEXTE --}}
    @if($contextDescription || $problemAnalysis || $strategy || $justification)
    <x-ui.section title="Contexte & Analyse" icon="book-open" :noPadding="false">
        <div class="space-y-6">
            @foreach([
                ['label' => 'Contexte', 'value' => $contextDescription],
                ['label' => 'Analyse du probleme', 'value' => $problemAnalysis],
                ['label' => 'Strategie', 'value' => $strategy],
                ['label' => 'Justification', 'value' => $justification],
            ] as $field)
                @if(!empty($field['value']))
                <div>
                    <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-2">{{ $field['label'] }}</span>
                    <div class="text-xs text-body leading-relaxed prose prose-sm max-w-none dark:prose-invert">{!! clean($field['value']) !!}</div>
                </div>
                @endif
            @endforeach
        </div>
    </x-ui.section>
    @endif

    {{-- CADRE LOGIQUE --}}
    @if(!empty($initialLogicalFramework['general_objective']))
    <x-ui.section title="Cadre logique" icon="target" :noPadding="false">
        <div class="space-y-6">
            <div>
                <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-2">Objectif general</span>
                <p class="text-sm font-bold text-heading">{{ $initialLogicalFramework['general_objective'] }}</p>
            </div>

            @if(count($specificObjectives) > 0)
            <div>
                <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-3">Objectifs specifiques ({{ count($specificObjectives) }})</span>
                <div class="space-y-2">
                    @foreach($specificObjectives as $i => $obj)
                        @if(!empty($obj['description']))
                        <div class="flex gap-3 p-3 bg-surface/50 rounded-xl border border-border-light">
                            <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-[10px] font-black">{{ $i + 1 }}</span>
                            <p class="text-xs text-body leading-relaxed">{{ $obj['description'] }}</p>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </x-ui.section>
    @endif

    {{-- RESULTATS ATTENDUS --}}
    @if(count($expectedResults) > 0)
    <x-ui.section title="Resultats attendus ({{ count($expectedResults) }})" icon="check-square" :noPadding="false">
        <div class="space-y-2">
            @foreach($expectedResults as $i => $result)
                @if(!empty($result['description']))
                <div class="flex gap-3 p-3 bg-surface/50 rounded-xl border border-border-light">
                    <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-success/10 text-success flex items-center justify-center text-[10px] font-black">R{{ $i + 1 }}</span>
                    <div class="text-xs text-body leading-relaxed prose prose-sm max-w-none dark:prose-invert">{!! clean($result['description']) !!}</div>
                </div>
                @endif
            @endforeach
        </div>
    </x-ui.section>
    @endif

    {{-- ACTIVITES --}}
    @if(count($activities) > 0)
    <x-ui.section title="Plan d'action ({{ count($activities) }})" icon="list-checks" :noPadding="false">
        <div class="space-y-3">
            @foreach($activities as $i => $act)
                <div class="p-4 bg-surface/50 rounded-xl border border-border-light">
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-lg bg-accent/10 text-accent flex items-center justify-center text-[10px] font-black">A{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-heading">{{ $act['description'] ?? 'Sans description' }}</p>
                            <div class="flex flex-wrap gap-3 mt-2">
                                @if(!empty($act['responsible_user_id']))
                                    @php $responsible = $users->firstWhere('id', $act['responsible_user_id']); @endphp
                                    @if($responsible)
                                    <span class="inline-flex items-center gap-1 text-[10px] text-subtle">
                                        <x-lucide-user class="w-3 h-3" /> {{ $responsible->name }}
                                    </span>
                                    @endif
                                @endif
                                @if(!empty($act['start_date']) && !empty($act['end_date']))
                                <span class="inline-flex items-center gap-1 text-[10px] text-subtle">
                                    <x-lucide-calendar class="w-3 h-3" />
                                    {{ \Carbon\Carbon::parse($act['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($act['end_date'])->format('d/m/Y') }}
                                </span>
                                @endif
                                @if(!empty($act['budget']) && $act['budget'] > 0)
                                <span class="inline-flex items-center gap-1 text-[10px] text-accent font-bold">
                                    <x-lucide-banknote class="w-3 h-3" /> {{ number_format($act['budget'], 0, ',', ' ') }} CFA
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @php $totalBudget = collect($activities)->sum('budget'); @endphp
        @if($totalBudget > 0)
        <div class="mt-4 pt-4 border-t border-border-light flex justify-end">
            <div class="p-4 bg-accent/5 rounded-2xl border border-accent/20">
                <span class="text-[9px] font-black text-muted uppercase tracking-[0.2em] block mb-1">Budget total estime</span>
                <span class="text-lg font-black text-accent">{{ number_format($totalBudget, 0, ',', ' ') }} CFA</span>
            </div>
        </div>
        @endif
    </x-ui.section>
    @endif

    {{-- CHAMPS DYNAMIQUES --}}
    @php
        $hasDynamicValues = false;
        foreach ($dynamicFieldValues as $value) { if (!empty($value)) { $hasDynamicValues = true; break; } }
    @endphp

    @if ($hasDynamicValues)
    <x-ui.section title="Details specifiques" icon="puzzle" :noPadding="false">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($dynamicFormFields as $section => $fields)
                @foreach ($fields as $field)
                    @if (isset($dynamicFieldValues[$field['field_name']]))
                        @php
                            $val = $dynamicFieldValues[$field['field_name']];
                            if (is_array($val)) $val = implode(', ', $val);
                        @endphp
                        @if(!empty($val))
                        <div class="p-4 bg-surface/50 dark:bg-surface-alt/20 rounded-2xl border border-border-light/50">
                            <span class="text-[9px] font-black text-muted block tracking-tight uppercase mb-1">{{ $field['question_text'] }}</span>
                            <p class="text-xs font-bold text-subtle mt-0.5 italic">{{ $val }}</p>
                        </div>
                        @endif
                    @endif
                @endforeach
            @endforeach
        </div>
    </x-ui.section>
    @endif

    {{-- DOCUMENTS --}}
    @if(count($existingDocuments) + count($uploadedDocuments) > 0)
    <x-ui.section title="Documents joints ({{ count($existingDocuments) + count($uploadedDocuments) }})" icon="paperclip" :noPadding="false">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($existingDocuments as $doc)
            <div class="flex items-center gap-3 p-3 bg-surface/50 rounded-xl border border-border-light">
                <x-lucide-file-text class="w-4 h-4 text-muted flex-shrink-0" />
                <span class="text-xs font-bold text-body truncate">{{ $doc['file_name'] ?? $doc->file_name }}</span>
            </div>
            @endforeach
            @foreach($uploadedDocuments as $file)
            <div class="flex items-center gap-3 p-3 bg-accent/5 rounded-xl border border-accent/20">
                <x-lucide-upload-cloud class="w-4 h-4 text-accent flex-shrink-0" />
                <span class="text-xs font-bold text-body truncate">{{ $file->getClientOriginalName() }}</span>
                <span class="text-[9px] text-muted ml-auto">{{ round($file->getSize() / 1024 / 1024, 2) }} MB</span>
            </div>
            @endforeach
        </div>
    </x-ui.section>
    @endif
</div>
