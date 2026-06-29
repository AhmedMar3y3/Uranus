<?php

namespace App\Traits;

trait HasLocalizedEnum
{
    public function getLocalizedName(): string
    {
        $enumName = $this->getEnumNameForTranslation();
        $key      = $this->value;

        $langPath = base_path('lang/ar/enums.php');
        if (file_exists($langPath)) {
            $translations = require $langPath;
            if (is_array($translations) && isset($translations[$enumName][$key])) {
                return $translations[$enumName][$key];
            }
        }

        $translationKey = "enums.{$enumName}.{$key}";
        $translation = trans($translationKey, [], 'ar');
        
        if ($translation !== $translationKey) {
            return $translation;
        }

        return $key;
    }

    private function getEnumNameForTranslation(): string
    {
        $className = class_basename($this);
        $snakeCase = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));
        return $snakeCase . 's';
    }

    public static function getLocalizedOptions(): array
    {
        $options = [];
        foreach (static::cases() as $case) {
            $options[$case->value] = $case->getLocalizedName();
        }

        return $options;
    }
}
