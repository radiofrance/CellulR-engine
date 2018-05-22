<?php

namespace Rf\CellulR\EngineBundle\Limenius;

use Limenius\ReactRenderer\Renderer\ExternalServerReactRenderer;

/**
 * Class ExternalServerReactRendererExtension
 *
 * @package Rf\CellulR\EngineBundle\Limenius
 */
class ExternalServerReactRendererExtension extends ExternalServerReactRenderer
{
    /**
     * {@inheritdoc}
     */
    public function render($componentName, $propsString, $uuid, $registeredStores = array(), $trace)
    {
        if (strpos($this->serverSocketPath, '://') === false) {
            $this->serverSocketPath = 'unix://'.$this->serverSocketPath;
        }

        $sock = @stream_socket_client($this->serverSocketPath, $errno, $errstr);

        if (!$sock) {
            $this->logger->error('React Renderer '.$errno.' '.$errstr);
            return '';
        }

        stream_socket_sendto($sock, $this->wrap($componentName, $propsString, $uuid, $registeredStores, $trace)."\0");

        $contents = '';

        while (!feof($sock)) {
            $contents .= fread($sock, 8192);
        }

        fclose($sock);

        $result = json_decode($contents, true);

        if ($result['hasErrors']) {
            $this->logErrors($result['consoleReplayScript']);

            if ($this->failLoud) {
                $this->throwError($result['consoleReplayScript'], $componentName);
            }
        }

        return $result['html'].$result['consoleReplayScript'];
    }
}
