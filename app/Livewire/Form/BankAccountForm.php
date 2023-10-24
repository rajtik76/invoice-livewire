<?php

namespace App\Livewire\Form;

use App\Models\BankAccount;
use Livewire\Attributes\Rule;

class BankAccountForm extends BaseForm
{
    #[Rule(['required', 'max:255'])]
    public ?string $bank_name = null;

    #[Rule(['required', 'max:255'])]
    public ?string $account_number = null;

    #[Rule(['required', 'max:255'])]
    public ?string $bank_number = null;

    #[Rule(['required', 'max:255'])]
    public ?string $iban = null;

    #[Rule(['required', 'max:255'])]
    public ?string $swift = null;

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

    public function render()
    {
        return view('livewire.form.bank-account-form');
    }

    protected function updateModel(): void
    {
        $bankAccount = $this->getModel();

        if (! $this->authorize('update', $bankAccount)) {
            abort(403);
        }

        $bankAccount->update($this->validate());

        $this->dispatch('model-updated');
    }

    protected function createModel(): void
    {
        if (! $this->authorize('create', BankAccount::class)) {
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
