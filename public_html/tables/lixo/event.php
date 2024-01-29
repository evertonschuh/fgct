<?php

defined('_JEXEC') or die('Restricted access');

class TableEvent extends JTable
{
	var $id_event = null;
	var $status_event = null;
	var $id_client = null;

	var $name_event = null;
	var $slug_event = null;
	var $type_event = null;
	var $description_event = null;
	
	var $begin_event = null;
	var $end_event = null;

	var $begin_reserve_event = null;
	var $end_reserve_event = null;

	var $methods_event = null;
	
	var $limit_day_event = null;
	var $limit_break_event = null;

	var $drums_event = null;
	var $break_drums_event = null;
	var $time_drums_event = null;
	var $duration_drums_event = null;

	var $squad_event = null;
	var $break_squad_event = null;
	var $time_squad_event = null;
	var $duration_squad_event = null;

	var $position_event = null;
	var $break_position_event = null;
	var $time_position_event = null;
	var $duration_position_event = null;

	var $nr_event = null;
	var $ns_event = null;
	var $decimal_event = null;
	var $results_event = null;

	var $genres_event = null;
	var $categorys_event = null;
	var $classs_event = null;

	var $checked_out = null;
	var $checked_out_time = null;




	function __construct(&$db)
	{
		parent::__construct('#__event', 'id_event', $db);
	}
}
