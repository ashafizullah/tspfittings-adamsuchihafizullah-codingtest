<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderProgress;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WorkOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:work_orders.index')->only('index');
        $this->middleware('permission:work_orders.create')->only('create', 'store');
        $this->middleware('permission:work_orders.edit')->only('edit', 'update');
        $this->middleware('permission:work_orders.delete')->only('destroy');
        $this->middleware('permission:work_orders.view_assigned')->only('viewAssigned');
        $this->middleware('permission:work_orders.update_status')->only('updateStatus');
        $this->middleware('permission:work_orders.view_reports')->only('reports');
    }

    public function index()
    {
        $isProductionManager = Auth::user()->hasRole('production_manager');
        $query = WorkOrder::with('operator', 'createdByUser', 'updatedByUser');

        // Apply filters based on request parameters
        $workOrders = $query->when(request()->product_name, function ($query) {
            $query->where('product_name', 'like', '%' . request()->product_name . '%');
        })->when(request()->status, function ($query) {
            $query->where('status', request()->status);
        })->when(request()->operator_id, function ($query) {
            $query->where('operator_id', request()->operator_id);
        })->when(request()->from_date && request()->to_date, function ($query) {
            $query->whereBetween('production_deadline', [request()->from_date, request()->to_date]);
        })->latest()->paginate(10);

        // Get operators for filter dropdown
        $operators = User::role('operator')->orderBy('name', 'ASC')->get();

        return Inertia::render('Apps/WorkOrders/Index', [
            'workOrders' => $workOrders,
            'operators' => $operators,
            'statuses' => ['Pending', 'In Progress', 'Completed', 'Canceled'],
            'filters' => request()->all(['product_name', 'status', 'operator_id', 'from_date', 'to_date']),
            'isProductionManager' => $isProductionManager,
        ]);
    }

    public function create()
    {
        $operators = User::role('operator')->orderBy('name', 'ASC')->get();

        return Inertia::render('Apps/WorkOrders/Create', [
            'operators' => $operators,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'production_deadline' => 'required|date',
            'operator_id' => 'required|exists:users,id',
        ]);

        WorkOrder::create([
            'work_order_number' => WorkOrder::generateWorkOrderNumber(),
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'production_deadline' => $request->production_deadline,
            'status' => 'Pending',
            'operator_id' => $request->operator_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('apps.work-orders.index');
    }

    public function edit(WorkOrder $workOrder)
    {
        $operators = User::role('operator')->orderBy('name', 'ASC')->get();

        return Inertia::render('Apps/WorkOrders/Edit', [
            'workOrder' => $workOrder,
            'operators' => $operators,
        ]);
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $this->validate($request, [
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'production_deadline' => 'required|date',
            'operator_id' => 'required|exists:users,id',
            'status' => 'required|in:Pending,In Progress,Completed,Canceled',
        ]);

        $workOrder->update([
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'production_deadline' => $request->production_deadline,
            'status' => $request->status,
            'operator_id' => $request->operator_id,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('apps.work-orders.index');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();

        return redirect()->route('apps.work-orders.index');
    }

    public function viewAssigned()
    {
        $workOrders = WorkOrder::with('operator', 'createdByUser', 'updatedByUser')
            ->where('operator_id', Auth::id())
            ->when(request()->status, function ($query) {
                $query->where('status', request()->status);
            })
            ->latest()
            ->paginate(10);

        return Inertia::render('Apps/WorkOrders/AssignedOrders', [
            'workOrders' => $workOrders,
            'statuses' => ['Pending', 'In Progress', 'Completed', 'Canceled'],
            'filters' => request()->all(['status']),
        ]);
    }

    public function reports()
    {
        // Summary report with counts by status
        $statusSummary = WorkOrder::selectRaw('product_name,
            SUM(CASE WHEN status = "Pending" THEN quantity ELSE 0 END) as pending_quantity,
            SUM(CASE WHEN status = "In Progress" THEN quantity ELSE 0 END) as in_progress_quantity,
            SUM(CASE WHEN status = "Completed" THEN quantity ELSE 0 END) as completed_quantity,
            SUM(CASE WHEN status = "Canceled" THEN quantity ELSE 0 END) as canceled_quantity')
            ->groupBy('product_name')
            ->get();

        // Operator performance report with requested vs. completed quantities
        $operatorPerformance = WorkOrder::selectRaw('
            users.name as operator_name,
            work_orders.product_name,
            SUM(work_orders.quantity) as requested_quantity,
            SUM(CASE WHEN work_orders.status = "Completed" THEN work_orders.quantity ELSE 0 END) as completed_quantity,
            MAX(CASE WHEN work_orders.status = "Completed" THEN work_orders.updated_at ELSE NULL END) as latest_completion_date
        ')
        ->join('users', 'users.id', '=', 'work_orders.operator_id')
        ->groupBy('users.name', 'work_orders.product_name')
        ->get();

        // Recently completed work orders with completion dates
        $recentlyCompletedOrders = WorkOrder::select(
            'work_orders.id',
            'work_orders.work_order_number',
            'work_orders.product_name',
            'work_orders.quantity as requested_quantity',
            'work_orders.created_at',
            'work_orders.updated_at as completed_at',
            'users.name as operator_name'
        )
        ->join('users', 'users.id', '=', 'work_orders.operator_id')
        ->where('work_orders.status', 'Completed')
        ->orderBy('work_orders.updated_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function ($workOrder) {
            // Get the actual completed quantity from progress records
            $completedQuantity = WorkOrderProgress::where('work_order_id', $workOrder->id)
                ->where('new_status', 'Completed')
                ->sum('quantity_change');

            $workOrder->completed_quantity = $completedQuantity ?: $workOrder->requested_quantity;

            // Calculate completion time in hours
            $createdAt = new \DateTime($workOrder->created_at);
            $completedAt = new \DateTime($workOrder->completed_at);
            $interval = $createdAt->diff($completedAt);
            $hours = $interval->h + ($interval->days * 24);
            $workOrder->completion_hours = $hours;

            return $workOrder;
        });

        return Inertia::render('Apps/WorkOrders/Reports', [
            'statusSummary' => $statusSummary,
            'operatorPerformance' => $operatorPerformance,
            'recentlyCompletedOrders' => $recentlyCompletedOrders,
        ]);
    }
}
