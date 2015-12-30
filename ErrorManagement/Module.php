<?php
/**
 * API Error Management
 *
 * PHP version 5.5
 *
 * @category Configuration
 * @package  ErrorManagement
 * @author   Abhinav <abhinav@osscube.com>
 * @license  http://www.beachbody.com, Beachbody, LLC.
 * @link     {}
 */
namespace ErrorManagement;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
class Module
{

    /**
     * To get the module configuartion
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * To get the autoloader configuartion
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    /**
     * To get the service configuartion
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'errormanagement' => 'ErrorManagement\Lib\ErrorManager',
                'coreError' => 'ErrorManagement\Entity\CoreError',
            )
        );
    }

}