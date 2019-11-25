<?php

namespace App\Tests;

use App\Controller\AssetController;

use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class AssetControllerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $model;

    public function setUp()
    {
        $this->model = $this->getMockBuilder('App\Model\AssetModel')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function test_If_GetAssetByName_ReturnsThatAsset()
    {
        $request = Request::create('/getByAssetName','GET',['name' => 'SeppesBank']);
        $expected = $this->assetProvider()[0];

        $this->model->expects($this->any())
            ->method('getByAssetName')
            ->with($request->get('name'))
            ->willReturn($this->assetProvider()[0]);

        $assetController = new AssetController($this->model);
        $assetEncoded = $assetController->getByAssetName($request);
        $result = json_decode($assetEncoded->getContent(),true);

        self::assertEquals($expected, $result);
    }

    private function assetProvider(){
        return [
            ["roomId"=>1,"name"=>"SeppesBank"],
            ["roomId"=>2,"name"=>"RobinBank"]
        ];
    }
}