<?php

return [

    'fields' => [

        'search_query' => [
            'label' => 'Søg',
            'placeholder' => 'Søg',
        ],

    ],

    'pagination' => [

        'label' => 'Paginering Navigation',

        'overview' => 'Viser :first til :last af :total resultater',

        'fields' => [

            'records_per_page' => [
                'label' => 'per side',
            ],

        ],

        'buttons' => [

            'go_to_page' => [
                'label' => 'Gå til side :page',
            ],

            'next' => [
                'label' => 'Næste',
            ],

            'previous' => [
                'label' => 'Forrige',
            ],

        ],

    ],

    'buttons' => [

        'filter' => [
            'label' => 'Filtrer',
        ],

        'open_actions' => [
            'label' => 'Åbn handlinger',
        ],

    ],

    'empty' => [
        'heading' => 'Ingen resultater',
    ],

    'selection_indicator' => [

        'buttons' => [

            'select_all' => [
                'label' => 'Vælg alle :count',
            ],

        ],

    ],

];
