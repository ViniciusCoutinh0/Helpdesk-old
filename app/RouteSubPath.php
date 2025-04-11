<?php

namespace App;

use Pecee\SimpleRouter\Event\EventArgument;
use Pecee\SimpleRouter\Handlers\EventHandler;
use Pecee\SimpleRouter\Route\{ILoadableRoute, IGroupRoute};

class RouteSubPath
{
    public function __construct(public string $path)
    {
    }

    public function handler(): EventHandler
    {
        return (new EventHandler)->register(
            EventHandler::EVENT_ADD_ROUTE,
            function (EventArgument $event) {
                if (!$event->isSubRoute) {
                    switch (true) {
                        case $event->route instanceof ILoadableRoute:
                            $event->route->prependUrl($this->path);
                            break;
                        case $event->route instanceof IGroupRoute:
                            $event->route->prependPrefix($this->path);
                            break;
                    };
                }
            }
        );
    }
}
