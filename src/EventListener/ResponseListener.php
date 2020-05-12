<?php 
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ResponseListener {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    function tidyHtml($html)
    {
        $dom = new \DOMDocument();

        if (libxml_use_internal_errors(true) === true)
        {
            libxml_clear_errors();
        }

        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $html = preg_replace(array('~\R~u', '~>[[:space:]]++<~m'), array("\n", '><'), $html);

        if ((empty($html) !== true) && ($dom->loadHTML($html) === true))
        {
            $dom->formatOutput = true;

            if (($html = $dom->saveXML($dom->documentElement, LIBXML_NOEMPTYTAG)) !== false)
            {
                $regex = array
                (
                    '~' . preg_quote('<![CDATA[', '~') . '~' => '',
                    '~' . preg_quote(']]>', '~') . '~' => '',
                    '~></(?:area|base(?:font)?|br|col|command|embed|frame|hr|img|input|keygen|link|meta|param|source|track|wbr)>~' => ' />',
                );

                return '<!DOCTYPE html>' . "\n" . preg_replace(array_keys($regex), $regex, $html);
            }
        }

        return false;
    }   


    public function onKernelResponse(ResponseEvent $event) {
        $request = $event->getRequest();
        //only when format == html and environment == dev
        if ($request->getRequestFormat() == 'html' && $this->container->get('kernel')->getEnvironment() == 'dev') {
            $event->getResponse()->setContent($this->tidyHtml($event->getResponse()->getContent()));
        }

    }

}