<?php

/**
 * Récupère les N premiers mots d'une chaîne.
 *
 * @param  string  $text
 * @param  int  $wordsCount
 * @return string
 */
function excerpt_words($text, $wordsCount = 4)
{
    // Supprime les balises HTML et multiple espaces
    $text = strip_tags($text);
    $text = preg_replace('/\s+/', ' ', trim($text));

    // Découpe en mots
    $words = explode(' ', $text);

    // Prend les N premiers mots
    $excerpt = implode(' ', array_slice($words, 0, $wordsCount));

    // Enlève la ponctuation collée (ex: "création," → "création")
    $excerpt = preg_replace('/[^\w\s]/u', '', $excerpt);

    return $excerpt;
}

/**
 * Formate une date selon le format demandé et la locale actuelle.
 *
 * @param string|null $date
 * @param string $format 'short', 'long', 'day', 'default'
 * @return string
 */
function format_date($date, $format = 'default')
{
    if (empty($date)) {
        return '';
    }

    $carbon = \Carbon\Carbon::parse($date);
    $locale = app()->getLocale(); // 'fr', 'en', etc.
    $carbon->locale($locale);

    switch ($format) {
        case 'short':
            // fr: 10/08/2025 | en: 08/10/2025
            return $carbon->translatedFormat('d/m/Y');

        case 'long':
            // fr: 10 août 2025 | en: August 10, 2025
            return $carbon->translatedFormat('j F Y');

        case 'day':
            // fr: Dimanche 10 août | en: Sunday, August 10
            return $carbon->translatedFormat('l j F');

        case 'datetime':
            // fr: 10 août 2025 à 14:30 | en: August 10, 2025 at 2:30 PM
            return $locale === 'fr'
                ? $carbon->translatedFormat('j F Y à H:i')
                : $carbon->translatedFormat('F j, Y \a\t h:i A');

        case 'default':
        default:
            return $carbon->format('d/m/Y');
    }
}


/**
 * 
 * function date_short($date) { return format_date($date, 'short'); }
 * function date_long($date)  { return format_date($date, 'long');  }
 * function date_day($date)    { return format_date($date, 'day');    }
 * function date_time($date)   { return format_date($date, 'datetime'); }
 * 
 */


/**
 * Affiche une date de façon "amicale" : "à l'instant", "hier", "il y a 3 jours", etc.
 * Si la date est ancienne (> 2 semaines), affiche la date complète selon la locale.
 *
 * @param string|\DateTimeInterface|null $date
 * @return string
 */


function friendly_date($date)
{
    if (empty($date)) {
        return '';
    }

    try {
        // Forcer le parsing même pour les dates sans heure
        $carbon = \Carbon\Carbon::parse($date)->startOfDay();
        $now = now();
        $diffInDays = (int) $carbon->diffInDays($now);
        $diffInWeeks = (int) $carbon->diffInWeeks($now);
        $locale = app()->getLocale();

        $carbon->locale($locale);

        // Cas particulier pour les dates sans heure (comme 2012-08-23)
        if ($diffInDays > 14) {
            return format_date($carbon, 'long');
        }

        // Hier
        if ($carbon->isYesterday()) {
            return $locale === 'fr' ? 'hier' : 'yesterday';
        }

        // Avant-hier
        if ($diffInDays === 2) {
            return $locale === 'fr' ? 'avant-hier' : '2 days ago';
        }

        // Moins d'une semaine
        if ($diffInDays <= 6) {
            if ($locale === 'fr') {
                return "il y a {$diffInDays} jour" . ($diffInDays > 1 ? 's' : '');
            }
            return "{$diffInDays} day" . ($diffInDays > 1 ? 's' : '') . ' ago';
        }

        // 1-2 semaines
        if ($diffInWeeks <= 2) {
            if ($locale === 'fr') {
                return "il y a {$diffInWeeks} semaine" . ($diffInWeeks > 1 ? 's' : '');
            }
            return "{$diffInWeeks} week" . ($diffInWeeks > 1 ? 's' : '') . ' ago';
        }

        // Par défaut : date formatée
        return format_date($carbon, 'long');

    } catch (\Exception $e) {
        // Fallback si le parsing échoue
        return $date;
    }
}

if (!function_exists('isSelfHosted')) {
    function isSelfHosted(): bool
    {
        return config('gpro.mode') === 'selfhosted';
    }
}

if (!function_exists('isSaas')) {
    function isSaas(): bool
    {
        return config('gpro.mode') !== 'selfhosted';
    }
}

if (!function_exists('notify')) {
    /**
     * Dispatch a global notification using the custom Notifier.
     * Generates a session flash that will be caught by the AlpineJS Toast component.
     *
     * @return \App\Helpers\Notifier
     */
    function notify()
    {
        return new \App\Helpers\Notifier();
    }
}