@if (session('error'))
<div class="alert-container" data-duration="5000">
    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 my-2 shadow-md relative" role="alert">
        <div class="flex">
            <div class="py-1">
                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16zm0 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12zm0 9a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-8a1 1 0 0 1 1 1v5a1 1 0 1 1-2 0V6a1 1 0 0 1 1-1z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold">Erreur</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
</div>
@endif