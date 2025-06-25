<?php

namespace MobileNowGroup\SearchableTextField;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Text;

class SearchableText extends Text
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

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        $uniqueKey = \sprintf(
        '%s-%s-%s-%s',
            $this->attribute,
            Str::slug($this->panel?->name ?? 'default'),
            $this->component(),
            Str::random(8)
        );

        return array_merge(parent::jsonSerialize(), compact('uniqueKey'));
    }
}
