<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property TeamStat $stats
 */
class Team extends Model
{
    protected $table = 'teams';
    protected $fillable = [
        'name',
        // how good is this team to defense when plays at home
        'home_defensive_strength',
        // how good is this team to defense when plays away
        'away_defensive_strength',
        // how good is this team to attack when plays at home
        'home_attacking_strength',
        // how good is this team to attack when plays away
        'away_attacking_strength',
    ];
    public $timestamps = false;

    public function stats(): HasOne
    {
        return $this->hasOne(TeamStat::class);
    }
}
