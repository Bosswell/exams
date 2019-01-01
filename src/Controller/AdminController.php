<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends BaseAdminController
{
    /**
     * @Route("/admin/cache/clear", name="clear_cache")
     */
    public function clearCache(Request $request)
    {
        $cache = new FilesystemCache();
        $cache->clear();

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}