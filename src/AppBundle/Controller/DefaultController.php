<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Dates;
use AppBundle\Entity\ItemsPerDay;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $date =  $request->get('date')
            ? \DateTime::createFromFormat('d/m/Y', $request->get('date'))
            :   new \DateTime();

        $em =  $this->container->get('doctrine.orm.entity_manager');
        $day = $em->getRepository(Dates::class)->findOneBy(['date' => $date]);

        if(!$day){
            return new JsonResponse("No data for today");
        }

       $entiteis = $em->getRepository(ItemsPerDay::class)->mostSoldItems($day);
        $serializer = $this->container->get('jms_serializer');
        $data = $serializer->toArray($entiteis, SerializationContext::create()->setGroups(['items_per_day']));

        return new JsonResponse($data);
    }
}
