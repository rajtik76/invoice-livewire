<?php

declare(strict_types=1);

namespace App\Livewire\Form;

use App\Models\Contract;
use App\Models\Report;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ReportForm extends BaseForm
{
    public ?int $contract_id = null;

    public ?int $year = null;

    public ?int $month = null;

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'contract_id' => [
                'required',
                Rule::exists(Contract::class, 'id')->where('user_id', auth()->id()),
                function ($attribute, $value, $fail) {
                    if (Report::where('contract_id', $value)
                        ->where('year', Arr::first($this->validateOnly('year')))
                        ->where('month', Arr::first($this->validateOnly('month')))
                        ->when($this->modelId, function($query, $value) {
                            $query->where('id' ,'!=', $value);
                        })
                        ->exists()) {
                        $fail(trans('validation.custom.report.unique'));
                    }
                }],
            'year' => ['required', 'numeric', 'between:1900,' . now()->year],
            'month' => ['required', 'numeric', 'between:1,12'],
        ];
    }

    public function setDataForUpdate(): void
    {
        $model = $this->getModel();

        $this->contract_id = $model->contract_id;
        $this->year = $model->year;
        $this->month = $model->month;
    }

    public function setDataForStore(): void
    {
        $this->contract_id = null;
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function render(): View
    {
        return view('livewire.form.report-form');
    }

    protected function createModel(): void
    {
        auth()->user()->reports()->create($this->validate());
    }

    protected function getModel(): Report
    {
        return Report::findOrFail($this->modelId);
    }
}
