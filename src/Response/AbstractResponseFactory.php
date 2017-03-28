<?php

namespace Plugin\ApiClient\Response;


/**
 * Class AbstractResponseFactory
 *
 * @author Petr Marochkin <petun911@gmail.com>
 */
abstract class AbstractResponseFactory
{
    /**
     * @param $data
     *
     * @return array
     */
    abstract function parseData($data);
}