<?php

namespace App\Services;

use App\Models\PoDetails;
use Illuminate\Support\Facades\DB;

class PoNumberFormatter
{
    public static function format(PoDetails $po): string
    {
        $parts = explode('/', $po->po_number);

        $prefix = $parts[0]; // "1"
        $rest = implode('/', array_slice($parts, 1)); // "PO/3/2026"

        if (!$po->isActive) {
            return $po->po_number . ' (CLOSED)';
        }

        $suffix = str_repeat('A', $po->revision);

        return $prefix . $suffix . '/' . $rest;
    }
}
