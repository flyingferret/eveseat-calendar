<?php

return [
    'calendar' => [
        'name'          => 'Fleep Ops',
        'label'         => 'Fleet Ops',
        'icon'          => 'fa-calendar',
        'route_segment' => 'calendar',
        'permission' => 'calendar.view',
        'entries' => [
            [
                'name'  => 'Operations',
                'icon'  => 'fa-space-shuttle',
                'route' => 'operation.index',
                'permission' => 'calendar.view'
            ],
            [
                'name'  => 'Timer Board',
                'icon'  => 'fa-clock-o',
                'route' => 'timers.index',
                'permission' => 'calendar.create'
            ],
            [
                'name'  => 'Settings',
                'icon'  => 'fa-cog',
                'route' => 'setting.index',
                'permission' => 'calendar.setup'
            ]
        ]
    ]
];
