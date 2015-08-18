<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpUnit\Runner\Filter\GroupIterator;

/**
 * @since Class available since Release 4.0.0
 */
class PHPUnit_Runner_Filter_Group_Exclude extends GroupIterator
{
    protected function doAccept($hash)
    {
        return !in_array($hash, $this->groupTests);
    }
}
