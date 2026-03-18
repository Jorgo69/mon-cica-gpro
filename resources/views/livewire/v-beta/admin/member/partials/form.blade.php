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

        {{-- Sexe --}}
        <x-ui.select 
            wire:model="sexe" 
            label="Sexe" 
            icon="users-2"
            :error="$errors->first('sexe')"
        >
            <option value="">-- Choisir --</option>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
        </x-ui.select>

        {{-- Numéro d'identification --}}
        <x-ui.input 
            wire:model="numero_identification" 
            label="Numéro d'identification" 
            placeholder="ID National / Passeport" 
            icon="id-card" 
            :error="$errors->first('numero_identification')"
        />

        {{-- Pays --}}
        <x-ui.input 
            wire:model="pays" 
            label="Pays" 
            placeholder="Ex: Bénin" 
            icon="globe" 
            :error="$errors->first('pays')"
        />

        {{-- Ville --}}
        <x-ui.input 
            wire:model="ville" 
            label="Ville" 
            placeholder="Ex: Cotonou" 
            icon="map-pin" 
            :error="$errors->first('ville')"
        />

        {{-- Rôle --}}
        <x-ui.select 
            wire:model="role" 
            label="Rôle / Type de compte" 
            icon="shield" 
            required
            :error="$errors->first('role')"
        >
            <option value="">-- Sélectionner un rôle --</option>
            @foreach(\App\Enums\AccountType::cases() as $type)
                <option value="{{ $type->value }}">{{ $type->label() }}</option>
            @endforeach
        </x-ui.select>

        {{-- Département --}}
        <x-ui.input 
            wire:model="department" 
            label="Département" 
            placeholder="Ex: IT, Finance, RH" 
            icon="building" 
            :error="$errors->first('department')"
        />

        {{-- Photo : Utilisation du nouveau composant Premium --}}
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
