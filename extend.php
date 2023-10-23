<?php

/*
 * This file is part of mattoid/daily-check-in-history.
 *
 * Copyright (c) 2023 mattoid.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Flarum\Extend;
use Mattoid\CheckinHistory\Middleware\UserAuthMiddleware;
use Ziven\checkin\Event\updatedEvent;
use Mattoid\CheckinHistory\Listeners\DoCheckinHistory;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less')
        ->route('/u/{username}/checkin/history', 'mattoid-daily-check-in-history.forum.page.link-name'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Middleware("api"))->add(UserAuthMiddleware::class),

    (new Extend\Event())->listen(updatedEvent::class, [DoCheckinHistory::class, 'checkinHistory']),

    (new Extend\Routes('api'))
        ->get('/checkin/history', 'checkin.history', Mattoid\CheckinHistory\Api\Controller\ListCheckinHistoryController::class)
        ->post('/supplement/checkin', 'supplement.checkin', Mattoid\CheckinHistory\Api\Controller\PostCheckinHistoryController::class)
];
