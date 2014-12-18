<?php


class ExportReportsCsvHeaders_Plugin  extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {

    public function init() {
        // register your events here

        // using anonymous function
        Pimcore::getEventManager()->attach("document.postAdd", function ($event) {
            // do something
            $document = $event->getTarget();
        });

        // using methods
        Pimcore::getEventManager()->attach("document.postUpdate", array($this, "handleDocument"));
    }public static function needsReloadAfterInstall() {
        return true;
    }

    public function handleDocument ($event) {
        // do something
        $document = $event->getTarget();
    }

    public static function install (){
        // we need a simple way to indicate that the plugin is installed, so we'll create a directory
 
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // NOTE - make sure that your plugin/ExportReportsCsvHeaders directory is writable by whatever user Apache runs as //
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
        $path = self::getInstallPath();
 
        if(!is_dir($path)) {
            mkdir($path);
        }
 
        if (self::isInstalled()) {
            return "ExportReportsCsvHeaders Plugin successfully installed.";
        } else {
            return "ExportReportsCsvHeaders Plugin could not be installed";
        }
        return true;
    }
    
    public static function uninstall (){
        rmdir(self::getInstallPath()); 
        if (!self::isInstalled()) {
            return "ExportReportsCsvHeaders Plugin successfully uninstalled.";
        } else {
            return "ExportReportsCsvHeaders Plugin could not be uninstalled";
        }
	return true;
    }

    public static function isInstalled () {
        return is_dir(self::getInstallPath());
    }
    
    public static function getTranslationFile($language) {
 
    }
 
    public static function getInstallPath() {
        return PIMCORE_PLUGINS_PATH."/ExportReportsCsvHeaders/install";
    }

}
