<?php

namespace Seat\Kassie\Calendar\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Web\Models\User;

/**
 * Class Timers
 *
 * Timer data model for recording individual timers for EVE online
 */
class Timers extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'timers';

    /**
     * Hardcoded GroupID that relates to the EVE Static Data Dump
     *
     * @var int
     */
    public static $POCOGroupID = 7;

    /**
     * The attributes that can be edited in models.
     *
     * @var array
     */
    protected $fillable = array(
        'itemID',
        'structureType',
        'structureStatus',
        'bashed',
        'outcome',
        'timerType',
        'timeExiting',
        'user_id',
        'type'
    );

    /**
     * Hardcoded Structure IDs that map IDs to names
     *
     * @var array
     */
    public static $structureTypes = array(
        '32226' => 'TCU',
        '32458' => 'I-Hub',
        '2233' => 'POCO',
        '35832' => 'Astrahus',
        '35825' => 'Raitaru',
        '35835' => 'Athanor',
        '35833' => 'Fortizar',
        '35826' => 'Azbel',
        '35836' => 'Tatara',
        '35834' => 'Keepstar',
        '35827' => 'Sotiyo',
        '35841' => 'Ansiblex Jump Gate',
        '35840' => 'Pharolux Cyno Beacon',
        '37534' => 'Tenebrex Cyno Jammer',
        '47513' => '\'Draccous\' Fortizar',
        '47514' => '\'Horizon\' Fortizar',
        '47515' => '\'Marginis\' Fortizar',
        '47512' => '\'Moreau\' Fortizar',
        '47516' => '\'Prometheus\' Fortizar',
        '40340' => 'Upwell Palatine Keepstar'
    );

    /**
     * Hardcoded Structure Statuses that map Statuses to names
     *
     * @var array
     */
    public static $structureStatus = array(
        '0' => '',
        '1' => 'Shield',
        '2' => 'Armor',
        '3' => 'Structure'
    );

    /**
     * Hardcoded Timer Types that map Types to Names
     *
     * @var array
     */
    public static $timerType = array(
        '0' => 'Offensive',
        '1' => 'Defensive'
    );

    public static $signUpRoles = array(
        '0' => 'FC',
        '1' => 'Titan'
    );

    /**
     * Many-to-many relationship to ApiUser for signing up for timers.
     */
    public function signUps()
    {
        return $this->belongsToMany('ApiUser', 'timer_sign_ups')->withPivot('role', 'confirmed');
    }

    public function mapItem()
    {
        return $this->belongsTo(MapDenormalize::class, 'itemID', 'itemID');
    }

    /**
     * One-to-many relationship to notes
     */
    public function notes()
    {
        return $this->hasMany('Notes');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Convenience method for checking if a user has signed up for this timer instance.
     *
     * @param int UserId ID of user to check
     * @param role ID of role from
     * @return bool True if signed up, otherwise false
     */
    public function isUserSignedUpAs($userId, $roleId)
    {
        return !$this->signUps()->wherePivot('api_user_id', $userId)
            ->wherePivot('role', $roleId)->get()->isEmpty();
    }

    public function userCanSignUp($roleId)
    {
        $role = Timers::$signUpRoles[$roleId];
        if($role == 'FC' and Auth::user()->isFC())
        {
            return true;
        }
        if($role == 'Titan' and Auth::user()->isTitanPilot())
        {
            return true;
        }
        return false;
    }
}