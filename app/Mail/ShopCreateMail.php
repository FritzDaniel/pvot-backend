<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShopCreateMail extends Mailable
{
    use Queueable, SerializesModels;
    public $namaToko;
    public $marketplace;
    public $kategoriToko;
    public $supplier;
    public $design;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($namaToko,$marketplace,$kategoriToko,$supplier,$design)
    {
        $this->namaToko = $namaToko;
        $this->marketplace = $marketplace;
        $this->kategoriToko = $kategoriToko;
        $this->supplier = $supplier;
        $this->design = $design;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.shopCreated')
            ->from('superadmin@pvotdigital.com','ADMIN PVOT DIGITAL')
            ->subject('New Shop')
            ->with(
                [
                    'namaToko', $this->namaToko,
                    'marketplace' => $this->marketplace,
                    'kategoriToko' => $this->kategoriToko,
                    'supplier' => $this->supplier,
                    'design' => $this->design
                ]
            );
    }
}
