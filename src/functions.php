<?php

use Illuminate\Support\Collection;

Collection::macro('trim', function () {
    /** @var Collection $this */
    return $this->map(fn($value) => is_string($value) ? trim($value) : $value);
});
Collection::macro('trimRecursive', function () {
    /** @var Collection $this */
    return $this->map(function ($value) {
        if (is_string($value)) {
            return trim($value);
        }
        if (is_array($value)) {
            return collect($value)->trimRecursive()->all();
        }
        if ($value instanceof Collection) {
            return $value->trimRecursive();
        }

        return $value;
    });
});
Collection::macro('mapKeys', function (callable $callback) {
    /** @var Collection $this */
    /* @psalm-suppress InvalidArgument */
    return $this->keys()->map($callback)->combine($this->values());
});
