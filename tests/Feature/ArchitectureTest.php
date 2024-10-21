<?php

arch()
    ->preset()
    ->php();

arch('Not used debug functions', function () {
    expect([
        'dd',
        'ddd',
        'dump',
        'env',
        'exit',
        'ray',
    ])->not->toBeUsed();
});
