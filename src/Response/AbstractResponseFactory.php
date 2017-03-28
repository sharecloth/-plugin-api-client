<?php

namespace Plugin\ApiClient\Response;


/**
 * Class AbstractResponseFactory
 *
 * @author Petr Marochkin <petun911@gmail.com>
 */
abstract class AbstractResponseFactory
{
    abstract function parseData($data);
}