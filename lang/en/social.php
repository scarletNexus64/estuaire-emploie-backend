<?php

return [
    'deleted_user' => 'Deleted user',

    'errors' => [
        'not_found' => 'Resource not found.',
        'item_not_found' => 'This post does not exist or is no longer available.',
        'comment_not_found' => 'This comment does not exist or has been deleted.',
        'parent_not_found' => 'The comment you are replying to no longer exists.',
        'company_not_found' => 'Company not found.',
        'forbidden' => 'You are not allowed to perform this action.',
        'not_comment_author' => 'Only the author can edit this comment.',
        'not_company_member' => 'This information is restricted to company members.',
        'cannot_repost_own' => 'You cannot repost your own post.',
        'cannot_follow_own_company' => 'You cannot follow your own company.',
        'user_not_found' => 'User not found.',
        'cannot_follow_self' => 'You cannot follow yourself.',
    ],

    'validation' => [
        'body_required' => 'The comment cannot be empty.',
        'body_max' => 'The comment may not exceed :max characters.',
        'parent_invalid' => 'Invalid parent comment.',
        'repost_comment_max' => 'The repost text may not exceed :max characters.',
        'search_min' => 'The search must contain at least 2 characters.',
    ],

    'push' => [
        'like_job' => [
            'title' => 'New like',
            'body' => ':name likes your job offer “:title”',
        ],
        'like_service' => [
            'title' => 'New like',
            'body' => ':name likes your service “:title”',
        ],
        'comment' => [
            'title' => 'New comment',
            'body' => ':name commented on “:title”: :excerpt',
        ],
        'reply' => [
            'title' => 'New reply',
            'body' => ':name replied to your comment',
        ],
        'repost' => [
            'title' => 'New repost',
            'body' => ':name reposted “:title”',
        ],
        'follow' => [
            'title' => 'New follower',
            'body' => ':name now follows :company',
        ],
        'user_follow' => [
            'title' => 'New follower',
            'body' => ':name started following you',
        ],
        'comment_like' => [
            'title' => 'New like',
            'body' => ':name likes your comment',
        ],
    ],
];
