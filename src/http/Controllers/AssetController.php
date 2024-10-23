<?php

namespace Maso\LaravelTerminal\http\Controllers;

use Illuminate\Routing\Controller;

class AssetController extends Controller
{

    /**
     * Provide the asset to the client
     */
    public function getAsset(string $asset)
    {

        if (
            !file_exists($path = __DIR__ . "/../../resources/css/{$asset}")
            && !file_exists($path = __DIR__ . "/../../resources/js/{$asset}")
            && !file_exists($path = __DIR__ . "/../../resources/fonts/{$asset}")
        )
        {
            abort(404);
        }

        $type = 'text/css';
        if (str_ends_with($asset, '.js'))
        {
            $type = 'application/javascript';
        }
        else if (str_ends_with($asset, '.woff'))
        {
            $type = ' application/font-woff2';
        }

        return response()->file($path, [
            'Content-Type' => $type,
        ]);
    }
}
