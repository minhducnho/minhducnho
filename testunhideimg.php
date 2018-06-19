<pre>
<?php
use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();

$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$mediaApi = $objectManager->create('Magento\Catalog\Model\Product\Gallery\Processor');
$curPage = $objectManager->create('Magento\Framework\App\Request\Http')->getParam('page');
$collection = $objectManager->create('Magento\Catalog\Model\Product')->getCollection()->addAttributeToSelect('id');
$collection->setPage($curPage, 1000)->load();
$skucol = [];
$i =0;
$total = count($collection);
$count = 0;
foreach ($collection as $product) {
    $_product = $objectManager->create('Magento\Catalog\Model\Product')->load($product->getId());
    echo $_product->getSku()."<br />";
    $existingMediaGallery = $_product->getMediaGallery('images');
    print_r($existingMediaGallery);
    if($existingMediaGallery){
        if(count($existingMediaGallery) == 1) {continue;}         
        $i ++;
        echo "\r\n processing product $i of $total ";
     
        // Loop through product images
            foreach($existingMediaGallery as $_image){
                $filepath =  $objectManager->get('Magento\Framework\Filesystem')->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('catalog/product') . $_image['file'];
                echo "\r\n $filepath";
                if(file_exists($filepath)) {
                    $size = getimagesize($filepath);
                    if($size) {
                        echo "<br />".$_image['file'];
                        if($_image['disabled'] == 1)
                        {
                            $mediaApi->updateImage($_product, $_image['file'], ['disabled' => 0]);
                            file_put_contents('test_dupimg.log', print_r($_product->getSku(), true) . PHP_EOL ,FILE_APPEND);
                            try {
                                $_product->save();
                            } catch (\Exception $e) {
                                echo "<br />".$e->getMessage();
                                continue;
                            }
                            $count++;
                        } else {
                            continue;
                        }
                    }else {continue;}
                }else {continue;}
            }
    }
}

echo "Done";