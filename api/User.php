<?php

class User
{
    private string $loginId;
    private string $password;

    public function __construct(string $loginId, string $password)
    {
        $this->loginId  = $loginId;
        $this->password = $password;
    }

    public function login(): array
    {
        $url    = Constants::baseApiUrl . 'users/login';
        $header = ['Content-Type: application/json'];
        $body   = json_encode([
            'login_id' => $this->loginId,
            'password' => $this->password,
        ]);

        $res = Curl::post($url, $header, $body);

        return $res['statusCode'] === 200 ?
            [
                'token' => $res['header']['Token'],
                'user'  => $res['body'],
            ]
            :
            [
                'error' => [
                    'id'      => $res['body']['id'],
                    'message' => $res['body']['message'],
                ]
            ];
    }
}
