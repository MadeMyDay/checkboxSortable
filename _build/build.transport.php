<?php
/**
 * Checkbox Sortable
 *
 * @package checkboxsortable
 */
/**
 * Checkbox Sortable transport package build script
 *
 * @package checkboxsortable
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* set package defines */
define('PKG_ABBR','checkboxsortable');
define('PKG_NAME','checkboxSortable');
define('PKG_VERSION','1.0');
define('PKG_RELEASE','beta1');

/* override with your own defines here (see build.config.sample.php) */
require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$root = dirname(dirname(__FILE__)).'/';
$assets = MODX_ASSETS_PATH.'components/'.PKG_ABBR.'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/',
    'data' => $root . '_build/data/',
    'validators' => $root.'_build/validators/',
    'lexicon' => $root.'core/components/checkboxsortable/lexicon/',
    'docs' => $root.'core/components/checkboxsortable/docs/',
    'source_core' => $root.'core/components/checkboxsortable',
);
unset($root);

$modx->initialize('mgr');
echo '<pre>';
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_ABBR,PKG_VERSION,PKG_RELEASE);
/* namespace */
$builder->registerNamespace(PKG_ABBR,false,true,'{core_path}components/'.PKG_ABBR.'/');

/* load system settings */
$modx->log(modX::LOG_LEVEL_INFO,'Packaging in System Settings...');
$settings = include $sources['data'].'transport.settings.php';
if (!is_array($settings)) $modx->log(modX::LOG_LEVEL_ERROR,'Could not package in settings.');
$attributes= array(
    xPDOTransport::UNIQUE_KEY => 'key',
    xPDOTransport::PRESERVE_KEYS => true,
    xPDOTransport::UPDATE_OBJECT => false,
);
$i = 0;
foreach ($settings as $setting) {
    $vehicle = $builder->createVehicle($setting,$attributes);
    if ($i == 0) {
        $vehicle->validate('php',array(
            'source' => $sources['validators'] . 'paths.validator.php',
        ));
        $vehicle->resolve('file',array(
            'source' => $sources['source_core'],
            'target' => "return MODX_CORE_PATH . 'components/';",
        ));
        $vehicle->resolve('file',array(
            'source' => $sources['data'] . 'input/checkboxSortable.php',
            'target' => "return MODX_CORE_PATH . 'model/modx/processors/element/tv/renders/mgr/input/';",
        ));
        $vehicle->resolve('file',array(
            'source' => $sources['data'].'input/checkboxSortable.tpl',
            'target' => "return MODX_MANAGER_PATH . 'templates/default/element/tv/renders/input/';",
        ));
    }
    $builder->putVehicle($vehicle);
    $i++;
}
unset($settings,$setting,$attributes);

/* now pack in the license file, readme and setup options */
$modx->log(modX::LOG_LEVEL_INFO,'Adding package attributes and setup options...');
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
));

$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();