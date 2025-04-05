<?php

namespace App\Repositories;

use App\Dto\ApiResult;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class BaseRepository
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.api_base_url');
    }

    abstract protected function getToken(): ?string;

    protected function request(
        string $url,
        string $method = 'GET',
        array $data = [],
        bool $isMultipart = false
    ): ApiResult
    {
        $token = $this->getToken();
        $fullUrl = $this->baseUrl . $url;

        $request = Http::withToken($token)
            ->acceptJson()
            ->withOptions(['http_errors' => false]);

        if ($isMultipart) {
            $request = $request->asMultipart();

            foreach ($data as $field) {
                if (
                    isset($field['name'], $field['contents'], $field['filename'])
                ) {
                    $request = $request->attach(
                        $field['name'],
                        $field['contents'],
                        $field['filename']
                    );
                }
            }
            $httpMethod = strtolower($method);
            $response = $request->{$httpMethod}($fullUrl);
        }
        else {
            $request = $request->withHeaders(['Content-Type' => 'application/json']);
            $response = $request->send($method, $fullUrl, ['json' => $data]);
        }

        if (app()->environment('local') || config('app.debug')) {
            error_log("API Request:");
            error_log("URL: $method $fullUrl");
            error_log("Token: $token");

            if ($isMultipart) {
                foreach ($data as $f) {
                    error_log("File: {$f['name']} => {$f['filename']}");
                }
            } else {
                error_log("Payload: " . json_encode($data));
            }

            error_log("API Response:");
            error_log("Status: " . $response->status());
            error_log("Body: " . $response->body());
            error_log("------------------------");
        }

        if ($response->successful()) {
            return new ApiResult(true, $response->json());
        }

        $error = $response->json();
        return new ApiResult(false, null, $error['error_code'] ?? null, $response->status());
    }
}
