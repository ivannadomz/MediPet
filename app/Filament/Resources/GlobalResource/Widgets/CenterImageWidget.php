<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CenterImageWidget extends Widget
{
    protected static string $view = 'filament.widgets.center-image-widget';

    protected int | string | array $columnSpan = [
        'md' => 2, // Ajusta el tamaño según los otros widgets
        'xl' => 4, // Se adapta mejor a pantallas grandes
    ];
}