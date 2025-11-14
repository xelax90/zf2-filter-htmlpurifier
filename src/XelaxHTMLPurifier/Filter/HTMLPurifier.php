<?php

/*
 * Copyright (C) 2015 schurix
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace XelaxHTMLPurifier\Filter;

use Zend\Filter\FilterInterface;
use HTMLPurifier as _HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Description of HTMLPurifier
 *
 * @author schurix
 */
class HTMLPurifier implements FilterInterface
{
    protected $options = [];
    /**
     * The HTML Purifer object
     *
     * @var _HTMLPurifier
     */
    private $purifier;
 
    /**
     * Initialise the HTML Purifier object.
     */
    function __construct($options = [])
    {
        $this->options = $options;
        $purifierOptions = $this->options['purifier_options'] ?? [];

        $config = HTMLPurifier_Config::createDefault();
        foreach ($purifierOptions as $key => $value) {
            $config->set($key, $value);
        }

        $this->purifier = new _HTMLPurifier($config);
    }
 
    /**
     * Filter the value using HTML Purifier.
     *
     * @param string $value The value to filter
     * @see Zend_Filter_Interface::filter()
     */
    public function filter($value)
    {
        $result = $this->purifier->purify($value);
        $preventAmpEncoding = $this->options['HTML.PreventAmpEncoding'] ?? true;
        if ($preventAmpEncoding) {
            $result = str_replace('&amp;', '&', $result);
        }

        return $result;
    }
}