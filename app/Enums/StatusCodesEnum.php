<?php

namespace App\Enums;

enum StatusCodesEnum: int {
    case SUCCESS            = 0;
    case SHOW_TOAST_MESSAGE = 1;
    case FAILURE            = 1000;
    case VALIDATION_ERROR   = 1001;
    case UNAUTHENTICATED    = 1002;
    case UNAUTHORIZED       = 1003;
    case NOT_FOUND          = 1004;
    case SERVER_ERROR       = 1005;
    case THROTTLE_ERROR     = 1006;
}
