<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class Gpt3 {
    private $engine_ = 'davinci';
    private $temperature_ = 0.9;
    private $topP_ = 1;
    private $n_ = 1;
    private $maxTokens_ = 16;
    private $presencePenalty_ = 0;
    private $freqPenalty_ = 0;
    private $bestOf_ = 1;

    public function davinci()
    {
        return $this->engine('davinci');
    }

    public function engine($name)
    {
        $this->engine = $name;
        return $this;
    }

    public function temperature($temp)
    {
        assert($temp >= 0);
        assert($temp <= 1);
        $this->temperature_ = $temp;
        return $this;
    }

    public function topP($value)
    {
        assert($value >= 0);
        assert($value <= 1);
        $this->topP_ = $value;
        return $this;
    }

    public function presencePenalty($value)
    {
        assert($value >= 0);
        assert($value <= 1);
        $this->presencePenalty_ = $value;
        return $this;
    }

    public function freqPenalty($value)
    {
        assert($value >= 0);
        assert($value <= 1);
        $this->freqPenalty_ = $value;
        return $this;
    }

    public function bestOf($value)
    {
        assert($value >= 0);
        $this->bestOf_ = $value;
        return $this;
    }

    public function tokens($nTokens)
    {
        assert($nTokens > 0);
        $this->maxTokens_ = min(2048, $nTokens);
        return $this;
    }

    public function take($value)
    {
        assert($value > 0);
        assert($value <= 10);
        $this->n_ = $value;
        return $this;
    }

    public function completion($prompt)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.gpt3')
        ])
            ->post('https://api.openai.com/v1/engines/' . $this->engine . '/completions', [
                'prompt' => $prompt,
                'max_tokens' => $this->maxTokens_,
                'temperature' => $this->temperature_,
                'top_p' => $this->topP_,
                'n' => $this->n_,
                'presence_penalty' => $this->presencePenalty_,
                'frequency_penalty' => $this->freqPenalty_,
                'best_of' => $this->bestOf_,
                'stop' => $this->getStopWord($prompt),
            ]);

        $response->throw();
        return $response->json()['choices'];
    }

    public function isSafe ($prompt)
    {
        $prompt = strpos($prompt, '###') !== false ? explode('###', $prompt) : explode("\n\n", $prompt);
        $prompt = $prompt[count($prompt) - 1];
        $prompt = "<|endoftext|>" . $prompt . "\n--\nLabel:";
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.gpt3')
        ])
            ->post('https://api.openai.com/v1/engines/content-filter-alpha-c4/completions', [
                'prompt' => $prompt,
                'max_tokens' => 1,
                'temperature' => 0.0,
                'top_p' => 0,
                'logprobs' => 10,
            ]);

        $response->throw();
        $choice = $response->json()['choices'][0];
        $minSafety = config('services.openai.gpt3_content_filter_threshold');
        return intval($choice['text']) <= $minSafety;
    }

    private function getStopWord($prompt)
    {
        if (strpos($prompt, '###') !== false) {
            return '###';
        }

        return "\n";
    }
}
