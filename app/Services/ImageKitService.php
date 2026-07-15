<?php

namespace App\Services;

use GuzzleHttp\HandlerStack;
use ImageKit\ImageKit;
use Psr\Http\Message\RequestInterface;

class ImageKitService
{
    /**
     * Get an ImageKit instance with Guzzle SSL verification bypassed
     * to resolve local and server certificate issues.
     *
     * @return ImageKit
     */
    public static function getInstance()
    {
        $handlerStack = HandlerStack::create();
        $handlerStack->push(function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $options['verify'] = false;
                return $handler($request, $options);
            };
        });

        return new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT'),
            \ImageKit\Utils\Transformation::DEFAULT_TRANSFORMATION_POSITION,
            $handlerStack
        );
    }
}
