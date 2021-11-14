<?php

namespace App\Jobs;

use App\Mail\ShopCreateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewCreateShopJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;
    public $email;
    public $phone;

    public $namaToko;
    public $marketplace;
    public $kategoriToko;
    public $supplier;
    public $design;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name,$email,$phone,$namaToko,$marketplace,$kategoriToko,$supplier,$design)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->namaToko = $namaToko;
        $this->marketplace = $marketplace;
        $this->kategoriToko = $kategoriToko;
        $this->supplier = $supplier;
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('danielsinaga53@gmail.com')->send(new ShopCreateMail(
            $this->name,$this->email,$this->phone,
            $this->namaToko,$this->marketplace,$this->kategoriToko,
            $this->supplier,$this->design
        ));
    }
}
