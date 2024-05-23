<?php

namespace Veriteworks\Base\Observer;

use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Veriteworks\Base\Model\AdminNotificationFeedFactory;

/**
 * Class PredispatchAdminActionControllerObserver
 */
class PredispatchAdminActionControllerObserver implements ObserverInterface
{
    /**
     * @param AdminNotificationFeedFactory $feedFactory
     * @param Session $backendAuthSession
     */
    public function __construct(
        protected AdminNotificationFeedFactory $feedFactory,
        protected Session $backendAuthSession,
    ) {
    }

    /**
     * Predispath admin action controller
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if ($this->backendAuthSession->isLoggedIn()) {
            $feedModel = $this->feedFactory->create();
            $feedModel->checkUpdate();
        }
    }
}
