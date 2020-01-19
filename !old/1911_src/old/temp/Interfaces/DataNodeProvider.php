<?php

namespace Qck\Interfaces;

/**
 * interface for classes providing an ActiveRecord
 * @author muellerm
 */
interface DataNodeProvider
{

    /**
     * @return DataNode
     */
    function getDataNode();
}
