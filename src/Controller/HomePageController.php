<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function index()
    {
		$serializer = new Serializer(array(), array(new JsonEncoder()));
        $cache = new FilesystemCache();

        if (!$cache->has('homepage.stages')) {

            $em = $this->getDoctrine()->getManager();
            $sql = '
                SELECT s.id, s.designation, s.image_name, count(q.qualification_id) as "qualification_quantity",
                count(DISTINCT(q.qualification_id)) as "stages_quantity" FROM `stage` s LEFT JOIN `stage_qualification` 
                s_q ON s_q.stage_id = s.id LEFT JOIN `question` q ON q.qualification_id = s_q.qualification_id GROUP BY s_q.stage_id
            ';
            
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $stages =  $stmt->fetchAll();

            $cache->set('homepage.stages', $serializer->encode($stages, 'json'));
        }

        $stages = $serializer->decode($cache->get('homepage.stages'), 'json');

        return $this->render('home_page/index.html.twig', [
            'stages'   =>  $stages,
        ]);
    }
}
