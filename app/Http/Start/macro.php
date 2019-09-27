<?php

namespace App\Http\Start;

use Collective\Html\FormBuilder as Form;

// Range dropdown with default value
	
Form::macro('selectRangeWithDefault', function($name, $start, $end, $selected = null, $default = null, $attributes = [])
{
    if ($default === null) {
        return Form::selectRange($name, $start, $end, $selected, $attributes);
    }
	
    $items = [];
    if (!in_array($default, $items)) {
        $items[''] = $default;
    }
    
	$interval = 1;
	
    if($start > $end) {
    	$interval = -1;
		for ($i=$start; $i>$end; $i+=$interval) {    	
        $items[$i . ""] = $i;
    	}
    }  else {
        for ($i=$start; $i<$end; $i+=$interval) {    	
        $items[$i . ""] = $i;
    	}
    }
	
    $items[$end] = $end;
    
    return Form::select($name, $items, isset($selected) ? $selected : '', $attributes);
});

// Month dropdown with default value

Form::macro('selectMonthWithDefault', function($name, $selected = null, $default = null, $options = [], $format = '%B')
{
    if ($default === null) {
        return Form::selectMonth($name, $selected, $options, $format);
    }
	
    $months = [];
    if (!in_array($default, $months)) {
        $months[''] = $default;
    }
    
    foreach(range(1, 12) as $month)
    {
      //$months[$month] = strftime($format, mktime(0, 0, 0, $month, 1));
      $months[$month] = strftime(trans('messages.lys.'.date("F", mktime(0, 0, 0, $month, 10))));

    }

    return Form::select($name, $months, isset($selected) ? $selected : '', $options);
});

