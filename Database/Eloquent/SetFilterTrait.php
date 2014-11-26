<?php namespace Exolnet\Database\Eloquent;
/**
 * Copyright © 2014 eXolnet Inc. All rights reserved. (http://www.exolnet.com)
 *
 * This file contains copyrighted code that is the sole property of eXolnet Inc.
 * You may not use this file except with a written agreement.
 *
 * This code is distributed on an 'AS IS' basis, WITHOUT WARRANTY OF ANY KIND,
 * EITHER EXPRESS OR IMPLIED, AND EXOLNET INC. HEREBY DISCLAIMS ALL SUCH
 * WARRANTIES, INCLUDING WITHOUT LIMITATION, ANY WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE, QUIET ENJOYMENT OR NON-INFRINGEMENT.
 *
 * @package    Exolnet
 * @subpackage Database
 * @author     eXolnet Inc. <info@exolnet.com>
 */

use Illuminate\Support\Str;

trait SetFilterTrait
{
	public function getSetFilters()
	{
		return isset($this->setFilters) ? $this->setFilters : [];
	}

	public function getSetFiltersForAttribute($key)
	{
		return array_get($this->getSetFilters(), $key, []);
	}

	public function setAttribute($key, $value)
	{
		parent::setAttribute($key, $value);

		$filtersForAttribute = $this->getSetFiltersForAttribute($key);

		if (empty($filtersForAttribute)) {
			return;
		}

		$value = $this->attributes[$key];

		foreach ($filtersForAttribute as $filter) {
			if (is_callable($filter)) {
				$value = call_user_func($filter, $value);
			}
		}

		$this->attributes[$key] = $value;
	}
}