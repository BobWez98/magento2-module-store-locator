<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\StoreLocator
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\StoreLocator\Block;

use Magento\Framework\DataObject\IdentityInterface;

/**
 * Retailer View Block
 *
 * @category Smile
 * @package  Smile\StoreLocator
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class View extends AbstractView implements IdentityInterface
{
    /**
     * @var \Smile\StoreLocator\Helper\Data
     */
    private $storeLocatorHelper;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context            Application context.
     * @param \Magento\Framework\Registry                      $coreRegistry       Application registry.
     * @param \Smile\StoreLocator\Helper\Data                  $storeLocatorHelper Store locator helper.
     * @param array                                            $data               Block Data.
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Smile\StoreLocator\Helper\Data $storeLocatorHelper,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $data);
        $this->storeLocatorHelper = $storeLocatorHelper;
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        $identities = [];
        if ($this->getRetailer()) {
            $identities = $this->getRetailer()->getIdentities();
        }

        return $identities;
    }

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($this->getRetailer()) {
            $this->setPageTitle()
                ->setPageMeta()
                ->setBreadcrumbs();
        }

        return $this;
    }

    /**
     * Set the current page title.
     *
     * @return \Smile\StoreLocator\Block\View
     */
    private function setPageTitle()
    {
        $retailer = $this->getRetailer();

        $titleBlock = $this->getLayout()->getBlock('page.main.title');

        if ($titleBlock) {
            $titleBlock->setPageTitle(__('Ranzijn tuin &amp; dier - %1', $retailer->getName()));
        }

        $pageTitle = $retailer->getMetaTitle();
        if (empty($pageTitle)) {
            $pageTitle = $retailer->getName();
        }

        $this->pageConfig->getTitle()->set(__('Ranzijn tuin &amp; dier - %1', $pageTitle));

        return $this;
    }

    /**
     * Set the current page meta attributes (keywords, description).
     *
     * @return \Smile\StoreLocator\Block\View
     */
    private function setPageMeta()
    {
        $retailer = $this->getRetailer();

        $keywords = $retailer->getMetaKeywords();
        if ($keywords) {
            $this->pageConfig->setKeywords($retailer->getMetaKeywords());
        }

        // Set the page description.
        $description = $retailer->getMetaDescription();
        if ($description) {
            $this->pageConfig->setDescription($retailer->getMetaDescription());
        }

        return $this;
    }

    /**
     * Build breadcrumbs for the current page.
     *
     * @return \Smile\StoreLocator\Block\View
     */
    private function setBreadcrumbs()
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $retailer            = $this->getRetailer();
            $homeUrl             = $this->_storeManager->getStore()->getBaseUrl();
            $storeLocatorHomeUrl = $this->storeLocatorHelper->getHomeUrl();

            $breadcrumbsBlock->addCrumb('home', ['label' => __('Home'), 'title' => __('Go to Home Page'), 'link' => $homeUrl]);
            $breadcrumbsBlock->addCrumb('search', ['label' => __('Our stores'), 'title' => __('Our stores'), 'link' => $storeLocatorHomeUrl]);
            $breadcrumbsBlock->addCrumb('store', ['label' => $retailer->getName(), 'title' => $retailer->getName()]);
        }

        return $this;
    }
}
