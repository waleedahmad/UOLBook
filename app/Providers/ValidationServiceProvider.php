<?php

namespace App\Providers;
use Validator;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('roll_no', function($attribute, $value)
        {
            return substr( $value, 0, 2 ) === "BCS" || substr( $value, 0, 2 ) === "bcs" && is_int(substr( $value, 3, 10 ));
        });

        Validator::extend('subject_code', function($attribute, $value)
        {
            return substr( $value, 0, 1 ) === "CS" || substr( $value, 0, 1 ) === "cs";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
