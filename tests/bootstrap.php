<?php
/**
 * File bootstrap.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

//use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * PHPUnit Bootstrap
 *
 * Registers the Composer autoloader
 */
call_user_func(function() {
    if ( ! is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
        throw new \RuntimeException('Did not find vendor/autoload.php. Did you run "composer install --dev"?');
    }

    require_once $autoloadFile;

    AnnotationRegistry::registerLoader('class_exists');
    AnnotationRegistry::registerFile(__DIR__."/../vendor/jms/serializer/src/JMS/Serializer/Annotation/Type.php");
    AnnotationRegistry::registerFile(__DIR__."/../vendor/jms/serializer/src/JMS/Serializer/Annotation/SerializedName.php");
    AnnotationRegistry::registerFile(__DIR__."/../vendor/jms/serializer/src/JMS/Serializer/Annotation/Since.php");
    AnnotationRegistry::registerFile(__DIR__."/../vendor/jms/serializer/src/JMS/Serializer/Annotation/Inline.php");
    AnnotationRegistry::registerFile(__DIR__."/../vendor/jms/serializer/src/JMS/Serializer/Annotation/Accessor.php");
    AnnotationRegistry::registerFile(__DIR__."/../vendor/epfremme/swagger-php/src/Annotations/Discriminator.php");
});
