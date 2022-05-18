<?php
return [
    [
        'name' => 'admin_sidebar.sub_article',
        'list-check' => ['menu','article'],
        'icon' => 'fa fa-edit',
		'level'  => [1,2],
        'sub'  => [
            [
                'name'  => 'Đơn vị',
                'namespace' => 'menu',
                'route' => 'admin.menu.index',
                'icon'  => 'fa-newspaper-o',
				'level'  => [1,2],
            ],
            [
                'name'  => 'Bài viết Album',
                'namespace' => 'article',
                'route' => 'admin.article.index',
                'icon'  => 'fa-edit',
				'level'  => [1,2],
            ],
        ]
    ],
	[
		'name' => 'admin_sidebar.sub_user',
		'list-check' => ['user','ncc'],
		'icon' => 'fa fa-user',
		'level'  => [1,2],
		'sub'  => [
			[
				'name'  => 'admin_sidebar.user',
				'route' => 'admin.user.index',
				'namespace' => 'user',
				'icon'  => 'fa fa-user',
				'level'  => [1,2],
			]
        ]
	]
];
