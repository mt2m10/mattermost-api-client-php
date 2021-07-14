<?php

class Curl
{
    public static function get(string $url, array $header): array
    {
        return self::request($url, 'GET', $header);
    }

    public static function post(string $url, array $header, string | array $body): array
    {
        $options = [
            CURLOPT_POSTFIELDS => $body,
        ];

        return self::request($url, 'POST', $header, $options);
    }

    public static function delete(string $url, array $header): array
    {
        return self::request($url, 'DELETE', $header);
    }

    private static function request(string $url, string $method, array $header, array $options = []): array
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, $options + [
            CURLOPT_HEADER         => true,
            CURLOPT_HTTPHEADER     => $header,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        ]);

        $response   = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $resHeader = [];
        foreach (explode("\n", substr($response, 0, $headerSize)) as $line)
        {
            if (preg_match('#^(.+): (.+)$#', $line, $matches))
            {
                $resHeader[$matches[1]] = trim($matches[2]);
            }
        }
        $resBody = json_decode(substr($response, $headerSize), true) ?? substr($response, $headerSize);

        return [
            'statusCode' => $statusCode,
            'header'     => $resHeader,
            'body'       => $resBody,
        ];
    }
}
