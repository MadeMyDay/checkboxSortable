<?php
/**
 * Checkbox Sortable
 *
 * @package checkboxsortable
 */
/**
 * @package checkboxsortable
 * @subpackage build
 */
$settings = array();

$settings['checkboxsortable.test']= $modx->newObject('modSystemSetting');
$settings['checkboxsortable.test']->fromArray(array(
    'key' => 'checkboxsortable.test',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'checkboxsortable',
    'area' => 'Checkbox Sortable',
),'',true,true);

return $settings;