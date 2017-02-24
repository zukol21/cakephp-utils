<?php
// Burzum File-Storage plugin configuration
return [
    // default thumbnail setup for all $entity->model entities for file_storage
    'ThumbnailVersions' => [
        'huge' => [
            'thumbnail' => ['width' => 2000, 'height' => 2000]
        ],
        'large' => [
            'thumbnail' => ['width' => 1024, 'height' => 1024]
        ],
        'medium' => [
            'thumbnail' => ['width' => 500, 'height' => 500]
        ],
        'small' => [
            'thumbnail' => ['width' => 150, 'height' => 150]
        ],
        'tiny' => [
            'thumbnail' => ['width' => 50, 'height' => 50]
        ]
    ],
    'FileStorage' => [
        'imageProcessing' => true,
        'pathBuilderOptions' => ['pathPrefix' => '/uploads'],
        'association' => 'UploadDocuments',
        'imageSizes' => [
            'file_storage' => [
                'huge' => [
                    'thumbnail' => ['width' => 2000, 'height' => 2000]
                ],
                'large' => [
                    'thumbnail' => ['width' => 1024, 'height' => 1024]
                ],
                'medium' => [
                    'thumbnail' => ['width' => 500, 'height' => 500]
                ],
                'small' => [
                    'thumbnail' => ['width' => 150, 'height' => 150]
                ],
                'tiny' => [
                    'thumbnail' => ['width' => 50, 'height' => 50]
                ]
            ]
        ]
    ]
];
