<button class="btn-primary inline-flex px-3 py-2 ml-3" wire:click="deleteCompany({{ $key }})" wire:confirm="{{ __('Sei sicuro di voler eliminare questa azienda? (con essa verrà eliminato anche il suo logo)') }}">
    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
    </svg>
    {{ __('Elimina') }}
</button>
