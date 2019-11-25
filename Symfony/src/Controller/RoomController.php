<?php

namespace App\Controller;

use App\Model\IRoomModel;
use App\Model\RoomModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    private $roomRepository;

    /**
     * RoomController constructor.
     * @param IRoomModel $roomRepository
     */
    public function __construct(IRoomModel $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @Route("/room", name="room")
     */
    public function index()
    {
        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }

    /**
     * @Route("/rooms", methods={"GET"}, name="rooms")
     * @return JsonResponse
     */
    public function getAllRooms() {
        $statuscode = 200;
        $rooms = [];
        try{
            $rooms = $this->roomRepository->getRooms();
        } catch (InvalidArgumentException $exception){
            $statuscode = 400;
            print $exception;
        } catch(\PDOException $exception){
            $statuscode = 500;
            print $exception;
        }
        $response = new JsonResponse($rooms, $statuscode);
        return $response;
    }

    /**
     * @Route("/getRoom", methods={"GET"}, name="getByRoomName")
     * @param Request $request
     * @return JsonResponse
     */
    public function getByRoomName(Request $request){
        $name = null;
        $statuscode = 200;
        $room = [];
        if (!empty($request->query)) {
            $name = $request->query->get("name");
        }
        try {
            $room = $this->roomRepository->getByRoomName($name);
        } catch (\InvalidArgumentException $exception){
            $statuscode = 400;
            print $exception;
        } catch(\PDOException $exception){
            $statuscode = 500;
            print $exception;
        }
        $response = new JsonResponse($room, $statuscode);
        return $response;
    }

    /**
     * @Route("/getRoomsByHappinessScore", methods={"GET"}, name="getRoomsWithHappinessScoreLowerThan")
     * @param Request $request
     * @return JsonResponse
     */
    public function getRoomsWithHappinessScoreLowerThan(Request $request){
        $happinessScore = null;
        $statuscode = 200;
        $rooms=[];
        if (!empty($request->query)) {
            $happinessScore = $request->query->get("happinessScore");
        }
        try{
            $rooms = $this->roomRepository->getRoomsWithHappinessScoreLowerThan($happinessScore);
        } catch (\InvalidArgumentException $exception){
            $statuscode = 400;
            print $exception;
        } catch(\PDOException $exception){
            $statuscode = 500;
            print $exception;
        }
        $response = new JsonResponse($rooms, $statuscode);
        return $response;
    }

    /**
     * @Route("/updateHappinessScore", methods={"GET", "PUT"}, name="updateHappinessScore")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateHappinessScore(Request $request){
        $name = null;
        $score = null;
        $statuscode = 200;
        $updatedRoom = null;
        if (!empty($request->query)) {
            $name = $request->query->get("name");
            $score = $request->query->get("score");
        }
        try{
            $updatedRoom = $this->roomRepository->updateHappinessScore($name,$score);
        } catch (\PDOException $exception){
            $statuscode = 500;
            print $exception;
        }
        $response = new JsonResponse($updatedRoom, $statuscode);
        return $response;
    }
}
