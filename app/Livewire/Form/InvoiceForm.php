<?php

declare(strict_types=1);

namespace App\Livewire\Form;

use App\Models\Contract;
use App\Models\Invoice;
use App\Services\InvoiceComputedData;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;

class InvoiceForm extends BaseForm
{
    public ?int $contract_id = null;

    public ?string $number = null;

    public ?int $year = null;

    public ?int $month = null;

    public Carbon|string|null $issue_date = null;

    public Carbon|string|null $due_date = null;

    public ?float $total_amount = null;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'contract_id' => ['required', Rule::exists(Contract::class, 'id')->where('user_id', auth()->id())],
            'number' => [
                'required', 'string', Rule::unique(Invoice::class, 'number')
                    ->where('user_id', auth()->id())
                    ->where('contract_id', $this->contract_id)
                    ->ignore($this->modelId),
            ],
            'year' => ['required', 'int', 'between:1900,'.now()->year],
            'month' => ['required', 'int', 'between:1,12'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
        ];
    }

    public function setDataForUpdate(): void
    {
        $model = $this->getModel();

        $this->number = $model->number;
        $this->contract_id = $model->contract_id;
        $this->year = $model->year;
        $this->month = $model->month;
        $this->issue_date = $model->issue_date->toDateString();
        $this->due_date = $model->due_date->toDateString();
    }

    public function setDataForStore(): void
    {
        $this->number = null;
        $this->contract_id = null;
        $this->year = now()->year;
        $this->month = now()->month;
        $this->issue_date = now()->toDateString();
        $this->due_date = now()
            ->addDays(7)
            ->toDateString();
    }

    public function render(): View
    {
        return view('livewire.form.invoice-form');
    }

    protected function updateModelAction(): void
    {
        // get model
        $model = $this->getModel();

        // authorize user action
        $this->authorize('update', $model);

        $invoiceService = $this->getInvoiceComputedService();

        $data = [
            ...$this->validate(),
            'content' => $invoiceService->getContent(),
            'total_amount' => $invoiceService->getTotalAmount(),
        ];

        // update model data
        $model->update($data);
    }

    protected function createModel(): void
    {
        $invoiceService = $this->getInvoiceComputedService();

        $data = [
            ...$this->validate(),
            'content' => $invoiceService->getContent(),
            'total_amount' => $invoiceService->getTotalAmount(),
        ];

        auth()->user()->invoices()->create($data);
    }

    protected function getModel(): Invoice
    {
        return Invoice::findOrFail($this->modelId);
    }

    private function getInvoiceComputedService(): InvoiceComputedData
    {
        return app(InvoiceComputedData::class, [
            'contractId' => $this->contract_id,
            'year' => $this->year,
            'month' => $this->month,
        ]);
    }
}
