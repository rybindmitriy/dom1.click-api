<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Message\LockableMessageInterface;
use Symfony\Component\Lock\Exception\LockAcquiringException;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class MessengerLockMiddleware implements MiddlewareInterface
{
    public function __construct(private LockFactory $lockFactory)
    {
    }

    /**
     * @throws LockAcquiringException
     * @throws LockConflictedException
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if (false === $message instanceof LockableMessageInterface) {
            return $stack->next()->handle($envelope, $stack);
        }

        $lock = $this->lockFactory->createLock($message->getLockKey(), 60);

        $lock->acquire(true);

        try {
            return $stack->next()->handle($envelope, $stack);
        } finally {
            $lock->release();
        }
    }
}
