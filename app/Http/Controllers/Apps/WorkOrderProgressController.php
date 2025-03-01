<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Models\WorkOrderProgress;
use Illuminate\Support\Facades\Auth;

class WorkOrderProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:work_orders.view_assigned')->only('index');
        $this->middleware('permission:work_orders.update_status')->only('store');
    }

    public function index(WorkOrder $workOrder)
    {
        // Verify the user has permission to view this work order
        if (Auth::user()->hasRole('operator') && Auth::id() != $workOrder->operator_id) {
            abort(403, 'You are not authorized to view this work order progress');
        }

        $progressLogs = $workOrder->progress()
            ->with('updatedByUser')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Apps/WorkOrders/Progress', [
            'workOrder' => $workOrder->load('operator', 'createdByUser', 'updatedByUser'),
            'progressLogs' => $progressLogs,
        ]);
    }

    public function store(Request $request, WorkOrder $workOrder)
    {
        // Validate the request
        $this->validate($request, [
            'new_status' => 'required|in:In Progress,Completed',
            'quantity_change' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        // Check if the user is the assigned operator
        if (Auth::id() != $workOrder->operator_id) {
            return back()->withErrors(['unauthorized' => 'You are not authorized to update this work order.']);
        }

        // Check if the status transition is valid
        $currentStatus = $workOrder->status;
        $newStatus = $request->new_status;

        // Modified valid transitions - allowing more flexibility
        $validTransitions = [
            'Pending' => ['In Progress', 'Completed'],
            'In Progress' => ['In Progress', 'Completed'],
        ];

        if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
            return back()->withErrors(['status' => 'Invalid status transition.']);
        }

        // Create a progress record
        $progress = WorkOrderProgress::create([
            'work_order_id' => $workOrder->id,
            'previous_status' => $currentStatus,
            'new_status' => $newStatus,
            'quantity_change' => $request->quantity_change,
            'notes' => $request->notes,
            'updated_by' => Auth::id(),
        ]);

        // Update the work order status
        $workOrder->status = $newStatus;
        $workOrder->updated_by = Auth::id();
        $workOrder->save();

        return redirect()->route('apps.work-orders.assigned')
            ->with('success', 'Work order progress updated successfully');
    }

    public function getProductionSteps(WorkOrder $workOrder)
    {
        // This method will retrieve all production steps for a specific work order
        // to display the production timeline

        $progressSteps = $workOrder->progress()
            ->with('updatedByUser')
            ->orderBy('created_at', 'asc')
            ->get();

        $timeline = [];
        $startTime = $workOrder->created_at;

        foreach ($progressSteps as $step) {
            $duration = $step->created_at->diffInMinutes($startTime);

            $timeline[] = [
                'status' => $step->new_status,
                'notes' => $step->notes,
                'quantity_change' => $step->quantity_change,
                'updated_by' => $step->updatedByUser->name,
                'timestamp' => $step->created_at->format('Y-m-d H:i:s'),
                'duration_minutes' => $duration,
            ];

            $startTime = $step->created_at;
        }

        return response()->json([
            'work_order' => $workOrder->only('id', 'work_order_number', 'product_name', 'quantity'),
            'timeline' => $timeline
        ]);
    }
}
