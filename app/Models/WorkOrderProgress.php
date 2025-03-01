<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderProgress extends Model
{
    use HasFactory;

    protected $table = 'work_order_progress';

    protected $fillable = [
        'work_order_id',
        'previous_status',
        'new_status',
        'quantity_change',
        'notes',
        'updated_by'
    ];

    // Define relationships
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Calculate time spent in the previous status
    public function getTimeSpentAttribute()
    {
        $previousStatusChange = WorkOrderProgress::where('work_order_id', $this->work_order_id)
            ->where('new_status', $this->previous_status)
            ->latest()
            ->first();

        if ($previousStatusChange) {
            return $this->created_at->diffInSeconds($previousStatusChange->created_at);
        }

        // If no previous status change found, compare with work order creation time
        $workOrder = $this->workOrder;
        return $this->created_at->diffInSeconds($workOrder->created_at);
    }
}
