<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ScrapeCurrencyTest extends DuskTestCase
{
    /**
     * @test
     */
    public function ScrapeCurrency(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://www.dailyfx.com/forex-rates#currencies')
                    ->assertSee('Currencies')
                    ->waitFor('#currencies');
            $rows = $browser->elements('#currencies .dfx-instrumentTiles__tileContainer');

            foreach ($rows as $row) {
                $textContent = $row->getText();
                \Log::info($textContent); 
            }
        });
    }
}
