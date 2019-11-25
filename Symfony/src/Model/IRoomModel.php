<?php
/**
 * Created by PhpStorm.
 * User: 11702205
 * Date: 25/11/2019
 * Time: 11:33
 */

namespace App\Model;


interface IRoomModel
{
    function getRooms();
    function getByRoomName($roomName);
    function getRoomsWithHappinessScoreLowerThan($happinessScore);
    function updateHappinessScore($name, $score);
}