<?php

namespace App\Controller;

use App\Model\ITicketModel;
use App\Model\TicketModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    private $ticketRepository;

    /**
     * TicketController constructor.
     * @param $ticketRepository
     */
    public function __construct(ITicketModel $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * @Route("/ticket", name="ticket")
     */
    public function index()
    {
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
        ]);
    }
    //TODO Default waarde?
    /**
     * @Route ("/getTicketsByAssetName", methods={"GET"}, name="getTicketsByAssetName")
     * @param Request $request
     * @return JsonResponse
     */
    public function getTicketsByAssetName(Request $request){
        $assetName = null;
        $statuscode = 200;
        $tickets = [];
        if (!empty($request->query)) {
            $assetName = $request->query->get("assetName");
        }
        try{
            $tickets = $this->ticketRepository->getTicketsByAssetName($assetName);
        } catch (\InvalidArgumentException $exception){
            $statuscode = 400;
            print $exception;
        } catch(\PDOException $exception){
            $statuscode = 500;
            print $exception;
        }
        $response = new JsonResponse($tickets, $statuscode);
        return $response;
    }

    //TODO returned ticket has id null -> change return?
    /**
     * @Route ("/createTicketByAssetName", methods={"GET","POST"}, name="createNewTicketByAssetName")
     * @param Request $request
     * @return Response
     */
    public function createNewTicketByAssetName(Request $request){
        $assetName = null;
        $description = null;
        $statuscode = null;
        $ticket = null;
        if (!empty($request->query)) {
            $assetName = $request->query->get("assetName");
            $description = $request->getContent();
        }
        try {
            $ticket = $this->ticketRepository->createNewTicketByAssetName($assetName,$description);
            $statuscode = 201;
        } catch (\InvalidArgumentException $exception){
            $statuscode = 400;
            print $exception;
        } catch (\PDOException $exception){
            print($exception);
            $statuscode = 500;
            print $exception;
        }
        $response = new JSonResponse($ticket, $statuscode);
        return $response;
    }

    /**
     * @Route("/updateNumberOfVotes", methods={"GET", "PUT"}, name="updateNumberOfVotes")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNumberOfVotes(Request $request){
        $ticketId = null;
        $statuscode = 200;
        $updatedTicket = null;
        if (!empty($request->query)) {
            $ticketId = $request->query->get("ticketId");
        }
        try{
            $updatedTicket = $this->ticketRepository->increaseNumberOfVotes($ticketId);
        } catch (\InvalidArgumentException $exception) {
            $statuscode = 400;
            print $exception;
        } catch (\PDOException $exception){
            $statuscode = 500;
            print $exception;
        }
        $response = new JsonResponse($updatedTicket, $statuscode);
        return $response;
    }
}
