<?php

class Emoji
{
    private array $user;
    private string $token;

    public function __construct(array $user, string $token)
    {
        $this->user  = $user;
        $this->token = $token;
    }

    public function create(string $imagePath): array
    {
        $url    = Constants::baseApiUrl . 'emoji';
        $header = [
            'Content-Type: multipart/form-data',
            "Authorization: Bearer {$this->token}",
        ];
        $body = [
            'image' => new CURLFile($imagePath, Image::mimeType($imagePath)),
            'emoji' => json_encode(
                [
                    'name'       => pathinfo($imagePath, PATHINFO_FILENAME),
                    'creator_id' => $this->user['id']
                ]
            )
        ];

        $res = Curl::post($url, $header, $body);

        return $this->returnArray($res);
    }

    public function getList(): array
    {
        $url    = Constants::baseApiUrl . 'emoji';
        $header = [
            "Authorization: Bearer {$this->token}",
        ];

        $res = Curl::get($url, $header);

        return $this->returnArray($res);
    }

    public function getById(string $id): array
    {
        $url    = Constants::baseApiUrl . "emoji/{$id}";
        $header = [
            "Authorization: Bearer {$this->token}",
        ];

        $res = Curl::get($url, $header);

        return $this->returnArray($res);
    }

    public function getByName(string $name): array
    {
        $url    = Constants::baseApiUrl . "emoji/name/{$name}";
        $header = [
            "Authorization: Bearer {$this->token}",
        ];

        $res = Curl::get($url, $header);

        return $this->returnArray($res);
    }

    public function getImageById(string $id): string | array
    {
        $url    = Constants::baseApiUrl . "emoji/{$id}/image";
        $header = [
            "Authorization: Bearer {$this->token}",
        ];

        $res = Curl::get($url, $header);

        return $this->returnArray($res);
    }

    public function autocomplete(string $name): array
    {
        $url    = Constants::baseApiUrl . "emoji/autocomplete?name={$name}";
        $header = [
            "Authorization: Bearer {$this->token}",
        ];

        $res = Curl::get($url, $header);

        return $this->returnArray($res);
    }

    public function search(string $term, string $prefixOnly = null): array
    {
        $url    = Constants::baseApiUrl . 'emoji/search';
        $header = [
            "Authorization: Bearer {$this->token}",
        ];
        $body = json_encode([
            'term'        => $term,
            'prefix_only' => $prefixOnly,
        ]);

        $res = Curl::post($url, $header, $body);

        return $this->returnArray($res);
    }

    public function delete(string $id): array
    {
        $url    = Constants::baseApiUrl . "emoji/{$id}";
        $header = [
            "Authorization: Bearer {$this->token}",
        ];

        $res = Curl::delete($url, $header);

        return $this->returnArray($res);
    }

    private function returnArray(array $res): string | array
    {
        return $res['statusCode'] === 200 ?
            $res['body']
            :
            [
                'error' => [
                    'id'      => $res['body']['id'],
                    'message' => $res['body']['message'],
                ],
            ];
    }
}
