<?php

namespace App\Service;

use Symfony\Component\Cache\Simple\FilesystemCache;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Qualification;


class QualificationGenerator
{
    private $em;
    private $cache;

    public function __construct(EntityManagerInterface $em)
    {
        $this->cache = new FilesystemCache();
        $this->em = $em;
    }

    public function get(int $qualification_id) 
    {
        if (!$this->cache->has('qualification.'.$qualification_id)) {
            $qualification = $this->em->getRepository(Qualification::class)->find($qualification_id);

            $this->cache->set('qualification.'.$qualification_id, $qualification);
        }

        return $this->cache->get('qualification.'.$qualification_id);
    }
}