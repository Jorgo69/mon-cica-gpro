<!-- Section de la barre de progression -->
@include('livewire.sub-activity.partials.progress-bar')

@if($subActivities->count() > 0)
    <div class="overflow-x-auto -mx-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border-light dark:border-surface-alt">
                    <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">Description</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">Début</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">Fin</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">Responsable</th>
                    <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">Statut</th>
                    @can('update', $activity)
                    <th class="px-6 py-3 text-right text-[10px] font-black text-muted uppercase tracking-widest">Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="divide-y divide-border-light dark:divide-surface-alt/50 text-body">
                @foreach($subActivities as $subActivity)
                    <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors {{ $subActivity->is_milestone ? 'bg-amber-50/50 dark:bg-amber-900/10' : '' }}">
                        <td class="px-6 py-4 font-medium text-body">
                            {{ $subActivity->description }}
                            @if($subActivity->is_milestone)
                                <x-ui.badge variant="warning" size="xs" class="ml-2">Jalon</x-ui.badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $subActivity->start_date ? \Carbon\Carbon::parse($subActivity->start_date)->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $subActivity->end_date ? \Carbon\Carbon::parse($subActivity->end_date)->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $subActivity->responsibleUser->name ?? 'Non assigné' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @can('update', $subActivity)
                                <x-ui.select wire:model.live="subActivityStatuses.{{ $subActivity->id }}" size="sm" class="w-40 inline-block mb-1">
                                    @foreach($this->activityStatuses as $status)
                                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                    @endforeach
                                </x-ui.select>
                                <br/>
                                <span class="text-[10px] text-muted">Statut actuel: {{ $subActivity->status?->label() ?? $subActivity->status }}</span>
                            @else
                                @php $subStatus = $subActivity->status; @endphp
                                <x-ui.badge :variant="$subStatus?->color() ?? 'slate'" size="sm">
                                    {{ $subStatus instanceof \App\Enums\ActivityStatus ? $subStatus->label() : ($subStatus instanceof \BackedEnum ? $subStatus->value : (string)$subStatus) }}
                                </x-ui.badge>
                            @endcan
                        </td>
                        @can('update', $activity)
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <x-ui.button wire:click="openModalForSubActivity('{{ $subActivity->id }}')" variant="ghost" size="sm" icon="edit" />
                        </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <x-ui.empty-state icon="git-branch" title="Aucune sous-activité" description="Il n'y a pas encore de sous-activités associées à cette activité." />
@endif