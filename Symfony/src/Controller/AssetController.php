<?php

namespace App\Controller;

use App\Model\AssetModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AssetController extends AbstractController
{
    private $assetRepository;

    /**
     * AssetController constructor.
     * @param AssetModel $assetRepository
     */
    public function __construct(AssetModel $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    /**
     * @Route("/asset", name="asset")
     */
    public function index()
    {
        return $this->render('asset/index.html.twig', [
            'controller_name' => 'AssetController',
        ]);
    }

    /**
     * @Route ("/getByAssetName", methods={"GET"}, name="getByAssetName")
     * @param Request $request
     * @return JsonResponse
     */
    public function getByAssetName(Request $request){
        $name = null;
        $statuscode = 200;
        $asset = [];
        if (!empty($request->query)) {
            $name = $request->query->get("name");
        }
        try{
            $asset = $this->assetRepository->getByAssetName($name);
        } catch (InvalidArgumentException $exception){
            $statuscode = 400;
            print $exception;
        } catch (\PDOException $exception){
            $statuscode = 500;
            print $exception;
        }
        $response = new JsonResponse($asset, $statuscode);
        return $response;
    }
}
