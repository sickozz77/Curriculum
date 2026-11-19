<?php

namespace App\Observers;

use App\Models\Audit;

class AuditObserver
{
    protected function log(
        string $action,
        $model,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        Audit::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'table_name' => $model->getTable(),
            'record_id'  => $model->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function created($model): void
    {
        $this->log('create', $model, null, $model->getAttributes());
    }

    public function updated($model): void
    {
        $this->log(
            'update',
            $model,
            $model->getOriginal(),
            $model->getChanges()
        );
    }

    public function deleted($model): void
    {
        $this->log('delete', $model, $model->getOriginal(), null);
    }
}