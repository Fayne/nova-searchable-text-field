<?php

namespace MobileNowGroup\SearchableTextField;

use Laravel\Nova\Fields\Field;

class SearchableText extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'searchable-text-field';

    /**
     * @param array $options
     * @return self
     */
    public function options(array $options): self
    {
        return $this->withMeta([
            'labelToValueMap' => $options,
        ]);
    }

    /**
     * @param string $path
     * @return self
     */
    public function remoteOptionsUrl(string $path): self
    {
        return $this->withMeta([
            'fetch-suggestion-url' => $path,
        ]);
    }
}
