<?php

return [
    // Min duration video is 180s => 3 minute
    'min_duration_video'             => 180,

    // Ignore video duration bigger...
    'ignore_duration_video_trending' => 20,

    // Video duration may not be absolute. Video length may vary during this time period
    'in_the_previous_period'         => 20,
    'in_the_following_period'        => 20,

    // Allow size
    'allow_size_video_export'        => [
        'height' => 1024,
        'width'  => 576,
        '_frame_rate'  => "30/1"
    ]
];
