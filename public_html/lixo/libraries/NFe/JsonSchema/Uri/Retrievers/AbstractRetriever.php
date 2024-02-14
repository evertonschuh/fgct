<?php
/**
 * JsonSchema
 *
 * @filesource
 */

//namespace JsonSchema\Uri\Retrievers;
require_once(JPATH_LIBRARIES . DS . 'NFe'. DS . 'JsonSchema' . DS . 'Uri' . DS . 'Retrievers' . DS . 'UriRetrieverInterface.php');
/**
 * AbstractRetriever implements the default shared behavior
 * that all descendant Retrievers should inherit
 *
 * @author Steven Garcia <webwhammy@gmail.com>
 */
abstract class AbstractRetriever implements UriRetrieverInterface
{
    /**
     * Media content type
     *
     * @var string
     */
    protected $contentType;

    /**
     * {@inheritdoc}
     *
     * @see \JsonSchema\Uri\Retrievers\UriRetrieverInterface::getContentType()
     */
    public function getContentType()
    {
        return $this->contentType;
    }
}
