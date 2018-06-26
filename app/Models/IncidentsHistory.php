<?php
/**
 *
 * @file
 * @version 0.1
 * @copyright 2018 CN-Consult GmbH
 * @author Pascal Stiemer <pascal.stiemer@cn-consult.eu>
 */

namespace CachetHQ\Cachet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use CachetHQ\Cachet\Presenters\IncidentsHistoryPresenter;

/**
 * This is a model for the database table incidents_histories.
 * Which tracks all changes done to an incident.
 */
class IncidentsHistory extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'scheduled_at' => 'date',
        'deleted_at'   => 'date',
    ];

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = [
        'incidents_id',
        'status',
        'message',
        'created_at',
        'updated_at',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'incidents_id' => 'int',
        'status'       => 'required|int',
        'message'      => 'required',
    ];

    /**
     * The searchable fields.
     *
     * @var string[]
     */
    protected $searchable = [
        'id',
        'incidents_id',
        'status',
    ];

    /**
     * The sortable fields.
     *
     * @var string[]
     */
    protected $sortable = [
        'id',
        'incidents_id',
        'status',
        'message',
    ];

    /**
     * An IncidentsHistory belongs to an Incident.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    /**
     * Finds all incidentsHistories for a given incident.
     *
     * @param Builder $query
     * @param int     $incidents_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForIncident(Builder $query, $incidents_id)
    {
        return $query->where('incidents_id', $incidents_id);
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return IncidentsHistoryPresenter::class;
    }
}