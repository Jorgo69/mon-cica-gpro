<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $provider; // 'gemini' or 'groq'
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        // Auto-detect provider: Groq first (no geo-restriction), then Gemini
        if (!empty(config('gpro.ai.groq_api_key'))) {
            $this->provider = 'groq';
            $this->apiKey = config('gpro.ai.groq_api_key');
            $this->model = config('gpro.ai.groq_model', 'llama-3.3-70b-versatile');
        } else {
            $this->provider = 'gemini';
            $this->apiKey = config('gpro.ai.gemini_api_key', '');
            $this->model = config('gpro.ai.gemini_model', 'gemini-2.0-flash');
        }
    }

    public static function isConfigured(): bool
    {
        return !empty(config('gpro.ai.groq_api_key')) || !empty(config('gpro.ai.gemini_api_key'));
    }

    public function ask(string $prompt, string $systemInstruction = '', float $temperature = 0.7): ?string
    {
        if (!self::isConfigured()) {
            return null;
        }

        return $this->provider === 'groq'
            ? $this->askGroq($prompt, $systemInstruction, $temperature)
            : $this->askGemini($prompt, $systemInstruction, $temperature);
    }

    protected function askGroq(string $prompt, string $systemInstruction, float $temperature): ?string
    {
        try {
            $messages = [];
            if ($systemInstruction) {
                $messages[] = ['role' => 'system', 'content' => $systemInstruction];
            }
            $messages[] = ['role' => 'user', 'content' => $prompt];

            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => $temperature,
                    'max_tokens' => 2048,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::warning('Groq API error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::warning('Groq API exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    protected function askGemini(string $prompt, string $systemInstruction, float $temperature): ?string
    {
        try {
            $body = [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
                'generationConfig' => [
                    'temperature' => $temperature,
                    'maxOutputTokens' => 2048,
                ],
            ];

            if ($systemInstruction) {
                $body['systemInstruction'] = [
                    'parts' => [['text' => $systemInstruction]],
                ];
            }

            $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';
            $response = Http::timeout(30)
                ->post("{$baseUrl}/{$this->model}:generateContent?key={$this->apiKey}", $body);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text');
            }

            Log::warning('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::warning('Gemini API exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function askCached(string $prompt, string $systemInstruction = '', int $ttl = 3600): ?string
    {
        $cacheKey = 'ai_' . md5($prompt . $systemInstruction);
        return Cache::remember($cacheKey, $ttl, fn () => $this->ask($prompt, $systemInstruction));
    }

    // ─── Domain-specific methods ────────────────────────────────

    public function generateProjectDescription(string $title, ?string $context = null): ?string
    {
        $locale = app()->getLocale();
        $lang = $locale === 'fr' ? 'français' : 'English';

        $prompt = "Titre du projet : {$title}";
        if ($context) {
            $prompt .= "\nContexte : {$context}";
        }

        $system = "Tu es un expert en gestion de projets pour ONG et organisations internationales. "
            . "Genere une description professionnelle de projet en {$lang} (3-5 phrases). "
            . "Sois concis, professionnel, oriente resultats. Ne mets pas de titre, juste la description.";

        return $this->ask($prompt, $system, 0.8);
    }

    public function suggestLogframe(string $title, string $description): ?array
    {
        $locale = app()->getLocale();
        $lang = $locale === 'fr' ? 'français' : 'English';

        $prompt = "Projet : {$title}\nDescription : {$description}";

        $system = "Tu es un expert en cadre logique (logframe) pour projets ONG. "
            . "A partir du titre et description, propose un cadre logique en {$lang}. "
            . "Reponds UNIQUEMENT en JSON valide avec cette structure exacte : "
            . '{"general_objective":"...","specific_objectives":[{"description":"...","results":[{"description":"...","activities":["...","..."]}]}]}'
            . " Maximum 2 objectifs specifiques, 2 resultats par objectif, 3 activites par resultat. Sois concret et mesurable.";

        $result = $this->ask($prompt, $system, 0.7);

        if (!$result) return null;

        $result = preg_replace('/```json\s*/', '', $result);
        $result = preg_replace('/```\s*/', '', $result);
        $result = trim($result);

        $decoded = json_decode($result, true);
        return is_array($decoded) ? $decoded : null;
    }

    public function generateExecutiveSummary(array $projectData): ?string
    {
        $locale = app()->getLocale();
        $lang = $locale === 'fr' ? 'français' : 'English';

        $prompt = "Projet : {$projectData['title']}\n"
            . "Statut : {$projectData['status']}\n"
            . "Progression : {$projectData['progress']}%\n"
            . "Activites : {$projectData['total_activities']} total, {$projectData['completed_activities']} terminees, {$projectData['overdue_activities']} en retard\n"
            . "Budget planifie : {$projectData['planned_budget']}\n"
            . "Budget depense : {$projectData['spent_budget']}\n"
            . "Periode : {$projectData['start_date']} - {$projectData['end_date']}";

        $system = "Tu es un consultant senior en gestion de projets ONG. "
            . "Genere un resume executif en {$lang} (4-6 phrases). "
            . "Inclus : etat d'avancement, points d'attention, recommandations cles. "
            . "Sois factuel, professionnel, actionnable. Pas de formule de politesse.";

        return $this->ask($prompt, $system, 0.6);
    }

    public function analyzeDashboard(array $stats): ?string
    {
        $locale = app()->getLocale();
        $lang = $locale === 'fr' ? 'français' : 'English';

        $prompt = "Projets : {$stats['total_projects']} (actifs: {$stats['active']}, termines: {$stats['completed']}, brouillon: {$stats['draft']})\n"
            . "Activites : {$stats['total_activities']} (en cours: {$stats['activities_in_progress']}, terminees: {$stats['activities_completed']}, en retard: {$stats['activities_overdue']})\n"
            . "Budget total planifie : {$stats['total_budget']}";

        $system = "Tu es un assistant IA pour la gestion de projets ONG. "
            . "Analyse ces statistiques et donne un bref resume en {$lang} (2-3 phrases). "
            . "Mentionne les points critiques (retards, budgets) et une recommandation. "
            . "Sois direct et actionnable. Format texte simple.";

        return $this->askCached($prompt, $system, 300);
    }
}
