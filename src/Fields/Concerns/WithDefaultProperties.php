<?php

namespace Aerni\LivewireForms\Fields\Concerns;

use Aerni\LivewireForms\Fields\Properties\WithConditions;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithDisplay;
use Aerni\LivewireForms\Fields\Properties\WithHandle;
use Aerni\LivewireForms\Fields\Properties\WithHidden;
use Aerni\LivewireForms\Fields\Properties\WithHideDisplay;
use Aerni\LivewireForms\Fields\Properties\WithId;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithInstructionsPosition;
use Aerni\LivewireForms\Fields\Properties\WithKey;
use Aerni\LivewireForms\Fields\Properties\WithRules;
use Aerni\LivewireForms\Fields\Properties\WithView;
use Aerni\LivewireForms\Fields\Properties\WithWidth;
use Aerni\LivewireForms\Fields\Properties\WithWireModel;

trait WithDefaultProperties
{
    use WithConditions;
    use WithDefault;
    use WithDisplay;
    use WithHandle;
    use WithHidden;
    use WithHideDisplay;
    use WithId;
    use WithInstructions;
    use WithInstructionsPosition;
    use WithKey;
    use WithRules;
    use WithView;
    use WithWidth;
    use WithWireModel;
}
