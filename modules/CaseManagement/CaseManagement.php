<?php
/***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

include_once 'modules/Vtiger/CRMEntity.php';

class CaseManagement extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_casemanagement';
	var $table_index= 'casemanagementid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_casemanagementcf', 'casemanagementid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_casemanagement', 'vtiger_casemanagementcf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_casemanagement' => 'casemanagementid',
		'vtiger_casemanagementcf'=>'casemanagementid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = array (
		'LBL_CASE_NUMBER' => array('casemanagement', 'case_number'),
		'LBL_CASE_STATUS' => array('casemanagement', 'case_status'),
		'LBL_CASE_OPENED' => array('casemanagement', 'case_opened'),
		'LBL_SUBJECT' => array('casemanagement', 'subject'),

);
	var $list_fields_name = array (
		'LBL_CASE_NUMBER' => 'case_number',
		'LBL_CASE_STATUS' => 'case_status',
		'LBL_CASE_OPENED' => 'case_opened',
		'LBL_SUBJECT' => 'subject',

);

	// Make the field link to detail view
	var $list_link_field = 'case_number';

	// For Popup listview and UI type support
	var $search_fields = array (

);
	var $search_fields_name = array (

);

	// For Popup window record selection
	var $popup_fields = array('case_number');

	// For Alphabetical search
	var $def_basicsearch_col = 'case_number';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'case_number';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = array('createdtime', 'modifiedtime', 'case_number');

	var $default_order_by = 'case_number';
	var $default_sort_order='ASC';

	function CaseManagement() {
		$this->log =LoggerManager::getLogger('CaseManagement');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('CaseManagement');
	}

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
 		if($eventType == 'module.postinstall') {
 			//Enable ModTracker for the module
 			static::enableModTracker($moduleName);
			//Create Related Lists
			static::createRelatedLists();
		} else if($eventType == 'module.disabled') {
			// Handle actions before this module is being uninstalled.
		} else if($eventType == 'module.preuninstall') {
			// Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			//Create Related Lists
			static::createRelatedLists();
		}
 	}
	
	/**
	 * Enable ModTracker for the module
	 */
	public static function enableModTracker($moduleName)
	{
		include_once 'vtlib/Vtiger/Module.php';
		include_once 'modules/ModTracker/ModTracker.php';
			
		//Enable ModTracker for the module
		$moduleInstance = Vtiger_Module::getInstance($moduleName);
		ModTracker::enableTrackingForModule($moduleInstance->getId());
	}
	
	protected static function createRelatedLists()
	{
		include_once('vtlib/Vtiger/Module.php');	

		$moduleInstance = Vtiger_Module::getInstance('Contacts');
		$relatedModuleInstance = Vtiger_Module::getInstance('CaseManagement');
		$relationLabel = 'LBL_CASEMANAGEMENT_LIST';
		$moduleInstance->setRelatedList(
			$relatedModuleInstance, $relationLabel, array('ADD'), 'get_dependents_list'
		);

	}
}