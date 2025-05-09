<?php

declare(strict_types=1);

namespace Authentication\EventListener;

use Common\Helper\RandomStringHelper;
use Authentication\Model\FailedLoginModelInterface;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;

class LoginListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $failedLoginModel;

    const onBeforeLogin = 'onBeforeLogin';
    const onFailedLogin = 'onFailedLogin';
    const onSuccessfullLogin = 'onSuccessfullLogin';

    public function __construct(FailedLoginModelInterface $failedLoginModel)
    {
        $this->failedLoginModel = $failedLoginModel;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(Self::onBeforeLogin, [$this, Self::onBeforeLogin]);
        $this->listeners[] = $events->attach(Self::onFailedLogin, [$this, Self::onFailedLogin]);
        $this->listeners[] = $events->attach(Self::onSuccessfullLogin, [$this, Self::onSuccessfullLogin]);
    }

    public function onBeforeLogin(EventInterface $e)
    {
        $params = $e->getParams();
        $username = trim($params['username']);
        //
        // check if the username coming with the IP address is banned ?
        // 
        if ($this->failedLoginModel->checkUsername($username)) {
            return [
                'banned' => true,
                'message' => $this->failedLoginModel->getMessage(),
            ];
        }
        return ['banned' => false];
    }

    public function onFailedLogin(EventInterface $e)
    {
        $params = $e->getParams();
        $request = $params['request'];
        $username = trim($params['username']);
        $ipAddress = $params['ip'];
        $server = $request->getServerParams();
        $userAgent = empty($server['HTTP_USER_AGENT']) ? 'unknown' : $server['HTTP_USER_AGENT'];

        $this->failedLoginModel->createAttempt(
            [
                'loginId' => RandomStringHelper::generateUuid(),
                'username' => $username,
                'attemptedAt' => date("Y-m-d"),
                'userAgent' => $userAgent,
                'ip' => $ipAddress,
            ]
        );
    }

    public function onSuccessfullLogin(EventInterface $e)
    {
        $params = $e->getParams();
        $username = trim($params['username']);
        $rowObject = $params['rowObject'];
        $translator = $params['translator'];
        $updateData = [
            'locale' => $translator->getLocale(), // last locale
            'lastLogin' => date("Y-m-d H:i:s", time()),
        ];
        /**
         * We delete failed login attempts for following scenarios:
         *
         * 1- When user do the successful login
         * 2- When the user clicks the reset link in the email we send
         */
        $this->failedLoginModel->deleteAttemptsAndUpdateUser(
            $updateData,
            ['username' => $username, 'userId' => $rowObject->userId]
        );
    }

}