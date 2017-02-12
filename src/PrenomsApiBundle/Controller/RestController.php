<?php

namespace PrenomsApiBundle\Controller;

use PrenomsApiBundle\Entity\FirstName;
use PrenomsApiBundle\Entity\Metrics;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestController extends Controller
{
    /**
     * API Ping
     *
     * @Rest\View()
     * @Rest\Get("/api/ping")
     * @return array|JsonResponse
     */
    public function pingAction()
    {
        return [
            'ping' => 'pong',
        ];
    }

    /**
     * Get First Name data
     *
     * @Rest\View(serializerGroups={"firstname"})
     * @Rest\Get("/api/name/{name}")
     * @param Request $request
     * @return array|JsonResponse
     */
    public function getFirstNameData(Request $request)
    {
        $metrics = $this->getDoctrine()
            ->getRepository('PrenomsApiBundle:FirstName')
            ->findOneByFirstName($request->get('name'));

        if(!$metrics){
            return new JsonResponse(['message' => 'FirstName not found'], Response::HTTP_NOT_FOUND);
        }

        $results = [];
        /** @var Metrics $metric */
        foreach ($metrics->getMetrics()->toArray() as $metric) {
            $gender = $metric->getSex()->getSex();

            $year = $metric->getYear();
            $count = $metric->getCount();

            if (!isset($results[$gender]['firstSeen'])) {
                $results[$gender]['firstSeen'] = $year;
                $results[$gender]['lastSeen'] = $year;
                $results[$gender]['topYear'] = ['year' => $year, 'count' => $count];
                $results[$gender]['worstYear'] = ['year' => $year, 'count' => $count];
            }else {
                if ($year < $results[$gender]['firstSeen']) {
                    $results[$gender]['firstSeen'] = $year;
                }
                if ($year > $results[$gender]['lastSeen']) {
                    $results[$gender]['lastSeen'] = $year;
                }
                if ($count > $results[$gender]['topYear']['count']) {
                    $results[$gender]['topYear'] = ['year' => $year, 'count' => $count];
                }
                if ($count < $results[$gender]['worstYear']['count']) {
                    $results[$gender]['worstYear'] = ['year' => $year, 'count' => $count];
                }
            }

            $results[$gender]['metrics'][$year] = $count;
        }
        return $results;
    }

    /**
     * Get First Name data
     *
     * @Rest\View()
     * @Rest\Get("/api/names")
     */
    public function getFirstNameList()
    {
        $names = $this->getDoctrine()
            ->getRepository('PrenomsApiBundle:FirstName')
            ->findAll();

        return array_map(['self', 'getName'], $names);
    }

    protected function getName(FirstName $name)
    {
        return $name->getFirstName();
    }
}
