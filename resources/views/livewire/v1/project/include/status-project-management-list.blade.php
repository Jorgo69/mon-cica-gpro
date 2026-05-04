<td class="px-6 py-4 whitespace-nowrap">
    <select 
        wire:model.change="projectStatuses.{{ $project->id }}"
        class="text-xs rounded border-border dark:bg-surface"
    >
        <option value="" @selected(true)>{{ $project->status }}</option>
        @foreach($projectTypes as $type)
            <option value="{{ $type }}" @selected($project->status === $type)>{{ $type  }}</option>
        @endforeach
    </select>
</td>