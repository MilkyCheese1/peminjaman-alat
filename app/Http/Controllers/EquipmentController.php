<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{
    /**
     * Display a listing of all equipment
     */
    public function index()
    {
        try {
            $equipment = Equipment::with('category')
                ->select('id_equipment', 'id_category', 'name', 'description', 'quantity', 'condition', 'photo', 'is_available', 'fine_per_day')
                ->get()
                ->map(function ($item) {
                    // Calculate available quantity (total - currently borrowed)
                    $borrowed = $item->borrowings()->where('status', '!=', 'returned')->where('status', '!=', 'rejected')->count();
                    $available = max(0, $item->quantity - $borrowed);
                    
                    return [
                        'id_equipment' => $item->id_equipment,
                        'id_category' => $item->id_category,
                        'nama_alat' => $item->name,
                        'deskripsi' => $item->description,
                        'total_stok' => $item->quantity,
                        'kondisi' => $item->condition,
                        'gambar' => $item->photo ? config('app.url') . '/storage/' . $item->photo : null,
                        'fine_per_day' => $item->fine_per_day,
                        'available_quantity' => $available,
                        'category' => $item->category ? [
                            'id_kategori' => $item->category->id_kategori,
                            'nama_kategori' => $item->category->nama_kategori
                        ] : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $equipment,
                'message' => 'Equipment retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Accept both field naming conventions
            $validated = $request->validate([
                'id_kategori' => 'nullable|exists:categories,id_category',
                'id_category' => 'nullable|exists:categories,id_category',
                'nama_alat' => 'required_without:name|string|max:100',
                'name' => 'required_without:nama_alat|string|max:100',
                'deskripsi' => 'nullable|string',
                'description' => 'nullable|string',
                'total_stok' => 'required_without:quantity|integer|min:1',
                'quantity' => 'required_without:total_stok|integer|min:1',
                'kondisi' => 'required_without:condition|in:baik,sedang,rusak,good,fair,poor',
                'condition' => 'required_without:kondisi|in:good,fair,poor,baik,sedang,rusak',
                'fine_per_day' => 'nullable|numeric|min:0',
                'photo' => 'required|image|mimes:jpeg,png,webp|max:5120', // max 5MB
            ]);

            // Map field names to database columns
            $data = [
                'id_category' => $validated['id_kategori'] ?? $validated['id_category'] ?? null,
                'name' => $validated['nama_alat'] ?? $validated['name'],
                'description' => $validated['deskripsi'] ?? $validated['description'] ?? null,
                'quantity' => $validated['total_stok'] ?? $validated['quantity'],
                'condition' => $this->mapConditionValue($validated['kondisi'] ?? $validated['condition']),
                'is_available' => true,
                'fine_per_day' => $validated['fine_per_day'] ?? 50000,
            ];

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photoFile = $request->file('photo');
                // Store in public disk under equipment folder
                $photoPath = $photoFile->store('equipment', 'public');
                $data['photo'] = $photoPath;
            }

            $equipment = Equipment::create($data);
            $equipment->load('category');

            return response()->json([
                'success' => true,
                'data' => $this->formatEquipmentResponse($equipment),
                'message' => 'Equipment created successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        try {
            $equipment->load('category', 'borrowings');

            return response()->json([
                'success' => true,
                'data' => $this->formatEquipmentResponse($equipment),
                'message' => 'Equipment retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipment $equipment)
    {
        try {
            // Accept both field naming conventions
            $validated = $request->validate([
                'id_kategori' => 'nullable|exists:categories,id_category',
                'id_category' => 'nullable|exists:categories,id_category',
                'nama_alat' => 'string|max:100',
                'name' => 'string|max:100',
                'deskripsi' => 'nullable|string',
                'description' => 'nullable|string',
                'total_stok' => 'integer|min:1',
                'quantity' => 'integer|min:1',
                'kondisi' => 'in:baik,sedang,rusak,good,fair,poor',
                'condition' => 'in:good,fair,poor,baik,sedang,rusak',
                'fine_per_day' => 'nullable|numeric|min:0',
                'photo' => 'nullable|image|mimes:jpeg,png,webp|max:5120', // max 5MB, optional for updates
            ]);

            // Map field names to database columns
            $data = [];
            if (!empty($validated['id_kategori']) || !empty($validated['id_category'])) {
                $data['id_category'] = $validated['id_kategori'] ?? $validated['id_category'];
            }
            if (!empty($validated['nama_alat'])) {
                $data['name'] = $validated['nama_alat'];
            } elseif (!empty($validated['name'])) {
                $data['name'] = $validated['name'];
            }
            if (!empty($validated['deskripsi'])) {
                $data['description'] = $validated['deskripsi'];
            } elseif (!empty($validated['description'])) {
                $data['description'] = $validated['description'];
            }
            if (!empty($validated['total_stok'])) {
                $data['quantity'] = $validated['total_stok'];
            } elseif (!empty($validated['quantity'])) {
                $data['quantity'] = $validated['quantity'];
            }
            if (!empty($validated['kondisi'])) {
                $data['condition'] = $this->mapConditionValue($validated['kondisi']);
            } elseif (!empty($validated['condition'])) {
                $data['condition'] = $this->mapConditionValue($validated['condition']);
            }
            if (!empty($validated['fine_per_day'])) {
                $data['fine_per_day'] = $validated['fine_per_day'];
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($equipment->photo && Storage::disk('public')->exists($equipment->photo)) {
                    Storage::disk('public')->delete($equipment->photo);
                }
                // Store new photo
                $photoFile = $request->file('photo');
                $photoPath = $photoFile->store('equipment', 'public');
                $data['photo'] = $photoPath;
            }

            $equipment->update($data);
            $equipment->load('category');

            return response()->json([
                'success' => true,
                'data' => $this->formatEquipmentResponse($equipment),
                'message' => 'Equipment updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validation failed'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        try {
            $equipment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Equipment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete equipment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available quantity for equipment
     */
    public function getAvailable($id)
    {
        try {
            $equipment = Equipment::find($id);

            if (!$equipment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Equipment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id_equipment' => $equipment->id_equipment,
                    'name' => $equipment->name,
                    'total_quantity' => $equipment->quantity,
                    'available_quantity' => $equipment->getAvailableQuantity(),
                ],
                'message' => 'Available quantity retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve available quantity: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map condition value from Indonesian to English
     */
    private function mapConditionValue($condition)
    {
        $map = [
            'baik' => 'good',
            'sedang' => 'fair',
            'rusak' => 'poor',
            'good' => 'good',
            'fair' => 'fair',
            'poor' => 'poor',
        ];
        return $map[$condition] ?? $condition;
    }

    /**
     * Format equipment response with calculated available quantity
     */
    private function formatEquipmentResponse($equipment)
    {
        $borrowed = $equipment->borrowings()->where('status', '!=', 'returned')->where('status', '!=', 'rejected')->count();
        $available = max(0, $equipment->quantity - $borrowed);

        return [
            'id_equipment' => $equipment->id_equipment,
            'id_category' => $equipment->id_category,
            'nama_alat' => $equipment->name,
            'deskripsi' => $equipment->description,
            'total_stok' => $equipment->quantity,
            'kondisi' => $equipment->condition,
            'gambar' => $equipment->photo ? config('app.url') . '/storage/' . $equipment->photo : null,
            'fine_per_day' => $equipment->fine_per_day,
            'available_quantity' => $available,
            'category' => $equipment->category ? [
                'id_category' => $equipment->category->id_category,
                'nama_kategori' => $equipment->category->name
            ] : null,
        ];
    }
}

