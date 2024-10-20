<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/*
**AppLayout.php は「シェフの指示書」**で、アプリ全体でどういう手順や共通部分を使うかを決めます。
**app.blade.php は「お皿」**で、各ページのコンテンツ（料理）を共通の枠組みで表示するための「器」になります。
*/

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
