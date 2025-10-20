<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentAssignment;
use App\Models\EquipmentMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\SyncsStorage;

class EquipmentController extends Controller
{
    use SyncsStorage;
    public function index(Request $request)
    {
        $query = Equipment::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('equipment_code', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $equipment = $query->latest()->paginate(20);
        $categories = EquipmentCategory::where('is_active', true)->get();

        return view('equipment.index', compact('equipment', 'categories'));
    }

    public function create()
    {
        $categories = EquipmentCategory::where('is_active', true)->get();
        return view('equipment.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:equipment_categories,id',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:excellent,good,fair,poor,damaged',
            'warranty_expiry' => 'nullable|date',
            'maintenance_interval_days' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        $validated['equipment_code'] = 'EQP-' . strtoupper(Str::random(8));
        $validated['status'] = 'available';

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('equipment/images', 'public');
        }

        $equipment = Equipment::create($validated);
        
        // Sync storage to public (for systems without symlink support)
        $this->syncStorageToPublic();

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Equipment added successfully.');
    }

    public function show(Equipment $equipment)
    {
        $equipment->load(['category', 'assignments', 'maintenanceRecords']);
        return view('equipment.show', compact('equipment'));
    }

    public function assign(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'assigned_date' => 'required|date',
            'expected_return_date' => 'nullable|date',
            'purpose' => 'nullable|string',
        ]);

        $validated['equipment_id'] = $equipment->id;
        $validated['assigned_by'] = auth()->id();
        $validated['status'] = 'active';

        EquipmentAssignment::create($validated);

        $equipment->update(['status' => 'in_use']);

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Equipment assigned successfully.');
    }

    public function returnEquipment(Request $request, EquipmentAssignment $assignment)
    {
        $validated = $request->validate([
            'return_notes' => 'nullable|string',
        ]);

        $assignment->update([
            'return_date' => now(),
            'status' => 'returned',
            'return_notes' => $validated['return_notes'] ?? null,
        ]);

        $assignment->equipment->update(['status' => 'available']);

        return redirect()->route('equipment.show', $assignment->equipment)
            ->with('success', 'Equipment returned successfully.');
    }

    public function edit(Equipment $equipment)
    {
        $categories = EquipmentCategory::where('is_active', true)->get();
        return view('equipment.edit', compact('equipment', 'categories'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:equipment_categories,id',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'condition' => 'required|in:excellent,good,fair,poor,damaged',
            'warranty_expiry' => 'nullable|date',
            'maintenance_interval_days' => 'nullable|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($equipment->image) {
                Storage::disk('public')->delete($equipment->image);
            }
            $validated['image'] = $request->file('image')->store('equipment/images', 'public');
        }

        $equipment->update($validated);
        $this->syncStorageToPublic();

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->image) {
            Storage::disk('public')->delete($equipment->image);
        }
        
        $equipment->delete();
        $this->syncStorageToPublic();

        return redirect()->route('equipment.index')
            ->with('success', 'Equipment deleted successfully.');
    }

    public function analytics()
    {
        $totalEquipment = Equipment::count();
        $totalValue = Equipment::sum('purchase_price');
        $availableEquipment = Equipment::where('status', 'available')->count();
        $inUseEquipment = Equipment::where('status', 'in_use')->count();
        $maintenanceEquipment = Equipment::where('status', 'maintenance')->count();

        $equipmentByCategory = Equipment::select('equipment_categories.name', \DB::raw('count(*) as total'))
            ->join('equipment_categories', 'equipment.category_id', '=', 'equipment_categories.id')
            ->groupBy('equipment_categories.name')
            ->get();

        $equipmentByCondition = Equipment::select('condition', \DB::raw('count(*) as total'))
            ->groupBy('condition')
            ->get();

        $valueByCategory = Equipment::select('equipment_categories.name', \DB::raw('sum(purchase_price) as total_value'))
            ->join('equipment_categories', 'equipment.category_id', '=', 'equipment_categories.id')
            ->groupBy('equipment_categories.name')
            ->get();

        $maintenanceDue = Equipment::needsMaintenance()->count();
        $maintenanceOverdue = Equipment::overdueMaintenance()->count();

        return view('equipment.analytics', compact(
            'totalEquipment',
            'totalValue',
            'availableEquipment',
            'inUseEquipment',
            'maintenanceEquipment',
            'equipmentByCategory',
            'equipmentByCondition',
            'valueByCategory',
            'maintenanceDue',
            'maintenanceOverdue'
        ));
    }

    public function generateQr(Equipment $equipment)
    {
        return view('equipment.qr-code', compact('equipment'));
    }

    public function bulkGenerateQr()
    {
        $equipment = Equipment::with('category')->get();
        return view('equipment.bulk-qr', compact('equipment'));
    }

    public function maintenance()
    {
        $maintenanceDue = Equipment::with('category')
            ->needsMaintenance()
            ->get();

        $maintenanceOverdue = Equipment::with('category')
            ->overdueMaintenance()
            ->get();

        $allMaintenance = EquipmentMaintenance::with(['equipment', 'equipment.category'])
            ->latest()
            ->paginate(20);

        return view('equipment.maintenance', compact('maintenanceDue', 'maintenanceOverdue', 'allMaintenance'));
    }

    public function addMaintenance(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'maintenance_date' => 'required|date',
            'maintenance_type' => 'required|in:routine,repair,inspection,upgrade',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'performed_by' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'next_maintenance_date' => 'nullable|date|after:maintenance_date',
        ]);

        $validated['equipment_id'] = $equipment->id;
        $validated['recorded_by'] = auth()->id();

        EquipmentMaintenance::create($validated);

        $equipment->update([
            'last_maintenance_date' => $validated['maintenance_date'],
            'next_maintenance_date' => $validated['next_maintenance_date'] ?? null,
            'status' => 'available',
        ]);

        return redirect()->route('equipment.show', $equipment)
            ->with('success', 'Maintenance record added successfully.');
    }

    public function export(Request $request)
    {
        $query = Equipment::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $equipment = $query->get();

        $filename = 'equipment_inventory_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($equipment) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Equipment Code', 'Name', 'Category', 'Brand', 'Model', 'Serial Number',
                'Purchase Date', 'Purchase Price', 'Current Value', 'Location',
                'Condition', 'Status', 'Next Maintenance'
            ]);

            foreach ($equipment as $item) {
                $depreciation = $item->calculateDepreciation();
                
                fputcsv($file, [
                    $item->equipment_code,
                    $item->name,
                    $item->category->name ?? 'N/A',
                    $item->brand,
                    $item->model,
                    $item->serial_number,
                    $item->purchase_date ? $item->purchase_date->format('Y-m-d') : '',
                    $item->purchase_price,
                    $depreciation ? number_format($depreciation['current_value'], 2) : '',
                    $item->location,
                    $item->condition,
                    $item->status,
                    $item->next_maintenance_date ? $item->next_maintenance_date->format('Y-m-d') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
