<!-- Section de la barre de progression -->
@include('livewire.v-beta.sub-activity.include.progres-bar')

@if($activity->subActivities->count() > 0)
    <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <i class="fas fa-hand-holding-usd mr-3 text-blue-600"></i> Sous Activites
        </h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Nom</th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Date de Debut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Date de Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Responsable</th>
                        @can('create', $activity)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Action</th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($activity->subActivities as $subActivity)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700
                            {{ $subActivity->is_milestone ? 'bg-yellow-100 dark:bg-yellow-800' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $subActivity->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $subActivity->start_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $subActivity->end_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                @if ($subActivity->responsibleUser)
                                    {{ $subActivity->responsibleUser->name }}
                                @else
                                    Non assigné
                                @endif
                            </td>
                            {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $subActivity->status }}</td> --}}
                            @can('create', $activity)
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-ui.select wire:model.live="subActivityStatuses.{{ $subActivity->id }}" size="sm" class="w-40">
                                    @foreach($this->activityStatuses as $status)
                                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                    @endforeach
                                </x-ui.select>
                                <div class="mt-1">
                                    @php $subStatus = $subActivity->status; @endphp
                                    <x-ui.badge :variant="$subStatus?->color() ?? 'slate'" size="xs">
                                        {{ $subStatus?->label() ?? $subActivity->status }}
                                    </x-ui.badge>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <x-ui.button wire:click="openModalForSubActivity('{{ $subActivity->id }}')" variant="ghost" size="sm" icon="edit" />
                                </div>
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
    
@else
    <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
            <i class="fas fa-hand-holding-usd mr-3 text-blue-600"></i> Ressources allouées
        </h2>
        <p class="text-gray-700 dark:text-gray-300">Aucune ressource n'a encore été ajoutée à cette activité.</p>
    </div>
@endif