<?php

namespace Rf\WebComponent\EngineBundle\EventSubscriber;

use Rf\WebComponent\EngineBundle\Utils\UtilsTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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
     * ViewObjectSubscriber constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
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
        $controller = $request->attributes->get('_controller');

        $path = str_replace('\\', '/', preg_replace("#(?:.*?)[ViewObject|WebComponent]\\\\(.*?)\:(?:.*)#", "$1", $controller));

        if (preg_match('#(/[^/]*)$#', $path, $name)) {
            $name = $name[1];
        }

        $path = preg_replace('#(/[^/]*)$#', '', $path);
        $name = $this->fromCamelCase(isset($name) ? $name : $path);

        $event->setResponse(
            new Response(
                $this->twig->render(
                    sprintf('@wc/%s/%s.html.twig', $path, $name),
                    $event->getControllerResult()
                )
            )
        );
    }
}
