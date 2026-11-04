<?php

namespace App\Models\Concerns;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;

/**
 * Polymorphic translation trait.
 *
 * Models using this trait must declare:
 *   protected array $translatable = ['name', 'description', ...];
 *
 * Usage:
 *   $category->t('name');           // current app locale
 *   $category->t('name', 'en');     // explicit locale
 *   $category->getTranslations('name'); // ['fr' => '...', 'en' => '...']
 *   $category->setTranslation('name', 'en', 'Technology');
 *   $category->setTranslations(['fr' => 'X', 'en' => 'Y'], 'name');
 */
trait HasTranslations
{
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get translated value for a field.
     * Falls back to the fallback locale (config('app.fallback_locale')),
     * then to the raw model column.
     */
    public function t(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?: App::getLocale();
        $fallback = config('app.fallback_locale', 'fr');

        $translation = $this->translations
            ->where('field', $field)
            ->firstWhere('locale', $locale);

        if ($translation && $translation->value !== null && $translation->value !== '') {
            return $translation->value;
        }

        if ($locale !== $fallback) {
            $fallbackTranslation = $this->translations
                ->where('field', $field)
                ->firstWhere('locale', $fallback);

            if ($fallbackTranslation && $fallbackTranslation->value !== null && $fallbackTranslation->value !== '') {
                return $fallbackTranslation->value;
            }
        }

        return $this->getAttribute($field);
    }

    /**
     * Get all translations for a field, keyed by locale.
     *
     * @return array<string, string|null>
     */
    public function getTranslations(string $field): array
    {
        return $this->translations
            ->where('field', $field)
            ->pluck('value', 'locale')
            ->toArray();
    }

    /**
     * Set a translation for a single field + locale.
     */
    public function setTranslation(string $field, string $locale, ?string $value): self
    {
        $this->translations()->updateOrCreate(
            ['field' => $field, 'locale' => $locale],
            ['value' => $value]
        );

        $this->load('translations');

        return $this;
    }

    /**
     * Bulk set translations for a single field across multiple locales.
     *
     * @param  array<string, string|null>  $values  e.g. ['fr' => 'X', 'en' => 'Y']
     */
    public function setTranslations(array $values, string $field): self
    {
        foreach ($values as $locale => $value) {
            $this->setTranslation($field, $locale, $value);
        }

        return $this;
    }

    /**
     * List of fields declared translatable on the model.
     *
     * @return array<int, string>
     */
    public function getTranslatableFields(): array
    {
        return property_exists($this, 'translatable') ? $this->translatable : [];
    }

    /**
     * Returns a payload of all translatable fields with all their locales,
     * useful for admin forms.
     *
     * @return array<string, array<string, string|null>>
     */
    public function translationsPayload(): array
    {
        $payload = [];

        foreach ($this->getTranslatableFields() as $field) {
            $payload[$field] = $this->getTranslations($field);
        }

        return $payload;
    }

    /**
     * Mutates the model so that toArray()/JSON serialization returns the
     * translated values (current locale) for the translatable fields, with
     * automatic fallback to the fallback locale or to the raw column.
     *
     * Idempotent — safe to call multiple times. Returns $this for chaining.
     *
     * Use it in API controllers when you return models directly without
     * going through an API Resource:
     *   $jobs->each->localizeForApi();
     *   return response()->json($jobs);
     */
    public function localizeForApi(?string $locale = null): self
    {
        foreach ($this->getTranslatableFields() as $field) {
            $this->setAttribute($field, $this->t($field, $locale));
        }

        return $this;
    }

    /**
     * Translation completeness per locale (0..1).
     *
     * @param  array<int, string>|null  $locales
     * @return array<string, float>
     */
    public function translationCompleteness(?array $locales = null): array
    {
        $locales = $locales ?: config('translations.locales', ['fr', 'en', 'es', 'ar']);
        $fields = $this->getTranslatableFields();
        $totalFields = count($fields);

        if ($totalFields === 0) {
            return array_fill_keys($locales, 1.0);
        }

        $result = [];

        foreach ($locales as $locale) {
            $filled = 0;
            foreach ($fields as $field) {
                $tr = $this->translations
                    ->where('field', $field)
                    ->firstWhere('locale', $locale);

                if ($tr && $tr->value !== null && $tr->value !== '') {
                    $filled++;
                }
            }
            $result[$locale] = $totalFields > 0 ? $filled / $totalFields : 1.0;
        }

        return $result;
    }

    public static function bootHasTranslations(): void
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }
            $model->translations()->delete();
        });

        // 🌍 L'auto-localisation (requêtes API) ne se fait plus ici, modèle
        // par modèle : `retrieved` est déclenché avant tout eager-load, donc
        // chaque modèle chargeait ses traductions avec SA requête (N+1 :
        // 887 requêtes pour la liste des domaines, 1 par catégorie dans
        // l'annuaire). Elle est faite par lot dans `newCollection()`.
    }

    /** Le modèle a-t-il déjà été localisé pour la requête API courante ? */
    protected bool $apiLocalized = false;

    protected static function shouldAutoLocalize(): bool
    {
        return App::bound('translations.auto_localize')
            && App::make('translations.auto_localize');
    }

    /**
     * Toute requête Eloquent (get, first, find, eager-load, relation lazy…)
     * passe par ici après hydratation : on charge les traductions de TOUS
     * les modèles du lot en une seule requête, puis on les localise.
     */
    public function newCollection(array $models = []): Collection
    {
        $collection = parent::newCollection($models);

        if (static::shouldAutoLocalize()) {
            $pending = $collection->filter(
                fn ($model) => $model instanceof self && ! $model->apiLocalized
            );

            if ($pending->isNotEmpty()) {
                $pending->loadMissing('translations');
                $pending->each->markLocalizedForApi();
            }
        }

        return $collection;
    }

    /**
     * Filet de sécurité pour les modèles lus en base sans passer par
     * `newCollection()` (ex. `cursor()`) : seule la sortie JSON est
     * traduite, les attributs ne sont pas modifiés (un `save()` ultérieur
     * n'écrira jamais une traduction dans la colonne d'origine). Les modèles
     * tout juste créés restent non localisés, comme avant.
     */
    public function toArray()
    {
        $array = parent::toArray();

        if (! $this->apiLocalized
            && $this->exists
            && ! $this->wasRecentlyCreated
            && static::shouldAutoLocalize()) {
            foreach ($this->getTranslatableFields() as $field) {
                if (array_key_exists($field, $array)) {
                    $array[$field] = $this->t($field);
                }
            }
        }

        return $array;
    }

    public function markLocalizedForApi(): void
    {
        $this->localizeForApi();
        $this->apiLocalized = true;
    }
}
