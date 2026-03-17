<?php

namespace App\Services\PDF;

use Illuminate\Support\Collection;

class PDFTemplateManager
{
    /**
     * Get all available PDF templates.
     *
     * @return Collection
     */
    public function getAvailableTemplates(): Collection
    {
        // On pourrait scanner le dossier resources/views/pdf/templates
        // Pour l'instant, on garde une configuration explicite pour le nom/description
        return collect([
            'modern' => [
                'name' => 'Modèle Moderne (Premium)',
                'description' => 'Un design épuré et professionnel avec graphiques.',
                'view' => 'pdf.templates.modern.index',
            ],
            'classic' => [
                'name' => 'Modèle Classique (Officiel)',
                'description' => 'Format standard structuré pour les archives.',
                'view' => 'pdf.templates.classic.index',
            ],
        ]);
    }

    /**
     * Get the default template for an organization or global.
     *
     * @param string|null $organizationId
     * @return array
     */
    public function getTemplateForOrganization(?string $organizationId = null): array
    {
        // For now, always return modern. 
        // Later, we can fetch this from Organization settings.
        return $this->getAvailableTemplates()->get('modern');
    }
}
