<?php
/**
 * Created by PhpStorm.
 * User: 11702205
 * Date: 25/11/2019
 * Time: 11:38
 */

namespace App\Model;


interface ITicketModel
{
    function getTicketsByAssetName($assetName);
    function createNewTicketByAssetName($assetName, $description);
    function getTicketById($ticketId);
    function increaseNumberOfVotes($ticketId);
}