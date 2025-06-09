<template>
  <DefaultField
      :field="currentField"
      :errors="errors"
      :show-help-text="showHelpText"
      :full-width-content="fullWidthContent"
  >
    <template #field>
      <div class="space-y-1">
        <input
            v-bind="extraAttributes"
            class="w-full form-control form-input form-control-bordered"
            @input="handleChange"
            :value="displayLabel"
            :id="currentField.uniqueKey"
            :dusk="field.attribute"
            :disabled="currentlyIsReadonly"
            :autocomplete="currentField.autocomplete"
            :maxlength="field.enforceMaxlength ? field.maxlength : -1"
            :list="suggestionsId"
        />

        <datalist v-if="suggestionLabels.length > 0" :id="suggestionsId">
          <option
              v-for="(label, index) in suggestionLabels"
              :key="index"
              :value="label"
          />
        </datalist>

        <CharacterCounter
            v-if="field.maxlength"
            :count="displayLabel.length"
            :limit="field.maxlength"
        />
      </div>
    </template>
  </DefaultField>
</template>

<script>
import debounce from 'lodash/debounce'
import {
  DependentFormField,
  FieldSuggestions,
  HandlesValidationErrors,
} from '@/mixins'

export default {
  mixins: [DependentFormField, FieldSuggestions, HandlesValidationErrors],

  data() {
    return {
      displayLabel: '',
      suggestionLabels: [],
      labelToValueMap: { ...this.currentField?.labelToValueMap || {} },
    }
  },

  created() {
    this.debouncedFetch = debounce(this.fetchSuggestions, 300);
  },

  computed: {
    suggestionsId() {
      return this.currentField.uniqueKey + '-suggestions'
    },

    suggestionLabels() {
      return Object.keys(this.labelToValueMap)
    },

    defaultAttributes() {
      return {
        type: this.currentField.type || 'text',
        class: this.errorClasses,
        min: this.currentField.min,
        max: this.currentField.max,
        step: this.currentField.step,
        pattern: this.currentField.pattern,
        placeholder: this.placeholder,

        ...this.suggestionsAttributes,
      }
    },

    extraAttributes() {
      return {
        // Leave the default attributes even though we can now specify
        // whatever attributes we like because the old number field still
        // uses the old field attributes
        ...this.defaultAttributes,
        ...this.currentField.extraAttributes,
      }
    },
  },

  methods: {
    async fetchSuggestions(query) {
      if (!query || !this.currentField['fetch-suggestion-url']) {
        this.labelToValueMap = this.currentField.labelToValueMap || {};
        this.suggestionLabels = [];

        return;
      }

      try {
        const { data } = await Nova.request().get(this.currentField['fetch-suggestion-url'], {
          params: {'query': query}
        });

        const newMap = {};
        const newLabels = [];

        Object.entries(data).forEach(([label, value]) => {
          newMap[label] = value;
          newLabels.push(label);
        });

        this.labelToValueMap = {
          ...this.currentField.labelToValueMap,
          ...newMap,
        };

        this.suggestionLabels = [
          ...new Set([
            ...Object.keys(this.currentField.labelToValueMap || {}),
            ...newLabels,
          ])
        ];
      } catch (err) {
        console.error(err);
      }
    },

    handleChange(event) {
      const label = event.target.value;
      const matchedValue = this.labelToValueMap[label];

      if (matchedValue) {
        this.displayLabel = matchedValue;
        this.$emit('input', matchedValue);
        this.value = matchedValue;

        this.$nextTick(() => {
          event.target.value = matchedValue;
          this.value = matchedValue;
        })
      } else {
        this.displayLabel = label;
        this.$emit('input', label);
        this.value = label;
      }

      if (this.value) {
        this.debouncedFetch(label);
      }
    },
  },
}
</script>
