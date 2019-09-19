<?php

namespace app\core\constant;

class Code {
    const ERROR_SYSTEM = -99999;//系统错误（代码错误）
    const ERROR_CONFIG = -99998;//配置错误
    const ERROR_FRAMEWORK = -99997;//系统错误（代码错误）
    const ERROR_LOGIN = -10000;//需要重新登录
    const ERROR_CHECK = -20000;
    const ERROR_SIGN = -30000;
    const ERROR_DATA_EMPTY = -99996;
    const ERROR_TIMEOUT = -99995;
}