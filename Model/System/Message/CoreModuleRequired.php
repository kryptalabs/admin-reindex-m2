<?php

namespace Kryptalabs\Reindex\Model\System\Message;

/**
 * Class CoreModuleRequired
 * @package Kryptalabs\Reindex\Model\System\Message
 */
class CoreModuleRequired implements \Magento\Framework\Notification\MessageInterface
{
    const MESSAGE_IDENTITY = 'kryptalabs_core_module_required';

    /**
     * @var \Kryptalabs\Reindex\Helper\Data
     */
    private $helper;

    /**
     * CoreModuleRequired constructor.
     * @param \Kryptalabs\Reindex\Helper\Data $helper
     */
    public function __construct(
        \Kryptalabs\Reindex\Helper\Data $helper
    )
    {
        $this->helper = $helper;
    }

    /**
     * Retrieve unique system message identity
     *
     * @return string
     */
    public function getIdentity()
    {
        return self::MESSAGE_IDENTITY;
    }

    /**
     * Check whether the system message should be shown
     *
     * @return bool
     */
    public function isDisplayed()
    {
        // The message will be shown
        return !$this->helper->isCoreModuleEnabled();
    }

    /**
     * Retrieve system message text
     *
     * @return string
     */
    public function getText()
    {
        $moduleName = $this->helper->getModuleName();
        $text = __(
            '<b>Your module "%1" can not work without Kryptalabs 
                Core Module included in the package</b>',
            $moduleName);
        $script =
            '<script>
                setTimeout(function() {
                    jQuery("button.message-system-action-dropdown").trigger("click");
                }, 100);
            </script>';
        return $text . $script;
    }

    /**
     * Retrieve system message severity
     * Possible default system message types:
     * - MessageInterface::SEVERITY_CRITICAL
     * - MessageInterface::SEVERITY_MAJOR
     * - MessageInterface::SEVERITY_MINOR
     * - MessageInterface::SEVERITY_NOTICE
     *
     * @return int
     */
    public function getSeverity()
    {
        return self::SEVERITY_MAJOR;
    }
}