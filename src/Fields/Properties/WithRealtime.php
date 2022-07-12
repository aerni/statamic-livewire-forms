<?php

namespace Aerni\LivewireForms\Fields\Properties;

trait WithRealtime
{
    protected function realtimeProperty(): array|string|bool|null
    {
        return $this->field->get('realtime');
    }
}
