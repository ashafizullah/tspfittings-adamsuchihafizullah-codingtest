<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_number',
        'product_name',
        'quantity',
        'production_deadline',
        'status',
        'operator_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'production_deadline' => 'datetime',
    ];

    // Define relationships
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function progress()
    {
        return $this->hasMany(WorkOrderProgress::class);
    }

    // Generate work order number
    public static function generateWorkOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastWorkOrder = self::where('work_order_number', 'like', "WO-{$date}-%")
            ->orderBy('work_order_number', 'desc')
            ->first();

        if ($lastWorkOrder) {
            $lastNumber = intval(substr($lastWorkOrder->work_order_number, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "WO-{$date}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // Status accessor and mutator
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: function ($value) {
                // Get current status safely (will be null for new records)
                $currentStatus = $this->attributes['status'] ?? null;

                // Only create progress record if status is changing and record exists in database
                if ($value !== $currentStatus && isset($this->attributes['id'])) {
                    WorkOrderProgress::create([
                        'work_order_id' => $this->attributes['id'],
                        'previous_status' => $currentStatus ?? 'Pending',
                        'new_status' => $value,
                        'updated_by' => auth()->id(),
                    ]);
                }
                return $value;
            }
        );
    }
}
