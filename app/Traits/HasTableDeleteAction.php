<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use RamonRietdijk\LivewireTables\Actions\Action;

trait HasTableDeleteAction
{
    protected function actions(): array
    {
        return [
            Action::make('Delete', 'delete', function (Collection $models) {
                $models->each(function (Model $model) {
                    if (auth()->user()->cannot('delete', $model)) {
                        abort(403);
                    }

                    return $model->delete();
                });
            }),
        ];
    }
}
