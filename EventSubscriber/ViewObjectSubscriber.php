<?php

namespace Rf\WebComponent\EngineBundle\EventSubscriber;

use Rf\WebComponent\EngineBundle\Finder\Finder;
use Rf\WebComponent\EngineBundle\Resolver\ViewObjectResponseResolver;
use Rf\WebComponent\EngineBundle\Utils\UtilsTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Rf\WebComponent\EngineBundle\ViewObject\Response as VOResponse;

/**
 * Class ViewObjectSubscriber.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
class ViewObjectSubscriber implements EventSubscriberInterface
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
     * ViewObjectSubscriber constructor.
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

        $webComponent = $request->attributes->get('_webcomponent');

        if ($webComponent === null) {
            $controller = $request->attributes->get('_controller');
            // This to retrieve only the ShortName of the controller class:
            $webComponent = preg_replace('#.*\\\\([^\\\\]*?)(::__invoke)?$#', '$1', $controller);
        }

        $webComponentData = $this->finder->getData($webComponent, Finder::WEB_COMPONENT);

        $response = (new ViewObjectResponseResolver($this->twig))->resolve($webComponentData, $result);

        if ($result instanceof VOResponse) {
            $this->applyCacheDirective($response, $result);
        }

        $event->setResponse($response);
    }

    protected function applyCacheDirective(Response $response, VOResponse $voResponse)
    {
        $response->setMaxAge($voResponse->getMaxAge());
        $response->setSharedMaxAge($voResponse->getSharedMaxAge());
    }
}
