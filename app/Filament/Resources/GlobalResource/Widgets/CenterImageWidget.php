<?php

namespace App\Filament\Resources\GlobalResource\Widgets;

use Filament\Widgets\Widget;

class CenterImageWidget extends Widget
{
    protected static string $view = 'filament.widgets.center-image-widget';

    protected int | string | array $columnSpan = [
        'md' => 2, 
        'xl' => 4, 
    ];
}