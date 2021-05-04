<?php

namespace Aerni\LivewireForms\Tags;

use Statamic\Tags\Iterate as BaseIterate;

class Iterate extends BaseIterate
{
    public function index()
    {
        return $this->wildcard($this->params->get('array'));
    }

    public function wildcard($tag)
    {
        [$keyKey, $valueKey] = $this->getKeyNames();

        $tag = $this->context->get($tag) ?? $tag;

        $items = collect($tag)
            ->map(function ($value, $key) use ($keyKey, $valueKey) {
                return [$keyKey => $key, $valueKey => $value];
            });

        if ($limit = $this->params->int('limit')) {
            $items = $items->take($limit);
        }

        return $items->values();
    }
}
