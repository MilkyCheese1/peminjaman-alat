<?php

namespace App\Services;

use App\Models\Borrowing;
use App\Models\Tool;
use Illuminate\Support\Carbon;

class BorrowingFineService
{
    public function resolveBorrowingPrice(Borrowing $borrowing): int
    {
        if ((int) $borrowing->alat_harga_asli > 0) {
            return (int) $borrowing->alat_harga_asli;
        }

        if ($borrowing->relationLoaded('alat') && $borrowing->alat) {
            return (int) $borrowing->alat->harga_asli;
        }

        if ($borrowing->alat_id) {
            $tool = Tool::query()->find($borrowing->alat_id);
            return (int) ($tool?->harga_asli ?? 0);
        }

        return 0;
    }

    public function resolveToolPriceByInput(mixed $alatId, mixed $alatHargaAsli): int
    {
        $price = (int) ($alatHargaAsli ?? 0);
        if ($price > 0) {
            return $price;
        }

        if (!$alatId) {
            return 0;
        }

        $tool = Tool::query()->find((int) $alatId);

        return (int) ($tool?->harga_asli ?? 0);
    }

    public function resolveTotalFine(array $data, array $fineBreakdown): int
    {
        $manual = isset($data['biaya']) ? (int) $data['biaya'] : null;
        if ($manual !== null && $manual > 0) {
            return $manual;
        }

        return (int) ($fineBreakdown['total'] ?? 0);
    }

    public function calculateFineBreakdown(
        int $price,
        string $dueDate,
        ?string $actualDate = null,
        ?string $statusPengembalian = null,
        ?string $kondisiPengembalian = null,
    ): array {
        $referenceDate = $actualDate ?: Carbon::now()->toDateString();
        $daysLate = 0;

        if ($dueDate !== '') {
            $daysLate = max(
                0,
                Carbon::parse($dueDate)->startOfDay()->diffInDays(Carbon::parse($referenceDate)->startOfDay(), false),
            );
        }

        $kerusakan = $statusPengembalian === 'Dikembalikan' && $kondisiPengembalian === 'Rusak'
            ? $price
            : 0;

        $kehilangan = $statusPengembalian === 'Dikembalikan' && $kondisiPengembalian === 'Hilang'
            ? (int) round($price * 1.5)
            : 0;

        $keterlambatan = 0;
        if ($daysLate > 0) {
            $dailyBase = (int) round($price * 0.5);
            $dailyPenalty = (int) round($price);

            if ($daysLate <= 2) {
                $keterlambatan = $dailyBase * $daysLate;
            } else {
                $keterlambatan = ($dailyBase * 2) + ($dailyPenalty * ($daysLate - 2));
            }
        }

        return [
            'daysLate' => $daysLate,
            'kerusakan' => $kerusakan,
            'kehilangan' => $kehilangan,
            'keterlambatan' => $keterlambatan,
            'total' => $kerusakan + $kehilangan + $keterlambatan,
        ];
    }
}
