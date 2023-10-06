<?php

namespace App\Controller;

use App\Core\InboundPort\SegmentServiceInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvailController extends AbstractController
{
    /**
     * @var SegmentServiceInterface The interface that permits interact with the core
     */
    protected SegmentServiceInterface $segmentService;

    /**
     * @param SegmentServiceInterface $segmentService the service port to interact with the segment
     *
     *                                                Constructor
     */
    public function __construct(SegmentServiceInterface $segmentService)
    {
        $this->segmentService = $segmentService;
    }
    /**
     * Request $request The request
     */
    #[Route('/api/avail', name: 'avail_endpoint')]
    public function endpoint(Request $request)
    {
        // Here it would go the implementation of $input validation abstracted
        // into another class to make use of the SAME RULES here AND in the command adapter

        // This would return 400 or other status codes depending on the input

        // I am not doing it because I feel it is not the purpose of this exercise, but
        // if you want me to implement the validation and just please let me know, and I will

        $origin = $request->query->get('origin');
        $destination = $request->query->get('destination');
        $date = DateTime::createFromFormat('Y-m-d', $request->query->get('date'));

        return new Response($this->segmentService->getSegmentsInJson($origin, $destination, $date));
    }
}
