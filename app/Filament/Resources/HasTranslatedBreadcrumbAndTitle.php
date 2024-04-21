<?php

declare(strict_types=1);

namespace App\Filament\Resources;

trait HasTranslatedBreadcrumbAndTitle
{
    /**
     * Get navigation label
     */
    public static function getNavigationLabel(): string
    {
        return static::getCustomTranslation();
    }

    /**
     * Get breadcrumb
     */
    public static function getBreadcrumb(): string
    {
        return static::getCustomTranslation();
    }

    /**
     * Get navigation translation from current breadcrumb name converted to snake string
     */
    protected static function getCustomTranslation(): string
    {
        return trans('navigation.'.str(parent::getBreadcrumb())->snake());
    }
}
