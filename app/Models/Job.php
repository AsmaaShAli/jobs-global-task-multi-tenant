<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Job extends Model
{
    protected $connection = 'tenant';
    use Searchable;

    protected $fillable = ['title', 'description','location'];

    public function searchableAs(): string
    {
        return 'jobs_'.(app(\App\Models\Tenant::class)->slug ?? 'global');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'tenant_slug' => app(\App\Models\Tenant::class)->slug ?? null,
        ];
    }
}
