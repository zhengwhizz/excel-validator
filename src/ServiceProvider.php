<?php

namespace Zhengwhizz\ExcelValidator;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        Validator::extend('excel', function ($attribute, $value, $parameters, $validator) {
            if (!$value instanceof \Illuminate\Http\UploadedFile) {
                return false;
            }
            if (!in_array($value->getClientOriginalExtension(), ['xls', 'xlsx', 'csv'])) {
                return false;
            }
            $mime = $value->getClientMimeType();
            Log::debug('FILE MIME: ' . $mime);
            $needMimes = 'text/plain,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/octet-stream';
            return stripos($needMimes, $mime) !== false;
        });

        Validator::replacer('excel', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', join(",", $parameters), $message);
        });
    }

}
