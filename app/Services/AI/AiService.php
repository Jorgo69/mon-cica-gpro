<?php

namespace App\Services\AI;

use App\Enums\AiProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected ?AiProvider $provider;
    protected ?string $apiKey;
    protected string $baseUrl;
    protected string $model;

    public function __construct()
    {
        $config = AiConfigResolver::resolve();

        if ($config) {
            $this->provider = $config['provider'];
            $this->apiKey = $config['api_key'];
            $this->baseUrl = $config['base_url'];
            $this->model = $config['model'];
        } else {
            $this->provider = null;
            $this->apiKey = null;
            $this->baseUrl = '';
            $this->model = '';
        }
    }

    public static function isConfigured(): bool
    {
        return AiConfigResolver::isAvailable();
    }

    public function ask(string $prompt, string $systemInstruction = '', float $temperature = 0.7): ?string
    {
        if (!$this->provider || !$this->apiKey) {
            return null;
        }

        if ($this->provider === AiProvider::GEMINI) {
            return $this->askGemini($prompt, $systemInstruction, $temperature);
        }

        if ($this->provider === AiProvider::ANTHROPIC) {
            return $this->askAnthropic($prompt, $systemInstruction, $temperature);
        }

        if ($this->provider === AiProvider::COHERE) {
            return $this->askCohere($prompt, $systemInstruction, $temperature);
        }

        // All other providers use OpenAI-compatible format
        return $this->askOpenAiCompatible($prompt, $systemInstruction, $temperature);
    }

    /**
     * OpenAI-compatible API (Groq, OpenAI, Mistral, Custom).
     */
    protected function askOpenAiCompatible(string $prompt, string $systemInstruction, float $temperature): ?string
    {
        try {
            $messages = [];
            if ($systemInstruction) {
                $messages[] = ['role' => 'system', 'content' => $systemInstruction];
            }
            $messages[] = ['role' => 'user', 'content' => $prompt];

            $url = rtrim($this->baseUrl, '/') . '/chat/completions';

            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->post($url, [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => $temperature,
                    'max_tokens' => 2048,
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::warning('AI API error', [
                'provider' => $this->provider->value,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::warning('AI API exception', [
                'provider' => $this->provider->value,
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Google Gemini API (different format).
     */
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

            $url = rtrim($this->baseUrl, '/') . "/models/{$this->model}:generateContent?key={$this->apiKey}";

            $response = Http::timeout(30)->post($url, $body);

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

    /**
     * Anthropic Messages API (Claude).
     */
    protected function askAnthropic(string $prompt, string $systemInstruction, float $temperature): ?string
    {
        try {
            $body = [
                'model' => $this->model,
                'max_tokens' => 2048,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ];

            if ($systemInstruction) {
                $body['system'] = $systemInstruction;
            }

            $url = rtrim($this->baseUrl, '/') . '/messages';

            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ])
                ->post($url, $body);

            if ($response->successful()) {
                return $response->json('content.0.text');
            }

            Log::warning('Anthropic API error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::warning('Anthropic API exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Cohere Chat API (different format).
     */
    protected function askCohere(string $prompt, string $systemInstruction, float $temperature): ?string
    {
        try {
            $body = [
                'model' => $this->model,
                'message' => $prompt,
                'temperature' => $temperature,
            ];

            if ($systemInstruction) {
                $body['preamble'] = $systemInstruction;
            }

            $url = rtrim($this->baseUrl, '/') . '/chat';

            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->post($url, $body);

            if ($response->successful()) {
                return $response->json('text');
            }

            Log::warning('Cohere API error', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::warning('Cohere API exception', ['message' => $e->getMessage()]);
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
