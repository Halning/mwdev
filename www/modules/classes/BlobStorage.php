<?php
/**
 * Created by PhpStorm.
 * User: webler
 * Date: 10.05.16
 * Time: 20:05
 */

namespace Modules;

use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Common\ServicesBuilder;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateBlobOptions;

class BlobStorage
{
    private $blobRestProxy;

    private $createContainerOptions;

    private $createBlobOptions;

    private $setBlobOptions;

    public function __construct()
    {
        $this->blobRestProxy = ServicesBuilder::getInstance()->createBlobService(BLOB_STORAGE);


        // OPTIONAL: Set public access policy and metadata.
        // Create container options object.
        $this->createContainerOptions = new CreateContainerOptions();

        // Set public access policy. Possible values are
        // PublicAccessType::CONTAINER_AND_BLOBS and PublicAccessType::BLOBS_ONLY.
        // CONTAINER_AND_BLOBS:
        // Specifies full public read access for container and blob data.
        // proxys can enumerate blobs within the container via anonymous
        // request, but cannot enumerate containers within the storage account.
        // BLOBS_ONLY:
        // Specifies public read access for blobs. Blob data within this
        // container can be read via anonymous request, but container data is not
        // available. proxys cannot enumerate blobs within the container via
        // anonymous request.
        // If this value is not specified in the request, container data is
        // private to the account owner.
        $this->createContainerOptions->setPublicAccess(PublicAccessType::BLOBS_ONLY);

        // Set container metadata.
//        $this->createContainerOptions->addMetaData("x-ms-blob-cache-control", "max-age=259200");

        $this->setBlobOptions = new SetBlobPropertiesOptions();
        $this->setBlobOptions->setBlobCacheControl('max-age=604800');

        $this->createBlobOptions = new CreateBlobOptions();
        $this->createBlobOptions->setBlobCacheControl('max-age=604800');
        $this->createBlobOptions->setContentType('image/jpeg');

    }

    public function createContainer($container)
    {
        try {
            // Create container.
            $this->blobRestProxy->createContainer($container, $this->createContainerOptions);
        } catch(ServiceException $e) {
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179439.aspx
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
    }

    public function deleteContainer($container)
    {
        try {
            // Delete container.
            $this->blobRestProxy->deleteContainer($container);
        } catch (ServiceException $e) {
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179439.aspx
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
    }

    public function getListBlobsInContainer($container)
    {
        $arrayBlobs = array();

        try {

            // List blobs.
            $blobList = $this->blobRestProxy->listBlobs($container);
            $blobs = $blobList->getBlobs();

            foreach ($blobs as $blob) {
//                echo $blob->getName().": ".$blob->getUrl()."<br />";
                $arrayBlobs[] = $blob->getName();
            }
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }

        return $arrayBlobs;
    }

    public function isContainer($container)
    {
        $isContainer = false;
        try {

            $isContainer = $this->blobRestProxy->getContainerProperties($container);

        } catch(ServiceException $e) {
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179439.aspx
//            $code = $e->getCode();
//            $error_message = $e->getMessage();
//            echo $code.": ".$error_message."<br />";
        }

        return (bool)$isContainer;
    }

    public function getListAllContainers()
    {
        return $this->blobRestProxy->listContainers();
    }

    public function getBlob($container, $blob)
    {
        $content = '';

        try {
            // Get blob.
            $blob = $this->blobRestProxy->getBlob($container, $blob);
            $content = stream_get_contents($blob->getContentStream());
        }
        catch(ServiceException $e){
            // Handle exception based on error codes and messages.
            // Error codes and messages are here:
            // http://msdn.microsoft.com/library/azure/dd179439.aspx
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }

        return $content;
    }

    public function uploadBlob($path, $name, $container)
    {
        $content = fopen($path, "r");

        //для картинок товарів вказуєм хедери
        $options = is_numeric($container) ? $this->createBlobOptions : null;

        try {

            //Upload blob
            $this->blobRestProxy->createBlockBlob($container, $name, $content, $options);
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code . ": " . $error_message . "<br />";
        }
    }

    public function setBlobCacheControl($container, $blob)
    {
       $this->blobRestProxy->setBlobProperties($container, $blob, $this->setBlobOptions);
    }

    public function deleteAllBlobsInContainer($container)
    {
        $arrayBlobs = $this->getListBlobsInContainer($container);

        if (count($arrayBlobs)) {
            foreach ($arrayBlobs as $blob) {

                try {

                    $this->blobRestProxy->deleteBlob($container, $blob);

                } catch (ServiceException $e) {
                    $code = $e->getCode();
                    $error_message = $e->getMessage();
                    echo $code.": ".$error_message."<br />";
                }
            }
        }
    }
    public function deleteBlob($container, $blob)
    {
        try {

            $this->blobRestProxy->deleteBlob($container, $blob);

        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            echo $code.": ".$error_message."<br />";
        }
    }
}
