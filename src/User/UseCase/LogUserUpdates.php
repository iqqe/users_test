<?php

declare(strict_types=1);

namespace App\User\UseCase;

use App\User\Event\UserUpdatedEvent;
use App\User\UseCase\UserUpdatesLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogUserUpdates implements EventSubscriberInterface
{
    public function __construct(private UserUpdatesLogger $userUpdatesLogger)
    {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [UserUpdatedEvent::class => 'onUserUpdated'];
    }

    public function onUserUpdated(UserUpdatedEvent $event): void
    {
        $this->userUpdatesLogger->log($event->getUserUpdatedFields());
    }
}