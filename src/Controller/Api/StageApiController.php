<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\StagesGenerator;
use App\Helper\Pagination;
use App\Entity\Stage;

class StageApiController extends AbstractController
{
    /**
     * @Route("/stage/api/find", name="stage_finder")
     */
    public function search(Request $request)
    {   
        $query = addslashes($request->get('query'));

        $sql = '
                SELECT s.meta_description, s.friendly_url, s.id, s.designation, s.image_name, count(q.qualification_id) as "qualification_quantity",
                count(DISTINCT(q.qualification_id)) as "stages_quantity" FROM `stage` s JOIN `stage_qualification` 
                s_q ON s_q.stage_id = s.id 
                JOIN `question` q ON q.qualification_id = s_q.qualification_id AND s.is_active = 1 AND s.designation LIKE "%'. $query .'%"
                GROUP BY s_q.stage_id LIMIT 5
            ';
            
        $stmt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmt->execute();
        $stages =  $stmt->fetchAll();

        return new JsonResponse($stages);
    }

    /**
     * @Route("/stage/api/pagination/get", name="pagination_get")
     */
    public function getPagination(Request $request, StagesGenerator $stagesGenerator)
    {   
        if (empty($request->get('paginationLimit')) || !ctype_digit($request->get('paginationLimit')))
            $paginationLimit = 3;
        else 
            $paginationLimit = $request->get('paginationLimit');

        if (empty($request->get('itemsLimit')) || !ctype_digit($request->get('itemsLimit')))
            $itemsLimit = 3;
        else 
            $itemsLimit = $request->get('itemsLimit');

        $totalStagesCount = $stagesGenerator->getTotalCount();

        if (empty($request->get('page')) || !ctype_digit($request->get('page')))
            $currentPage = 1;
        else 
            $currentPage = $request->get('page');

        $pagination = new Pagination($currentPage, $totalStagesCount, $paginationLimit, $itemsLimit);
        $stages = $stagesGenerator->getRange($pagination->getLimitOffset(), $itemsLimit);

        $response = [
            'stages'             =>  $stages,
            'allPages'           =>  $pagination->getPagesQuantity(),
            'currentPage'        =>  $pagination->getCurrentPage(),
            'totalStagesCount'   =>  $totalStagesCount,
            'range'              =>  $pagination->getRange()
        ];

        return new JsonResponse($response);
    }
}
