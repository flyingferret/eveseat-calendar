<?php

return [
    'calendar' => [
        'name'          => 'Fleep Ops',
        'label'         => 'Fleet Ops',
        'icon'          => 'fas fa-calendar-alt',
        'route_segment' => 'calendar',
        'permission' => 'calendar.view',
        'entries' => [
            [
                'name'  => 'Operations',
                'icon'  => 'fas fa-space-shuttle',
                'route' => 'operation.index',
                'permission' => 'calendar.view'
            ],
            [
                'name'  => 'Timer Board',
                'icon'  => 'fas fa-clock',
                'route' => 'timers.index',
                'permission' => 'calendar.create'
            ],
            [
                'name'  => 'Settings',
                'icon'  => 'fas fa-cog',
                'route' => 'setting.index',
                'permission' => 'calendar.setup'
            ]
        ]
    ]
];
