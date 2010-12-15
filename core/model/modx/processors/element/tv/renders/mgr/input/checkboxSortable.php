<?php 
/** 
* @package modx 
* @subpackage processors.element.tv.renders.mgr.input 
*/ 
$this->xpdo->lexicon->load('tv_widget'); 

$default = explode("||",$this->get('default_text')); // all standard values 
$value = trim($value); 
$value = empty($value)? $default : explode("||",$value); // current saved values or default 

$index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name'))); 

$inputoptions = array(); 
foreach ($index_list as $inputoption){ 
$inputopt_array = (is_array($inputoption)) ? $inputoption : explode("==",$inputoption); 
$option['value'] = isset($inputopt_array[1])?$inputopt_array[1]:$inputopt_array[0]; 
$option['text'] = htmlspecialchars($inputopt_array[0],ENT_COMPAT,'UTF-8');
$option['checked']=false; 
$inputoptions[$option['value']] = $option; 
} 
$options = array(); 
if (!empty($value[0]) && count($value)>0){ 
foreach ($value as $itemvalue){ 
$option=$inputoptions[$itemvalue]; 
$option['checked']=true; 
$options[] = $option; 
unset($inputoptions[$itemvalue]); 
}	
} 

$options = count($options)>0?array_merge($options,$inputoptions):$inputoptions; 

//$this->xpdo->smarty->assign('cbdefaults',implode(',',$defaults)); 
$this->xpdo->smarty->assign('opts',$options); 
return $this->xpdo->smarty->fetch('element/tv/renders/input/checkboxSortable.tpl');