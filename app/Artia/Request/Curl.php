<?php

namespace App\Artia\Request;

class Curl
{
    /**
     * @var string
     */
    protected string $url;

    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @var array
     */
    protected string $query;

    /**
     * @return object
     * @throws CurlException
     */
    protected function send(): object
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => json_encode([
                'query' => $this->query
            ]),
            CURLOPT_HTTPHEADER => array_merge([
                'OrganizationId: ' . env('CONFIG_API_ORGANIZATION_ID'),
                'UserId: ' . env('CONFIG_API_USER_ID'),
                'Content-Type: application/json',
            ], $this->headers)
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new CurlException(curl_error($ch));
        }

        return json_decode($response);
    }
}
