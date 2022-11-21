<?php

namespace Sirj3x\Jxt;

class JxtHelper
{
    public static function getGuards(): array
    {
        $final = [];

        $guards = config('auth.guards');
        if (isset($guards["web"])) unset($guards["web"]);
        if (isset($guards["sanctum"])) unset($guards["sanctum"]);

        foreach ($guards as $guardName => $guardDetail) {
            $final[] = $guardName;
        }

        return $final;
    }

    public static function getAppliedGuards($action): array
    {
        if (!isset($action["middleware"])) return [];

        foreach ($action["middleware"] as $item) {
            if (strpos($item, 'auth:') !== false) {
                return explode(',', str_replace('auth:', '', $item));
            } elseif ($item == 'auth') {
                return self::getGuards();
            }
        }

        return [];
    }

    public static function getGuardModel($guard)
    {
        $guardProvider = config('auth.guards')[$guard]["provider"] ?? null;
        if ($guardProvider === null) return null;
        $guardModel = config('auth.providers')["$guardProvider"]["model"] ?? null;
        if ($guardModel === null) return null;
        return $guardModel;
    }
}
