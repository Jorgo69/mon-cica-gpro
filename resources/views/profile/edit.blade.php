<x-app-layout>
    <x-ui.page-layout>

        <x-ui.page-header title="Mon Profil" subtitle="Gerez vos informations personnelles et la securite de votre compte" />

        <div class="space-y-6">
            {{-- Informations du profil --}}
            {{-- Avatar --}}
            <x-ui.section title="Avatar" icon="image" :noPadding="false">
                <div x-data="{
                    current: '{{ $user->getMeta('avatar', '') }}',
                    showPicker: false,
                    saving: false,
                    async selectAvatar(key) {
                        this.current = key;
                        this.saving = true;
                        await fetch('/api/user-meta', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                            body: JSON.stringify({ key: 'avatar', value: key })
                        });
                        this.saving = false;
                        this.showPicker = false;
                    },
                    removeAvatar() {
                        this.current = '';
                        fetch('/api/user-meta', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                            body: JSON.stringify({ key: 'avatar', value: '' })
                        });
                    }
                }">
                    <div class="flex items-center gap-6">
                        {{-- Current avatar --}}
                        <div class="relative">
                            <template x-if="current">
                                <img :src="'/avatars/' + current + '.svg'" class="w-20 h-20 rounded-full border-2 border-accent/20" />
                            </template>
                            <template x-if="!current">
                                <div class="w-20 h-20 rounded-full bg-accent/10 text-accent flex items-center justify-center text-lg font-black uppercase">
                                    {{ collect(explode(' ', $user->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}
                                </div>
                            </template>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-heading">{{ $user->name }}</p>
                            <p class="text-xs text-subtle">{{ $user->email }}</p>
                            <div class="flex gap-2 mt-2">
                                <button type="button" @click="showPicker = !showPicker"
                                    class="px-3 py-1.5 bg-surface text-xs font-bold text-body rounded-lg border border-border-light hover:border-accent hover:text-accent transition-all">
                                    Changer l'avatar
                                </button>
                                <button type="button" x-show="current" @click="removeAvatar()"
                                    class="px-3 py-1.5 text-xs font-bold text-subtle rounded-lg hover:text-error transition-all">
                                    Retirer
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Avatar picker --}}
                    <div x-show="showPicker" x-collapse x-cloak class="mt-6 pt-6 border-t border-border-light">
                        <p class="text-xs font-bold text-muted uppercase tracking-wider mb-3">Choisir un avatar</p>
                        <div class="grid grid-cols-8 sm:grid-cols-12 gap-3">
                            @for($i = 1; $i <= 24; $i++)
                                <button type="button" @click="selectAvatar('avatar-{{ $i }}')"
                                    :class="current === 'avatar-{{ $i }}' ? 'ring-2 ring-accent ring-offset-2 scale-110' : 'hover:scale-105 opacity-80 hover:opacity-100'"
                                    class="w-10 h-10 rounded-full overflow-hidden transition-all">
                                    <img src="{{ asset("avatars/avatar-{$i}.svg") }}" class="w-full h-full" />
                                </button>
                            @endfor
                        </div>
                        <p x-show="saving" class="text-xs text-accent font-bold mt-2">Enregistrement...</p>
                    </div>
                </div>
            </x-ui.section>

            {{-- Informations personnelles --}}
            <x-ui.section title="Informations personnelles" icon="user" :noPadding="false">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Nom complet</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all" />
                            @error('name') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Adresse email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all" />
                            @error('email') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="telephone" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Telephone</label>
                            <input id="telephone" name="telephone" type="text" value="{{ old('telephone', $user->telephone) }}"
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all" />
                        </div>

                        <div>
                            <label for="sexe" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Sexe</label>
                            <select id="sexe" name="sexe"
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                                <option value="">Non renseigne</option>
                                <option value="Homme" {{ $user->sexe === 'Homme' ? 'selected' : '' }}>Homme</option>
                                <option value="Femme" {{ $user->sexe === 'Femme' ? 'selected' : '' }}>Femme</option>
                            </select>
                        </div>

                        <div>
                            <label for="pays" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Pays</label>
                            <input id="pays" name="pays" type="text" value="{{ old('pays', $user->pays) }}"
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all" />
                        </div>

                        <div>
                            <label for="ville" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Ville</label>
                            <input id="ville" name="ville" type="text" value="{{ old('ville', $user->ville) }}"
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all" />
                        </div>
                    </div>

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/10 rounded-xl border border-amber-200 dark:border-amber-800">
                            <p class="text-xs text-amber-700 dark:text-amber-300">
                                Votre adresse email n'est pas verifiee.
                                <button form="send-verification" class="font-bold underline hover:text-amber-900">Renvoyer le lien de verification.</button>
                            </p>
                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>
                        </div>
                    @endif

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-accent text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-accent/90 transition-all shadow-sm">
                            Enregistrer
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                                class="text-xs font-bold text-emerald-600">Profil mis a jour.</p>
                        @endif
                    </div>
                </form>
            </x-ui.section>

            {{-- Mot de passe --}}
            <x-ui.section title="Changer le mot de passe" icon="lock" :noPadding="false">
                <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="current_password" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Mot de passe actuel</label>
                            <input id="current_password" name="current_password" type="password"
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all" />
                            @error('current_password', 'updatePassword') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Nouveau mot de passe</label>
                            <input id="password" name="password" type="password"
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all" />
                            @error('password', 'updatePassword') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Confirmer</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all" />
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-accent text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-accent/90 transition-all shadow-sm">
                            Mettre a jour
                        </button>
                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                                class="text-xs font-bold text-emerald-600">Mot de passe mis a jour.</p>
                        @endif
                    </div>
                </form>
            </x-ui.section>

            {{-- Zone dangereuse --}}
            <x-ui.section title="Zone dangereuse" icon="alert-triangle" :noPadding="false">
                <div class="p-4 bg-red-50 dark:bg-red-900/10 rounded-xl border border-red-200 dark:border-red-800">
                    <div class="flex items-start gap-4">
                        <x-lucide-trash-2 class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" />
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-700 dark:text-red-400">Supprimer mon compte</p>
                            <p class="text-xs text-red-600/70 dark:text-red-300/70 mt-1">
                                Cette action est irreversible. Toutes vos donnees seront definitivement supprimees.
                            </p>
                        </div>
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                            class="px-4 py-2 bg-red-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-red-600 transition-all flex-shrink-0">
                            Supprimer
                        </button>
                    </div>
                </div>

                <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                        @csrf
                        @method('delete')

                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <x-lucide-alert-triangle class="w-5 h-5 text-red-500" />
                            </div>
                            <h2 class="text-lg font-bold text-heading">Confirmer la suppression</h2>
                        </div>

                        <p class="text-sm text-subtle mb-6">
                            Entrez votre mot de passe pour confirmer la suppression definitive de votre compte et de toutes vos donnees.
                        </p>

                        <div class="mb-6">
                            <input name="password" type="password" placeholder="Votre mot de passe"
                                class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all" />
                            @error('password', 'userDeletion') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" x-on:click="$dispatch('close')"
                                class="px-5 py-2.5 bg-surface text-subtle text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-surface-alt border border-border-light transition-all">
                                Annuler
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-red-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-red-600 transition-all">
                                Supprimer definitivement
                            </button>
                        </div>
                    </form>
                </x-modal>
            </x-ui.section>

            {{-- Infos compte (lecture seule) --}}
            <x-ui.section title="Informations du compte" icon="info" :noPadding="false">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-surface rounded-xl border border-border-light">
                        <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Role</span>
                        <span class="text-sm font-bold text-heading">{{ $user->role?->label() ?? 'Non defini' }}</span>
                    </div>
                    <div class="p-4 bg-surface rounded-xl border border-border-light">
                        <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Organisation</span>
                        <span class="text-sm font-bold text-heading">{{ $user->organization?->name ?? 'Aucune' }}</span>
                    </div>
                    <div class="p-4 bg-surface rounded-xl border border-border-light">
                        <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Membre depuis</span>
                        <span class="text-sm font-bold text-heading">{{ $user->created_at?->format('d/m/Y') }}</span>
                    </div>
                    <div class="p-4 bg-surface rounded-xl border border-border-light">
                        <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Derniere connexion</span>
                        <span class="text-sm font-bold text-heading">{{ $user->updated_at?->diffForHumans() }}</span>
                    </div>
                </div>
            </x-ui.section>
        </div>

    </x-ui.page-layout>
</x-app-layout>
