<?php

namespace Aerni\StatamicLivewireForms\Tags;

use Statamic\Tags\Iterate as BaseIterate;

class Iterate extends BaseIterate
{
    public function index()
    {
        return $this->wildcard($this->params->get('array'));
    }

    /**
     * Maps to the {{ iterate:fieldname }} tag.
     *
     * Also maps to {{ foreach:fieldname }}.
     * It's called Iterate because foreach is a reserved word. Thanks PHP.
     *
     * @return mixed
     */
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
