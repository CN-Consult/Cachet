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

class Downtime extends Model
{
    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = [
        'created_at',
        'resolved_at',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'resolved_at'      => 'optional|null',
    ];

    /**
     * The searchable fields.
     *
     * @var string[]
     */
    protected $searchable = [
        'id',
        'created_at',
        'resolved_at',
    ];

    /**
     * The sortable fields.
     *
     * @var string[]
     */
    protected $sortable = [
        'id',
        'created_at',
        'resolved_at',
    ];
}