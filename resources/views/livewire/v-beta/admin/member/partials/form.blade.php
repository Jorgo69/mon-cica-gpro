{{-- <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-lg shadow-lg"> --}}
    <form wire:submit.prevent="{{ $action }}" class="space-y-4">
        <!-- Nom -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom complet</label>
            <input type="text" wire:model="name"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
            <input type="email" wire:model="email"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Mot de passe (uniquement si create ou si modif avec nouveau mot de passe) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Mot de passe</label>
            <input type="password" wire:model="password"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Téléphone -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Téléphone</label>
            <input type="text" wire:model="telephone"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
            @error('telephone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Sexe -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sexe</label>
            <select wire:model="sexe"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                <option value="">-- Choisir --</option>
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>
            @error('sexe') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Numéro d'identification -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Numéro d'identification</label>
            <input type="text" wire:model="numero_identification"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
            @error('numero_identification') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Pays -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pays</label>
            <input type="text" wire:model="pays"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
            @error('pays') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Ville -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Ville</label>
            <input type="text" wire:model="ville"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
            @error('ville') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Rôle -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Rôle</label>
            <select wire:model="role"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                <option value="member">Membre</option>
                <option value="Administrateur">Admin</option>
                <option value="manager">Manager</option>
            </select>
            @error('role') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Département -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Département</label>
            <input type="text" wire:model="department"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
            @error('department') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Image -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Photo</label>
            <input type="file" wire:model="image"
                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300">
            @error('image') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-20 w-20 object-cover rounded-full">
            @endif
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-2 mt-4">
            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 rounded">
                Annuler
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                {{ $action === 'store' ? 'Enregistrer' : 'Mettre à jour' }}
            </button>
        </div>
    </form>
{{-- </div> --}}
