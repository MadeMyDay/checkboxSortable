<?php
/**
 * Checkbox Sortable
 *
 * @package checkboxsortable
 */
/**
 * Ensure that the paths are made writable so the files can be copied.
 *
 * @package checkboxsortable
 * @subpackage build
 */
$success = true;
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;

            $directories = array(
                $object->xpdo->getOption('core_path').'model/modx/processors/element/tv/renders/mgr/input/',
                $object->xpdo->getOption('manager_path').'templates/default/element/tv/renders/input/',
            );
            foreach ($directories as $dir) {
                @chmod($dir,0775);
                if (!is_writable($dir)) {
                    $success = false;
                }
            }

            break;
        case xPDOTransport::ACTION_UPGRADE:
            break;
    }
}
return $success;