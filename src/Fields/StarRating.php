<?php

namespace Aerni\LivewireForms\Fields;

class StarRating extends Field
{
    protected string $view = 'star_rating';

    protected function maxStarsProperty(?int $maxStars = null): int
    {
        return $maxStars ?? $this->field->get('max_stars', 5);
    }
}
