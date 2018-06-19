<?php
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');
$registry = $objectManager->get('\Magento\Framework\Registry');
$registry->register('isSecureArea', true);
$objectManager->create('Magento\Sitemap\Model\Sitemap')->load(1)->generateXml();
echo "\nDone";