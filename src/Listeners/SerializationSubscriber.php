<?php

namespace TreeSoft\SwaggerRestValidator\Listeners;

use JMS\Serializer\EventDispatcher\PreDeserializeEvent;
use Epfremme\Swagger\Listener\SerializationSubscriber as BaseSubscriber;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class SerializationSubscriber extends BaseSubscriber
{
    /**
     * @param PreDeserializeEvent $event
     */
    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        parent::onPreDeserialize($event);
        $data = $event->getData();
        $this->recursiveUnset($data, 'example');
        $event->setData($data);
    }

    /**
     * @param $array
     * @param mixed $unwantedKey
     */
    private function recursiveUnset(&$array, $unwantedKey) {
        unset($array[$unwantedKey]);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursiveUnset($value, $unwantedKey);
            }
        }
    }

}
