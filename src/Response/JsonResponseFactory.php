<?php

namespace Plugin\ApiClient\Response;


/**
 * Class JsonResponseFactory
 *
 * @author Petr Marochkin <petun911@gmail.com>
 */
class JsonResponseFactory extends AbstractResponseFactory
{

    /**
     * @param $data
     *
     * @return mixed
     */
    public function parseData($data)
    {
        $data = json_decode($data, true);
        return $data;
    }
}