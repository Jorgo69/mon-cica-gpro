<td class="px-6 py-4 whitespace-nowrap">
    <select 
        wire:model.change="projectStatuses.{{ $project->id }}"
        class="text-xs rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
    >
        <option value="" @selected(true)>{{ $project->status }}</option>
        @foreach($projectTypes as $type)
            <option value="{{ $type }}" @selected($project->status === $type)>{{ $type  }}</option>
        @endforeach
    </select>
</td>