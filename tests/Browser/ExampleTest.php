<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ScrapeExchangeCurrency;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * @test
     */
    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ScrapeExchangeCurrency)->assertSee('Currencies');
        });
    }
}
