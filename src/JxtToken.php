<?php

namespace Sirj3x\Jxt;

/*
 * Write by Mehdi Hashemi (sirj3x)
 * */

use Illuminate\Support\Facades\Hash;

class JxtToken
{
    public static function encode($guard, $user_id, ...$additional): string
    {
        $array = [
            'created_at' => strtotime('now'),
            'guard' => $guard,
            'user_id' => $user_id,
            'expire_at' => strtotime('now') + config('jxt.expiration_period'),
        ];

        // nullable (check additional)
        if (count($additional) > 0) {
            foreach ($additional as $item) {
                foreach ($item as $key => $value) {
                    $array['additional'][$key] = $value;
                }
            }

        }

        return bin2hex(openssl_encrypt(json_encode($array), config('jxt.algo'), config('jxt.passphrase')));
    }

    public static function decode($token): array
    {
        try {
            $decoded = json_decode(openssl_decrypt(hex2bin($token), config('jxt.algo'), config('jxt.passphrase')), true);

            // validation (check decoded array length)
            if ($decoded == null || !is_array($decoded) || count($decoded) < 4) return [];

            return $decoded;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public static function customEncode($string): string
    {
        return bin2hex(openssl_encrypt($string, config('jxt.algo'), config('jxt.passphrase')));
    }

    public static function customDecode($string)
    {
        try {
            return openssl_decrypt(hex2bin($string), config('jxt.algo'), config('jxt.passphrase'));
        } catch (\Exception $exception) {
            return null;
        }
    }

    public static function loginWithToken($token)
    {
        try {
            $tokenData = self::decode($token);
            if (count($tokenData) == 0) return null;
            $guardModel = JxtHelper::getGuardModel($tokenData["guard"]);
            if (!$guardModel) return null;
            $query = new $guardModel;
            $user = $query->find($tokenData["user_id"])->toArray();
        } catch (\Exception $exception) {
            return null;
        }
        if (!$user) return null;
        $user['guard'] = $tokenData["guard"];
        return $user;
    }

    public static function login($guard, $login, $password)
    {
        $guardModel = JxtHelper::getGuardModel($guard);
        if (!$guardModel) return null;
        $query = new $guardModel;
        $user = $query->where(config('jxt.login_field'), $login)->first();
        if (!$user) return null;
        if (!Hash::check($password, $user->password)) return null;
        return $user;
    }
}
