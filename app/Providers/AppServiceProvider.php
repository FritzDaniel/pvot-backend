<?php

namespace App\Providers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        Schema::defaultStringLength(125);

        view()->composer('*', function (View $view){
            if (Auth::check()){
                if(Auth::user()->isSupplier())
                {
                    $order = Payment::where('supplier_id','=',Auth::user()->id)
                        ->where('status','=','Paid')
                        ->count();
                    $view->with('order', $order);
                }
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
