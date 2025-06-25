<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends SpatieActivity
{
    /**
     * Get the causer of the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function causer(): MorphTo
    {
        if (empty($this->causer_type)) {
            return $this->morphTo();
        }
        
        return $this->morphTo('causer');
    }

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        if (empty($this->subject_type)) {
            return $this->morphTo();
        }
        
        return $this->morphTo('subject');
    }

    /**
     * Get the batch of the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function batch()
    {
        return $this->belongsTo(ActivityBatch::class, 'batch_uuid', 'uuid');
    }
}
