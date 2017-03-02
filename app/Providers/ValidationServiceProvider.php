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
            return substr( $value, 0, 3 ) === "BCS" || substr( $value, 0, 3 ) === "bcs";
        });

        Validator::extend('subject_code', function($attribute, $value)
        {
            return substr( $value, 0, 2 ) === "CS" || substr( $value, 0, 2 ) === "cs";
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
