<?php

return [
    'required' => 'Параметр ":attribute" обов\'язковий.',
    'string' => 'Параметр ":attribute" повинен бути текстом.',
    'between' => [
        'string' => 'Параметр ":attribute" повинен бути довжиною від :min до :max символів.',
    ],
    'in' => 'Параметр ":attribute" не є валідним.',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    'attributes' => [
        'name' => 'ім\'я',
        'password' => 'пароль',
        'student' => 'студент',
        'supervisor' => 'керівник',
        'theme' => 'тема',
        'group' => 'група',
        'project_type_id' => 'тип проекту',
    ],
];
