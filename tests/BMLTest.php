<?php

use Baraveli\BMLTransaction\BML;

class BMLTest extends \PHPUnit\Framework\TestCase
{
    protected $bml;

    protected function setUp(): void
    {
        $bml = new BML();
        $this->bml = $bml->login($_ENV['BML_USERNAME'], $_ENV['BML_PASSWORD']);
    }

    /** @test */
    public function can_login_and_authenticate_with_bml()
    {
        $bml = new BML();

        $response = $bml->login($_ENV['BML_USERNAME'], $_ENV['BML_PASSWORD']);
        $this->assertEquals(true, $response->authenticationStatus);
    }

    /** @test */
    public function it_can_get_transactions_made_today()
    {
        $response = $this->bml->GetTodayTransactions();

        $this->assertNotEmpty($response);
    }

    /** @test */
    public function it_can_get_pending_transactions()
    {
        $response = $this->bml->GetPendingTransactions();

        $this->assertNotEmpty($response);
    }

    /** @test */
    public function it_can_get_the_transactions_made_between_certain_dates()
    {
        $start = 'December 15 2019';
        $end = 'August 1 2020';
        $response = $this->bml->GetTransactionsBetween($start, $end);

        $date = strtotime($response['history'][0]['bookingDate']);

        $this->assertTrue($date >= strtotime($start) && $date <= strtotime($end));
    }
}
