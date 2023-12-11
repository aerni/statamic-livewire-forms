<?php

namespace Aerni\LivewireForms\Fields;

class Honeypot extends Field
{
    protected string $view = 'honeypot';

    protected bool $submittable = false;
}
