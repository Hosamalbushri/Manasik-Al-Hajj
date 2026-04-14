<?php

namespace Webkul\Manasik\Support;

final class ManasikGuideCompletion
{
    public static function allCompleted(array $completed, int $expectedLength): bool
    {
        if ($expectedLength <= 0 || count($completed) !== $expectedLength) {
            return false;
        }

        foreach ($completed as $v) {
            if (! (bool) $v) {
                return false;
            }
        }

        return true;
    }

    public static function shouldRecordNewFullCompletion(array $oldCompleted, array $newCompleted, int $expectedLength): bool
    {
        if (! self::allCompleted($newCompleted, $expectedLength)) {
            return false;
        }

        return ! self::allCompleted($oldCompleted, $expectedLength);
    }
}
