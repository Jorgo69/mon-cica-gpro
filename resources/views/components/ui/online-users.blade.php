@props(['orgId' => null])

@if($orgId)
<div
    x-data="{
        users: [],
        init() {
            window.addEventListener('echo-ready', () => {
                if (!window.Echo) return;

                window.Echo.join('presence.org.{{ $orgId }}')
                    .here((members) => { this.users = members; })
                    .joining((member) => { this.users.push(member); })
                    .leaving((member) => {
                        this.users = this.users.filter(u => u.id !== member.id);
                    });
            });
        }
    }"
    x-show="users.length > 0"
    x-cloak
    class="flex items-center gap-1"
>
    <template x-for="user in users.slice(0, 5)" :key="user.id">
        <span
            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 dark:bg-green-900 text-xs font-medium text-green-800 dark:text-green-200 ring-2 ring-white dark:ring-gray-800"
            x-text="user.name.charAt(0).toUpperCase()"
            :title="user.name + ' (en ligne)'"
        ></span>
    </template>
    <span
        x-show="users.length > 5"
        class="text-xs text-muted"
        x-text="'+' + (users.length - 5)"
    ></span>
</div>
@endif
