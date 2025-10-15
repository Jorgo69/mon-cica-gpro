<?php

return [
    'step_1' => [
        'title' => 'key information',
        'description' => 'basic project details',
        'form' => [
            'title' => 'key project information',
            'description' => 'enter basic administrative details and choose the project type',
            'input_1' => 'project type',
            'option' => 'select a project type',
            'select' => 'project type description',
            'input_2' => 'project title',
            'input_3' => 'project code',
            'input_4' => 'short title (optional)',
            'input_5' => 'start date',
            'input_6' => 'end date',
        ],
        'type_message' => 'no dynamic fields configured for this project type'
    ],
    'step_2' => [
        'title' => 'background and documents',
        'description' => 'description et fichiers pertinents',
    ],
    'step_3' => [
        'title' => 'description and relevant files',
        'description' => 'specific goal and objectives',
    ],
    'step_4' => [
        'title' => 'expected results',
        'description' => 'concrete deliverables of the project',
    ],
    'step_5' => [
        'title' => 'activities',
        'description' => 'preliminary actions',
    ],
    'step_6' => [
        'title' => 'finalization',
        'description' => 'verification and submission',
    ],

];