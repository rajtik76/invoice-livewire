<?php

namespace App\Livewire;

use App\Models\BankAccount;
use App\Models\User;
use App\Traits\HasFormModalControl;
use Livewire\Attributes\Rule;
use Livewire\Component;

class BankAccountForm extends Component
{
    use HasFormModalControl;

    #[Rule(['required', 'max:255'])]
    public string|null $bank_name = null;
    #[Rule(['required', 'max:255'])]
    public string|null $account_number = null;
    #[Rule(['required', 'max:255'])]
    public string|null $bank_number = null;
    #[Rule(['required', 'max:255'])]
    public string|null $iban = null;
    #[Rule(['required', 'max:255'])]
    public string|null $swift = null;

    public function fetchModelData(): void
    {
        $bankAccount = $this->getModel();

        $this->bank_name = $bankAccount->bank_name;
        $this->account_number = $bankAccount->account_number;
        $this->bank_number = $bankAccount->bank_number;
        $this->iban = $bankAccount->iban;
        $this->swift = $bankAccount->swift;
    }

    public function resetModelData(): void
    {
        $this->bank_name = null;
        $this->account_number = null;
        $this->bank_number = null;
        $this->iban = null;
        $this->swift = null;
    }

    public function submit()
    {
        if ($this->modelId) {
            $this->updateModel();
        } else {
            $this->createModel();
        }
    }

    public function render()
    {
        return view('livewire.bank-account-form');
    }

    private function updateModel()
    {
        $bankAccount = $this->getModel();

        if (!$this->authorize('update', $bankAccount)) {
            abort(403);
        }

        $bankAccount->update($this->validate());

        $this->dispatch('model-updated');
    }

    private function createModel()
    {
        if (!$this->authorize('create', BankAccount::class)) {
            abort(403);
        }

        auth()->user()->bankAccounts()->create($this->validate());
        $this->resetModelData();

        $this->dispatch('model-updated');
    }

    private function getModel(): BankAccount
    {
        return BankAccount::findOrFail($this->modelId);
    }
}
