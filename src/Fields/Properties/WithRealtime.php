<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithRealtime
{
    public function realtime(): mixed
    {
        return $this->field->get('realtime');
    }
}
