<?php

namespace Qck\Interfaces;

/**
 * interface for classes providing an ActiveRecord
 * @author muellerm
 */
interface DataNodeConsumer extends DataNodeProvider
{

    /**
     * @return DataNode
     */
    function setDataNode( DataNode $ActiveRecord );
}
