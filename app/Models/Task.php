<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['objective_id', 'id'];

    protected $fillable = [
        'objective_id',
        'id',
        'detail',
        'image',
        'status',
    ];

    protected $casts = [
        'objective_id' => 'integer',
        'id' => 'integer',
        'status' => 'integer',
    ];

    /**
     * Task belongs to an Objective.
     */
    public function objective()
    {
        return $this->belongsTo(Objective::class, 'objective_id', 'id');
    }

    /**
     * Get the next ID for a given objective.
     */
    public static function getNextId($objectiveId): int
    {
        $maxId = static::where('objective_id', $objectiveId)->max('id');
        return ($maxId ?? 0) + 1;
    }

    /**
     * Override the route key name for composite key routing.
     */
    public function getRouteKeyName()
    {
        return 'objective_id';
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKey()
    {
        return $this->objective_id . '-' . $this->id;
    }

    /**
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (str_contains($value, '-')) {
            [$objectiveId, $id] = explode('-', $value, 2);
            return $this->where('objective_id', $objectiveId)
                        ->where('id', $id)
                        ->first();
        }

        return null;
    }

    /**
     * Override update method to handle composite primary key.
     */
    public function update(array $attributes = [], array $options = [])
    {
        if (! $this->exists) {
            return false;
        }

        return $this->fill($attributes)->save($options);
    }

    /**
     * Override save method to handle composite primary key updates.
     */
    public function save(array $options = [])
    {
        $query = $this->newModelQuery();

        // If the model already exists in the database we can just update our record
        // that is already in this database using the current IDs in this "where"
        // clause to only update this model. Otherwise, we'll just insert them.
        if ($this->exists) {
            $saved = $this->isDirty() ?
                     $this->performUpdate($query) : true;
        } else {
            $saved = $this->performInsert($query);

            if (! $this->getConnectionName() &&
                $connection = $query->getConnection()) {
                $this->setConnection($connection->getName());
            }
        }

        if ($saved) {
            $this->finishSave($options);
        }

        return $saved;
    }

    /**
     * Perform an update query for composite primary key.
     */
    protected function performUpdate($query)
    {
        $dirty = $this->getDirty();

        if (count($dirty) > 0) {
            if ($this->fireModelEvent('updating') === false) {
                return false;
            }

            $this->setKeysForSaveQuery($query);

            $dirty = $this->getDirty();

            if (count($dirty) > 0) {
                $numRows = $this->setKeysForSaveQuery($query)->update($dirty);

                $this->syncChanges();

                $this->fireModelEvent('updated', false);
            }
        }

        return true;
    }

    /**
     * Set the keys for a save update query for composite primary key.
     */
    protected function setKeysForSaveQuery($query)
    {
        foreach ((array) $this->primaryKey as $key) {
            $query->where($key, '=', $this->getAttribute($key));
        }

        return $query;
    }
}
