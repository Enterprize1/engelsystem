<?php

use Engelsystem\Controllers\DesignController;

test('screenshot', function (int $themeId) {
    $page = visit("http://es_server/design?theme=$themeId");

    // Hide countdowns, which depend on the current time
    $page->page()->addStyleTag("[data-countdown-ts] {display:none}");

    $page->page()->waitForFunction('document.readyState === "complete"');
    $page->script("document.documentElement.scrollTop = 0");

    $page->assertScreenshotMatches();
})->with(function () {
    $config = require __DIR__ . '/../../config/config.default.php';
    foreach ($config['themes'] as $id => $theme) {
        yield $theme['name'] => $id;
    }
})->coversClass(DesignController::class);
