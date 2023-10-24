<?php

namespace App\Traits;

use Illuminate\Support\Enumerable;
use RamonRietdijk\LivewireTables\Actions\Action;

trait HasActiveActions
{
    public function actions(): array
    {
        return [
            ...parent::actions(),
            Action::make('Deactivate', 'deactivate', function (Enumerable $models): void {
                foreach ($models as $model) {
                    $model->update(['active' => false]);
                }
            }),
            Action::make('Activate', 'activate', function (Enumerable $models): void {
                foreach ($models as $model) {
                    $model->update(['active' => true]);
                }
            }),
        ];
    }
}
