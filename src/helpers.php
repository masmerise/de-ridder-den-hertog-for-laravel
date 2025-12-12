<?php declare(strict_types=1);

use DeRidderDenHertog\DeRidderDenHertog;

if (! function_exists('renh')) {
    function renh(): DeRidderDenHertog
    {
        return app('renh');
    }
}
