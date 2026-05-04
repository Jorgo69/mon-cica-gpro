<?php

use App\Services\AI\AiService;

/*
|--------------------------------------------------------------------------
| AiService::stripMarkdown() — Phase 27
|--------------------------------------------------------------------------
*/

test('stripMarkdown: doit retourner null quand le texte est null', function () {
    expect(AiService::stripMarkdown(null))->toBeNull();
});

test('stripMarkdown: doit retourner une chaine vide quand le texte est vide', function () {
    expect(AiService::stripMarkdown(''))->toBe('');
});

test('stripMarkdown: doit retourner le texte tel quel sans markdown', function () {
    $plain = 'Ceci est un texte simple sans formatage.';
    expect(AiService::stripMarkdown($plain))->toBe($plain);
});

test('stripMarkdown: doit retirer les headers de niveau 1 a 6', function () {
    expect(AiService::stripMarkdown('# Titre 1'))->toBe('Titre 1');
    expect(AiService::stripMarkdown('## Titre 2'))->toBe('Titre 2');
    expect(AiService::stripMarkdown('### Titre 3'))->toBe('Titre 3');
    expect(AiService::stripMarkdown('###### Titre 6'))->toBe('Titre 6');
});

test('stripMarkdown: doit retirer le bold avec double asterisque', function () {
    expect(AiService::stripMarkdown('Texte **gras** ici'))->toBe('Texte gras ici');
});

test('stripMarkdown: doit retirer le bold avec triple asterisque', function () {
    expect(AiService::stripMarkdown('Texte ***gras italique*** ici'))->toBe('Texte gras italique ici');
});

test('stripMarkdown: doit retirer l italic avec simple asterisque', function () {
    expect(AiService::stripMarkdown('Texte *italique* ici'))->toBe('Texte italique ici');
});

test('stripMarkdown: doit retirer l italic avec underscore', function () {
    expect(AiService::stripMarkdown('Texte _italique_ ici'))->toBe('Texte italique ici');
});

test('stripMarkdown: doit retirer les code blocks', function () {
    $input = "Avant\n```php\necho 'hello';\n```\nApres";
    expect(AiService::stripMarkdown($input))->toBe("Avant\n\nApres");
});

test('stripMarkdown: doit retirer les inline code', function () {
    expect(AiService::stripMarkdown('Utilise `composer install` pour installer'))->toBe('Utilise composer install pour installer');
});

test('stripMarkdown: doit retirer les listes a puces avec tiret', function () {
    $input = "- Premier\n- Deuxieme";
    expect(AiService::stripMarkdown($input))->toBe("Premier\nDeuxieme");
});

test('stripMarkdown: doit retirer les listes a puces avec asterisque', function () {
    $input = "* Premier\n* Deuxieme";
    expect(AiService::stripMarkdown($input))->toBe("Premier\nDeuxieme");
});

test('stripMarkdown: doit retirer les listes numerotees', function () {
    $input = "1. Premier\n2. Deuxieme\n10. Dixieme";
    expect(AiService::stripMarkdown($input))->toBe("Premier\nDeuxieme\nDixieme");
});

test('stripMarkdown: doit retirer les blockquotes', function () {
    $input = "> Citation importante\n> Suite de la citation";
    expect(AiService::stripMarkdown($input))->toBe("Citation importante\nSuite de la citation");
});

test('stripMarkdown: doit retirer les horizontal rules', function () {
    $input = "Avant\n---\nApres";
    expect(AiService::stripMarkdown($input))->toBe("Avant\n\nApres");
});

test('stripMarkdown: doit nettoyer les lignes vides excessives', function () {
    $input = "Ligne 1\n\n\n\n\nLigne 2";
    expect(AiService::stripMarkdown($input))->toBe("Ligne 1\n\nLigne 2");
});

test('stripMarkdown: doit gerer un texte mixte avec plusieurs formats', function () {
    $input = "## Titre\n\n**Intro** avec `code` et:\n\n- item 1\n- item 2\n\n> Note importante\n\n---\n\nFin du texte.";
    $result = AiService::stripMarkdown($input);

    expect($result)->not->toContain('##');
    expect($result)->not->toContain('**');
    expect($result)->not->toContain('`');
    expect($result)->not->toContain('- item');
    expect($result)->not->toContain('>');
    expect($result)->toContain('Titre');
    expect($result)->toContain('Intro');
    expect($result)->toContain('code');
    expect($result)->toContain('Fin du texte.');
});
