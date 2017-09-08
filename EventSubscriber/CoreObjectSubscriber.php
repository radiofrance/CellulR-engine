<?php

namespace Rf\CellulR\EngineBundle\EventSubscriber;

use Rf\CellulR\EngineBundle\Finder\Finder;
use Rf\CellulR\EngineBundle\Resolver\CoreObjectResponseResolver;
use Rf\CellulR\EngineBundle\Utils\UtilsTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Rf\CellulR\EngineBundle\CoreObject\Response as COResponse;

/**
 * Class CoreObjectSubscriber.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class CoreObjectSubscriber implements EventSubscriberInterface
{
    use UtilsTrait;

    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var Finder
     */
    private $finder;

    /**
     * CoreObjectSubscriber constructor.
     *
     * @param \Twig_Environment $twig
     * @param Finder            $finder
     */
    public function __construct(\Twig_Environment $twig, Finder $finder)
    {
        $this->twig = $twig;
        $this->finder = $finder;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => array('onKernelView', 9999),
        );
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $result = $data = $event->getControllerResult();

        $cell = $request->attributes->get('_cell');

        if ($cell === null) {
            $controller = $request->attributes->get('_controller');
            // This to retrieve only the ShortName of the controller class:
            $cell = preg_replace('#.*\\\\([^\\\\]*?)(::__invoke)?$#', '$1', $controller);
        }

        $cellData = $this->finder->getData($cell, Finder::CELL);

        $response = (new CoreObjectResponseResolver($this->twig))->resolve($cellData, $result);

        if ($result instanceof COResponse) {
            $this->applyCacheDirective($response, $result);
        }

        $event->setResponse($response);
    }

    protected function applyCacheDirective(Response $response, COResponse $voResponse)
    {
        $response->setMaxAge($voResponse->getMaxAge());
        $response->setSharedMaxAge($voResponse->getSharedMaxAge());
    }
}
