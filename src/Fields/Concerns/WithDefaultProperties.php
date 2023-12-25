<?php

namespace Aerni\LivewireForms\Fields\Concerns;

use Aerni\LivewireForms\Fields\Properties\WithId;
use Aerni\LivewireForms\Fields\Properties\WithKey;
use Aerni\LivewireForms\Fields\Properties\WithView;
use Aerni\LivewireForms\Fields\Properties\WithLabel;
use Aerni\LivewireForms\Fields\Properties\WithRules;
use Aerni\LivewireForms\Fields\Properties\WithWidth;
use Aerni\LivewireForms\Fields\Properties\WithHandle;
use Aerni\LivewireForms\Fields\Properties\WithHidden;
use Aerni\LivewireForms\Fields\Properties\WithDefault;
use Aerni\LivewireForms\Fields\Properties\WithShowLabel;
use Aerni\LivewireForms\Fields\Properties\WithWireModel;
use Aerni\LivewireForms\Fields\Properties\WithConditions;
use Aerni\LivewireForms\Fields\Properties\WithInstructions;
use Aerni\LivewireForms\Fields\Properties\WithInstructionsPosition;

trait WithDefaultProperties
{
    use WithConditions;
    use WithDefault;
    use WithHandle;
    use WithHidden;
    use WithId;
    use WithInstructions;
    use WithInstructionsPosition;
    use WithKey;
    use WithLabel;
    use WithRules;
    use WithShowLabel;
    use WithView;
    use WithWidth;
    use WithWireModel;
}