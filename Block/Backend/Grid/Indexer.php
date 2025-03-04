<?php

namespace Kryptalabs\Reindex\Block\Backend\Grid;

/**
 * Class ItemsUpdater
 * @package Kryptalabs\Reindex\Block\Backend\Grid
 */
class Indexer extends \Magento\Framework\View\Element\Text
{
    /**
     * @var \Kryptalabs\Reindex\Helper\Data
     */
    private $helper;

    /**
     * Indexer constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Kryptalabs\Reindex\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Kryptalabs\Reindex\Helper\Data $helper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $script = "
        <script>
            var isCoreModuleEnabled = '{$this->helper->isCoreModuleEnabled()}';
            require(['jquery', 'domReady!'], function($) {
                'use strict';
                if (Boolean(isCoreModuleEnabled) !== true) {
                    $('#gridIndexer_massaction-select option[value=\"change_mode_reindex\"]').remove();
                }
                
                $('.kryptalabs-reindex-info').closest('.message-success.success').addClass('kryptalabs-hidden');
                $('.kryptalabs-reindex-show').click(function () {
                    if ($('.kryptalabs-reindex-info').length > 0) {
                        $('.kryptalabs-reindex-info').each(function () {
                            if ($(this).closest('.message-success.success').hasClass('kryptalabs-hidden')) {
                                $(this).closest('.message-success.success').removeClass('kryptalabs-hidden');
                            } else {
                                $(this).closest('.message-success.success').addClass('kryptalabs-hidden');
                            }
                        });
                    }
                });
            });
        </script>
        <style>
            .kryptalabs-hidden{
                display: none;
            }
        </style>";
        return $script;
    }

}
