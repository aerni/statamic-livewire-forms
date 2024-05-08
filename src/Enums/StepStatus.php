<?php

namespace Aerni\LivewireForms\Enums;

enum StepStatus: string
{
    case Previous = 'previous';
    case Current = 'current';
    case Next = 'next';
    case Invisible = 'invisible';
}
