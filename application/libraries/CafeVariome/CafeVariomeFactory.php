<?php

/**
 * Description of CafeVariomeFactory
 * http://www.phptherightway.com/pages/Design-Patterns.html
 * @author owen
 */

class CafeVariomeFactory {
    public static function create($make, $model) {
		return new CafeVariome($make, $model);
	}

}

?>
