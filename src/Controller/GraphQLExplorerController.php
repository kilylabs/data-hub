<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\DataHubBundle\Controller;

use Pimcore\Bundle\DataHubBundle\GraphQL\Service;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class GraphQLExplorerController extends AbstractController
{
    /**
     * @param RouterInterface $routingService
     * @param Request $request
     *
     * @Cache(expires="tomorrow", public=true)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function explorerAction(RouterInterface $routingService, Request $request, Service $service)
    {
        $tagManagerListener = $service->getTagManagerListener();
        // disable Tag & Snippet Management
        if($tagManagerListener) {
            $tagManagerListener->disable();
        }

        $urlParams = array_merge($request->request->all(), $request->query->all());

        $clientName = $request->get('clientname');

        $url = $routingService->generate('admin_pimcoredatahub_webservice', ['clientname' => $clientName]);

        if (!$url) {
            throw new \Exception('unable to resolve');
        }

        if ($urlParams) {
            $url = $url . '?' . http_build_query($urlParams);
        }

        return $this->render('@PimcoreDataHub/Feature/explorer.html.twig', [
            'graphQLUrl' => $url,
            'tokenHeader' => 'access-token'
        ]);
    }
}
