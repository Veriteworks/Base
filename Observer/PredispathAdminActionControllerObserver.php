<?php

namespace Veriteworks\Base\Model;

use Magento\AdminNotification\Model\Feed;
use Magento\AdminNotification\Model\InboxFactory;
use Magento\Backend\App\ConfigInterface;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\HTTP\Adapter\CurlFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Module\Manager;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

/**
 * Class AdminNotificationFeed
 */
class PredispathAdminActionControllerObserver extends Feed
{
    /**
     * @var string
     */
    const VERITEWORKS_CACHE_KEY = 'veriteworks_admin_notifications_lastcheck';

    const VERITEWORKS_FEED_URL = 'https://notifications.veriteworks.co.jp/notifications.xml';

    const VERITEWORKS_XML_FREQUENCY = 24;

    /**
     * AdminNotificationFeed constructor.
     *
     * @param Context                  $context
     * @param Registry                 $registry
     * @param ConfigInterface          $backendConfig
     * @param InboxFactory             $inboxFactory
     * @param Session                  $backendAuthSession
     * @param ModuleListInterface      $moduleList
     * @param Manager                  $moduleManager
     * @param CurlFactory              $curlFactory
     * @param DeploymentConfig         $deploymentConfig
     * @param ProductMetadataInterface $productMetadata
     * @param UrlInterface             $urlBuilder
     * @param AbstractResource|null    $resource
     * @param AbstractDb|null          $resourceCollection
     * @param array                    $data
     */
    public function __construct(
        protected Session             $backendAuthSession,
        protected ModuleListInterface $moduleList,
        protected Manager             $moduleManager,
        Context                       $context,
        Registry                      $registry,
        ConfigInterface               $backendConfig,
        InboxFactory                  $inboxFactory,
        CurlFactory                   $curlFactory,
        DeploymentConfig              $deploymentConfig,
        ProductMetadataInterface      $productMetadata,
        UrlInterface                  $urlBuilder,
        AbstractResource              $resource = null,
        AbstractDb                    $resourceCollection = null,
        array                         $data = []
    ) {
        parent::__construct($context, $registry, $backendConfig, $inboxFactory, $curlFactory, $deploymentConfig, $productMetadata, $urlBuilder, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl(): string
    {
        return self::VERITEWORKS_FEED_URL;
    }

    /**
     * Retrieve Update Frequency
     *
     * @return int
     */
    public function getFrequency(): int
    {
        return self::VERITEWORKS_XML_FREQUENCY * 3600;
    }

    /**
     * Retrieve Last update time
     *
     * @return int
     */
    public function getLastUpdate(): int
    {
        return $this->_cacheManager->load(self::VERITEWORKS_CACHE_KEY);
    }

    /**
     * Set last update time (now)
     *
     * @return $this
     */
    public function setLastUpdate(): static
    {
        $this->_cacheManager->save(time(), self::VERITEWORKS_CACHE_KEY);
        return $this;
    }
}
