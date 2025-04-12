<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class RatingStars extends Component
{
    public float|int $value;
    public string $size;

    public function __construct($value = 0, $size = 'md')
    {
        $this->value = floatval($value);
        $this->size = $size;
    }

    public function render()
    {
        return view('components.ui.rating-stars');
    }
}

