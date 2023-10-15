<?php

use function Livewire\Volt\{state, on};

state(['isShowing' => false, 'address' => 0]);

$closeModal = function () {
    $this->address = 0;
    $this->isShowing = false;
};

on(['address-update-form-modal-open' => function (int $address) {
    $this->address = $address;
    $this->isShowing = true;
}]);

on(['address-update-form-modal-close' => function () {
    $this->dispatch('refreshLivewireTable');
    $this->closeModal();
}]);

?>

<div>
    @if($isShowing)
        <x-modal name="address-update-form-modal" :show="true" :auto-close="false">
            <div class="p-4">
                <x-slot name="form"></x-slot>
                <div>
                    <livewire:address.update-address-form :address="$address"/>
                </div>
            </div>
        </x-modal>
    @endif
</div>
