<?php
declare(strict_types=1);

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

    public function setDataForUpdate(): void
    {
        $bankAccount = $this->getModel();

        $this->bank_name = $bankAccount->bank_name;
        $this->account_number = $bankAccount->account_number;
        $this->bank_number = $bankAccount->bank_number;
        $this->iban = $bankAccount->iban;
        $this->swift = $bankAccount->swift;
    }

    public function setDataForCreate(): void
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

    protected function createModel(): void
    {
        $this->authorize('create', BankAccount::class);

        auth()->user()->bankAccounts()->create($this->validate());
    }

    protected function getModel(): BankAccount
    {
        return BankAccount::findOrFail($this->modelId);
    }
}
