<?php
declare(strict_types=1);

namespace Kadoco\Snapchat\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class SnapchatDataProvider implements ArgumentInterface
{
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;
    /**
     * @var RemoteAddress
     */
    private RemoteAddress $remoteAddress;

    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        RemoteAddress $remoteAddress
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->remoteAddress = $remoteAddress;
    }

    public function getIsActive():bool
    {
        return (bool) $this->getConfigValue('snapchat/configuration/active');
    }

    public function getPixelId(): string
    {
        return (string) $this->getConfigValue('snapchat/configuration/pixelid');
    }

    public function isTrackPurchases(): bool
    {
        return (bool) $this->getConfigValue('snapchat/configuration/purchases');
    }

    public function isCloudflare(): bool
    {
        return (bool) $this->getConfigValue('snapchat/configuration/cloudflare');
    }

    private function getConfigValue(string $path): string
    {
        $storeId = $this->storeManager->getStore()->getId();

        return (string) $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
