<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Services\BookingValidationService;
use App\Services\StockManagementService;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    private $bookingService;
    private $stockService;

    public function __construct(
        BookingValidationService $bookingService,
        StockManagementService $stockService
    ) {
        $this->bookingService = $bookingService;
        $this->stockService = $stockService;
    }

    /**
     * Get all alat with filtering and search
     * 
     * Query parameters:
     * - category: filter by kategori id
     * - search: search by nama_alat, sku, or deskripsi
     * - status: filter by status_alat (tersedia, booked, in_use, maintenance)
     * - date_from & date_to: check availability on specific dates
     * - sort: sort by (nama_alat, stok, status_alat)
     */
    public function index(Request $request)
    {
        $query = Alat::with('kategori');

        // Filter by category
        if ($request->has('category')) {
            $query->where('id_kategori', $request->category);
        }

        // Search by name, SKU, or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_alat', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('deskripsi', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status_alat', $request->status);
        }

        // Filter by availability on specific dates
        if ($request->has('date_from') && $request->has('date_to')) {
            $date_from = $request->date_from;
            $date_to = $request->date_to;
            
            // Get all booked alats in this date range
            $booked_alats = \DB::table('peminjaman')
                ->whereIn('status', ['pending', 'booked', 'in_use'])
                ->where(function ($q) use ($date_from, $date_to) {
                    $q->where('tgl_peminjaman', '<=', $date_to)
                        ->where('tgl_kembali', '>=', $date_from);
                })
                ->pluck('id_alat')
                ->unique();

            $query->whereNotIn('id_alat', $booked_alats->toArray());
        }

        // Sort
        if ($request->has('sort')) {
            $sort = $request->sort;
            if (in_array($sort, ['nama_alat', 'stok', 'status_alat'])) {
                $query->orderBy($sort);
            }
        }

        $alats = $query->get()->map(function ($alat) {
            return array_merge($alat->toArray(), [
                'stock_info' => $this->stockService->getStockInfo($alat->id_alat),
                'unavailable_periods' => $this->bookingService->getUnavailablePeriods($alat->id_alat),
            ]);
        });

        return response()->json([
            'success' => true,
            'count' => count($alats),
            'data' => $alats,
        ]);
    }

    /**
     * Get alat by ID with detailed information
     */
    public function show($id)
    {
        $alat = Alat::with('kategori')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => array_merge($alat->toArray(), [
                'stock_info' => $this->stockService->getStockInfo($id),
                'unavailable_periods' => $this->bookingService->getUnavailablePeriods($id),
                'active_bookings' => $this->bookingService->getActiveBookings($id),
            ]),
        ]);
    }

    /**
     * Create new alat with comprehensive validations
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:50',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'stok' => 'required|integer|min:1',
            'dipinjam' => 'sometimes|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['dipinjam'] = $validated['dipinjam'] ?? 0;

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('alat', 'public');
            $validated['gambar'] = $path;
        }

        $alat = Alat::create($validated);
        $alat->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil ditambahkan',
            'data' => $alat,
        ], 201);
    }

    /**
     * Update alat
     */
    public function update(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $validated = $request->validate([
            'nama_alat' => 'sometimes|string|max:50',
            'id_kategori' => 'sometimes|exists:kategori,id_kategori',
            'stok' => 'sometimes|integer|min:1',
            'dipinjam' => 'sometimes|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($alat->gambar && \Storage::disk('public')->exists($alat->gambar)) {
                \Storage::disk('public')->delete($alat->gambar);
            }
            $path = $request->file('gambar')->store('alat', 'public');
            $validated['gambar'] = $path;
        }

        $alat->update($validated);
        $alat->refresh()->load('kategori');

        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil diperbarui',
            'data' => $alat,
        ]);
    }

    /**
     * Delete alat
     */
    public function destroy($id)
    {
        $alat = Alat::findOrFail($id);
        $alat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil dihapus',
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories()
    {
        $categories = Kategori::all();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Check available dates for an alat
     */
    public function getAvailableDates($id, Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
        ]);

        $alat = Alat::findOrFail($id);

        $available_dates = $this->bookingService->getAvailableDates(
            $id,
            $request->date_from,
            $request->date_to
        );

        return response()->json([
            'success' => true,
            'alat_id' => $id,
            'alat_nama' => $alat->nama_alat,
            'date_range' => [
                'from' => $request->date_from,
                'to' => $request->date_to,
            ],
            'available_count' => count($available_dates),
            'available_dates' => array_map(function ($date) {
                return $date->format('Y-m-d');
            }, $available_dates),
        ]);
    }

    /**
     * Set alat to maintenance mode
     */
    public function setMaintenance($id, Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $alat = Alat::findOrFail($id);
        $this->stockService->setMaintenance($id);
        $alat->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Alat diset ke mode maintenance',
            'data' => $alat,
        ]);
    }

    /**
     * Get available stock info
     */
    public function getStockInfo($id)
    {
        $stock_info = $this->stockService->getStockInfo($id);

        return response()->json([
            'success' => true,
            'data' => $stock_info,
        ]);
    }
}
