<?php

namespace Kryptalabs\Reindex\Controller\Adminhtml\Indexer;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class MassReindexData extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Indexer\IndexerRegistry
     */
    protected $registry;

    /**
     * @var \Kryptalabs\Reindex\Helper\Data
     */
    protected $helper;

    /**
     * MassReindexData constructor.
     *
     * @param Action\Context $context
     * @param \Magento\Framework\Indexer\IndexerRegistry $registry
     * @param \Kryptalabs\Reindex\Helper\Data $helper
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Indexer\IndexerRegistry $registry,
        \Kryptalabs\Reindex\Helper\Data $helper
    ) {
        parent::__construct($context);
        $this->registry = $registry;
        $this->helper = $helper;
    }

    /**
     * Determine if action is allowed for module
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        if ($this->_request->getActionName() == 'massReindexData') {
            return $this->_authorization->isAllowed('Kryptalabs_Reindex::reindexdata')
                && $this->helper->isCoreModuleEnabled();
        }
        return false;
    }

    /**
     * Mass action reindex
     *
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        if (!is_array($indexerIds)) {
            $this->messageManager->addError(__('Please select indexers.'));
        } else {
            $startTime = microtime(true);
            foreach ($indexerIds as $indexerId) {
                try {
                    $indexer = $this->registry->get($indexerId);
                    $indexer->reindexAll();
                    $resultTime = (int)(microtime(true) - $startTime);
                    $this->messageManager->addSuccess(
                        '<div class="Kryptalabs-reindex-info">' . $indexer->getTitle() . ' index has been rebuilt successfully in ' . gmdate('H:i:s', (int)$resultTime) . '</div>'
                    );
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->messageManager->addError(
                        $indexer->getTitle() . ' indexer process unknown error:',
                        $e
                    );
                } catch (\Exception $e) {
                    $this->messageManager->addException(
                        $e,
                        __("We couldn't reindex data because of an error.")
                    );
                }
            }
            $this->messageManager->addSuccess(
                __('%1 indexer(s) have been rebuilt successfully <a href="javascript:void(0)" class="Kryptalabs-reindex-show">Show detail</a>', count($indexerIds))
            );
        }
        $this->_redirect('indexer/indexer/list');
    }
}
