<?php

namespace App\Enums;

enum AiProvider: string
{
    case GROQ = 'groq';
    case GEMINI = 'gemini';
    case OPENAI = 'openai';
    case ANTHROPIC = 'anthropic';
    case MISTRAL = 'mistral';
    case DEEPSEEK = 'deepseek';
    case COHERE = 'cohere';
    case TOGETHER = 'together';
    case CUSTOM = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::GROQ => 'Groq (Llama)',
            self::GEMINI => 'Google Gemini',
            self::OPENAI => 'OpenAI',
            self::ANTHROPIC => 'Anthropic (Claude)',
            self::MISTRAL => 'Mistral AI',
            self::DEEPSEEK => 'DeepSeek',
            self::COHERE => 'Cohere',
            self::TOGETHER => 'Together AI',
            self::CUSTOM => __('enums.ai_provider.custom'),
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::GROQ => 'zap',
            self::GEMINI => 'sparkles',
            self::OPENAI => 'brain',
            self::ANTHROPIC => 'message-circle',
            self::MISTRAL => 'wind',
            self::DEEPSEEK => 'search',
            self::COHERE => 'layers',
            self::TOGETHER => 'combine',
            self::CUSTOM => 'server',
        };
    }

    public function defaultModel(): string
    {
        return match ($this) {
            self::GROQ => 'llama-3.3-70b-versatile',
            self::GEMINI => 'gemini-2.0-flash',
            self::OPENAI => 'gpt-4o-mini',
            self::ANTHROPIC => 'claude-haiku-4-5-20251001',
            self::MISTRAL => 'mistral-small-latest',
            self::DEEPSEEK => 'deepseek-chat',
            self::COHERE => 'command-r',
            self::TOGETHER => 'meta-llama/Llama-3.3-70B-Instruct-Turbo',
            self::CUSTOM => '',
        };
    }

    public function baseUrl(): string
    {
        return match ($this) {
            self::GROQ => 'https://api.groq.com/openai/v1',
            self::GEMINI => 'https://generativelanguage.googleapis.com/v1beta',
            self::OPENAI => 'https://api.openai.com/v1',
            self::ANTHROPIC => 'https://api.anthropic.com/v1',
            self::MISTRAL => 'https://api.mistral.ai/v1',
            self::DEEPSEEK => 'https://api.deepseek.com/v1',
            self::COHERE => 'https://api.cohere.ai/v1',
            self::TOGETHER => 'https://api.together.xyz/v1',
            self::CUSTOM => '',
        };
    }

    /**
     * Providers using OpenAI-compatible API format (chat/completions).
     */
    public function isOpenAiCompatible(): bool
    {
        return in_array($this, [
            self::GROQ,
            self::OPENAI,
            self::MISTRAL,
            self::DEEPSEEK,
            self::TOGETHER,
            self::CUSTOM,
        ]);
    }

    /**
     * Whether this provider offers a free tier.
     */
    public function hasFreeTier(): bool
    {
        return in_array($this, [self::GROQ, self::GEMINI, self::COHERE, self::TOGETHER]);
    }

    /**
     * Short description for the guide.
     */
    public function description(): string
    {
        return __("ai.providers.{$this->value}.description");
    }

    /**
     * Pricing hint for the guide.
     */
    public function pricing(): string
    {
        return __("ai.providers.{$this->value}.pricing");
    }

    /**
     * URL to get an API key.
     */
    public function signupUrl(): string
    {
        return match ($this) {
            self::GROQ => 'https://console.groq.com/keys',
            self::GEMINI => 'https://aistudio.google.com/apikey',
            self::OPENAI => 'https://platform.openai.com/api-keys',
            self::ANTHROPIC => 'https://console.anthropic.com/settings/keys',
            self::MISTRAL => 'https://console.mistral.ai/api-keys',
            self::DEEPSEEK => 'https://platform.deepseek.com/api_keys',
            self::COHERE => 'https://dashboard.cohere.com/api-keys',
            self::TOGETHER => 'https://api.together.xyz/settings/api-keys',
            self::CUSTOM => '',
        };
    }
}
