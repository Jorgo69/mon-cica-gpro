<form wire:submit.prevent="{{ $action }}">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Nom complet --}}
        <x-ui.input
            wire:model="name"
            label="Nom complet"
            placeholder="Ex: Jean Dupont"
            icon="user"
            required
            :error="$errors->first('name')"
        />

        {{-- Email --}}
        <x-ui.input
            wire:model="email"
            type="email"
            label="Adresse Email"
            placeholder="jean.dupont@example.com"
            icon="mail"
            required
            :error="$errors->first('email')"
        />

        {{-- Mot de passe --}}
        <x-ui.input
            wire:model="password"
            type="password"
            label="Mot de passe"
            placeholder="{{ $action === 'store' ? '••••••••' : 'Laisser vide pour ne pas modifier' }}"
            icon="lock"
            :required="$action === 'store'"
            :error="$errors->first('password')"
        />

        {{-- Téléphone --}}
        <x-ui.input
            wire:model="telephone"
            label="Téléphone"
            placeholder="+229 XX XX XX XX"
            icon="phone"
            :error="$errors->first('telephone')"
        />

        {{-- N° Identification --}}
        <x-ui.input
            wire:model="numero_identification"
            label="Numéro d'identification"
            placeholder="ID National / Passeport"
            icon="id-card"
            :error="$errors->first('numero_identification')"
        />

        {{-- Pays --}}
        <x-ui.input
            wire:model="country"
            label="Pays"
            placeholder="Ex: BJ"
            icon="globe"
            :error="$errors->first('country')"
        />

        {{-- Ville --}}
        <x-ui.input
            wire:model="ville"
            label="Ville"
            placeholder="Ex: Cotonou"
            icon="map-pin"
            :error="$errors->first('ville')"
        />

        {{-- Quartier --}}
        <x-ui.input
            wire:model="quartier"
            label="Quartier"
            placeholder="Ex: Akwa"
            icon="home"
            :error="$errors->first('quartier')"
        />

        {{-- Rôle dans l'organisation --}}
        <x-ui.select
            wire:model="org_role"
            label="Rôle dans l'organisation"
            icon="shield"
            required
            :error="$errors->first('org_role')"
        >
            <option value="">-- Sélectionner un rôle --</option>
            @foreach(\App\Enums\OrgMemberRole::cases() as $role)
                <option value="{{ $role->value }}">{{ $role->label() }}</option>
            @endforeach
        </x-ui.select>

        {{-- Rôle fonctionnel (Spatie) --}}
        <x-ui.select
            wire:model="spatie_role"
            label="Rôle fonctionnel (permissions)"
            icon="key"
            required
            :error="$errors->first('spatie_role')"
        >
            <option value="">-- Sélectionner --</option>
            <option value="ORG_ADMIN">Admin Organisation</option>
            <option value="MANAGER">Gestionnaire de projets</option>
            <option value="MEMBER">Membre d'équipe</option>
            <option value="SUPERVISOR">Superviseur / Bailleur</option>
        </x-ui.select>

        {{-- Photo --}}
        <div class="md:col-span-2">
            <x-ui.image-upload
                wire:model="image"
                label="Photo de profil du membre"
                :preview="$image ?? ($selectedMember?->image)"
                :error="$errors->first('image')"
            />
        </div>
    </div>

    {{-- Actions --}}
    <x-slot:footer>
        <x-ui.button type="button" variant="ghost" wire:click="closeModal">
            Annuler
        </x-ui.button>
        <x-ui.button type="submit" variant="accent" icon="check" loadingText="Enregistrement..." loadingTarget="{{ $action }}">
            {{ $action === 'store' ? 'Créer le membre' : 'Mettre à jour' }}
        </x-ui.button>
    </x-slot:footer>
</form>
