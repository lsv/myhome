<?php

namespace HomeBundle\Controller;

use ContaoCommunityAlliance\GithubPayload\Event as GithubEvent;
use ContaoCommunityAlliance\GithubPayload\GithubPayloadParser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $feeds = $this->get('feed.service')->getFeedRender();
        return $this->render(':Home/Default:index.html.twig', [
            'feeds' => $feeds
        ]);
    }

    /**
     * @Route("/feed")
     * @param Request $request
     * @return JsonResponse
     */
    public function itemsAction(Request $request)
    {
        if (! $request->isXmlHttpRequest()) {
            $this->createAccessDeniedException();
        }

        if ($request->isMethod('POST')) {
            $data = $request->request;
        } else {
            $data = $request->query;
        }

        if (! $data->has('feeds')) {
            $this->createAccessDeniedException();
        }

        $feeds = $data->get('feeds');
        foreach($feeds as &$feed) {
            try {
                $feed['items'] = $this->get('feed.service')->getItems($feed['url']);
            } catch (\Exception $e) {
                $feed['error'] = $e->getMessage();
            }
        }

        return new JsonResponse($feeds);
    }

    /**
     * @Route("/payload")
     * @param Request $request
     * @return Response
     */
    public function payloadAction(Request $request)
    {
        $parser = new GithubPayloadParser();
        $parser->setSecret(getenv('GITHUB_SECRET'));
        try {
            $event = $parser->parseRequest($request);
            if ($event instanceof GithubEvent\PingEvent) {
                return new Response('pong');
            }

            if ($event instanceof GithubEvent\PushEvent) {
                $dir = sprintf('%s/../push.log', $this->getParameter('kernel.root_dir'));
                file_put_contents($dir, 'PUSH');
                return new Response();
            }

            throw $this->createNotFoundException();
        } catch (\Exception $e) {
            throw $this->createNotFoundException();
        }
    }

}
