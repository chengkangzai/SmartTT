<?php

return [

    'columns' => [

        'tags' => [
            'more' => 'dan :count lagi',
        ],

    ],

    'fields' => [

        'search_query' => [
            'label' => 'Cari',
            'placeholder' => 'Carian',
        ],

    ],

    'pagination' => [

        'label' => 'Navigasi Penomboran',

        'overview' => 'Menunjukkan :first ke :last dari :total rekod',

        'fields' => [

            'records_per_page' => [
                'label' => 'setiap halaman',
            ],

        ],

        'buttons' => [

            'go_to_page' => [
                'label' => 'Pergi ke halaman :page',
            ],

            'next' => [
                'label' => 'Seterusnya',
            ],

            'previous' => [
                'label' => 'Sebelumnya',
            ],

        ],

    ],

    'buttons' => [

        'disable_reordering' => [
            'label' => 'Selesai menyusun semula rekod',
        ],

        'enable_reordering' => [
            'label' => 'Menyusun semula rekod',
        ],

        'filter' => [
            'label' => 'Penapis',
        ],

        'open_actions' => [
            'label' => 'Tindakan terbuka',
        ],

        'toggle_columns' => [
            'label' => 'Togol lajur',
        ],

    ],

    'empty' => [
        'heading' => 'Tiada rekod dijumpai',
    ],

    'filters' => [

        'buttons' => [

            'remove' => [
                'label' => 'Buang penapis',
            ],

            'remove_all' => [
                'label' => 'Buang semua penapis',
                'tooltip' => 'Buang semua penapis',
            ],

            'reset' => [
                'label' => 'Tetapkan semula penapis',
            ],

            'close' => [
                'label' => 'Tutup',
            ],

        ],

        'indicator' => 'Penapis aktif',

        'multi_select' => [
            'placeholder' => 'Semua',
        ],

        'select' => [
            'placeholder' => 'Semua',
        ],

        'trashed' => [

            'label' => 'Rekod telah dipadamkan',

            'only_trashed' => 'Hanya rekod yang dipadamkan',

            'with_trashed' => 'Dengan rekod yang dipadam',

            'without_trashed' => 'Tanpa rekod yang dipadam',

        ],

    ],

    'reorder_indicator' => 'Seret dan lepaskan rekod mengikut susunan.',

    'selection_indicator' => [

        'selected_count' => '{1} 1 rekod dipilih.|[2,*] :count rekod yang dipilih.',

        'buttons' => [

            'select_all' => [
                'label' => 'Pilih semua :count',
            ],

            'deselect_all' => [
                'label' => 'Nyahpilih semua',
            ],

        ],

    ],

];
