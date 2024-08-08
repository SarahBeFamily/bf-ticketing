<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Company;

class DeleteCompany extends Component
{
    public $key;

    public function mount($key)
    {
        $this->key = $key;
    }
    
    public function render()
    {
        return view('livewire.delete-company');
    }

    /**
     * Delete the company.
     *
     * @param [int] $key | Company ID
     * @return void
     */
    public function deleteCompany($key)
    {
        $company = Company::find($key['id']);
        $company->deleteCompany();

        session()->flash('success', __('Azienda eliminata con successo.'));

        $this->redirectRoute('companies.index');
    }
}
