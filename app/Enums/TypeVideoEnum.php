<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TypeVideoEnum extends Enum
{
    const TRENDING = "trending";
    const HASHTAG = "hashtag";
    const USER = "user";
    const SOUND = "sound";
}
