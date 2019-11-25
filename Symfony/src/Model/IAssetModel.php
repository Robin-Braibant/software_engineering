<?php
/**
 * Created by PhpStorm.
 * User: 11702205
 * Date: 25/11/2019
 * Time: 11:41
 */

namespace App\Model;


interface IAssetModel
{
    function getByAssetName($name);
}