<?php

namespace App\Services;

use App\Exceptions\RateLimitException;
use App\Models\Result;
use App\Models\Service;

class RateLimiter {
    public function validate(Service $service)
    {
        // We will check the service rate limits against the user executions
        // and if the user has already reached the limit, we will throw a RateLimitException
        if ($service->max_generations_per_minute) {
            $this->timeBasedValidation($service, $service->max_generations_per_minute, 'minute', now()->subMinute());
        }

        if ($service->max_generations_per_hour) {
            $this->timeBasedValidation($service, $service->max_generations_per_hour, 'hour', now()->subHour());
        }
    }

    private function timeBasedValidation(Service $service, $maxGenerations, $timeUnit, $since)
    {
        $calls = Result::where([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
        ])
            ->where('created_at', '>=', $since)
            ->get();
        $generations = 0;
        foreach ($calls as $call) {
            $generations += count($call->response);
        }

        if ($generations >= $maxGenerations) {
            throw new RateLimitException('You have reached the usage limit of ' . $maxGenerations . ' generations per ' . $timeUnit . '. Please, try again later or use another tool.');
        }
    }
}
