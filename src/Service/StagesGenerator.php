<?php

namespace App\Service;

use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Stage;

class StagesGenerator
{
    private $em;
    private $serializer;
    private $cache;

    public function __construct(EntityManagerInterface $em)
    {
        $this->serializer = new Serializer(array(), array(new JsonEncoder()));
        $this->cache = new FilesystemCache();

        $this->em = $em;
    }

    /*
    * Pull out all with the number of all questions
    */
    public function getAll() : Array
    {
        if (!$this->cache->has('stages.all')) {

            $sql = '
                SELECT s.meta_description, s.friendly_url, s.id, s.designation, s.image_name, count(q.qualification_id) as "qualification_quantity",
                count(DISTINCT(q.qualification_id)) as "stages_quantity" FROM `stage` s LEFT JOIN `stage_qualification` 
                s_q ON s_q.stage_id = s.id LEFT JOIN `question` q ON q.qualification_id = s_q.qualification_id GROUP BY s_q.stage_id
            ';
            
            $stmt = $this->em->getConnection()->prepare($sql);
            $stmt->execute();
            $stages =  $stmt->fetchAll();

            $this->cache->set('stages.all', $this->serializer->encode($stages, 'json'));
        }

        return $this->serializer->decode($this->cache->get('stages.all'), 'json');
    }
}