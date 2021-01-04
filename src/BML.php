<?php

namespace Baraveli\BMLTransaction;

class BML
{
    protected $client;

    public $userID;
    public $authenticationStatus;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Attempts to login to BML and also sets the userid on the process.
     *
     * @param string $username
     * @param string $password
     * @param int	$account
     *
     * Note: $account is account index from dashboard
     *
     * @return BML
     */
    public function login(string $username, string $password, int $account = 0): BML
    {
        $response = $this->client->PostRequest(['j_username' => $username, 'j_password' => $password], 'm/login');
        $this->authenticationStatus = $response['authenticated'];
        $this->SetUserID($account);

        return $this;
    }

    /**
     * Get the transactions made today.
     *
     * @return array
     */
    public function GetTodayTransactions(): array
    {
        return $this->client->GetRequest('account/'.$this->userID.'/history/today');
    }

    /**
     * Get the Pending Transactions.
     *
     * @return array
     */
    public function GetPendingTransactions(): array
    {
        return $this->client->GetRequest("history/pending/$this->userID");
    }

    /**
     * Get the transactions made between the date range.
     *
     * Note: Can only get the transactions made with in 12 months.
     *
     * Example: $from = "December 15 2019", $to = "August 1 2020",
     *
     * @param string $from
     * @param string $to
     * @param string $page
     *
     * @return array
     */
    public function GetTransactionsBetween(string $from, string $to, string $page = '1'): array
    {
        $from = date('Ymd', strtotime($from));
        $to = date('Ymd', strtotime($to));

        return $this->client->GetRequest("account/$this->userID/history/$from/$to/$page");
    }

    /**
     * Get Accounts
     *
     * @return array
     */
     public function GetAccounts(): array
     {
        $response = $this->client->GetRequest('dashboard');
        return $response['dashboard'];
     }

    /**
     * SetUserID.
     *
     * @param integer $account
     *
     * @return void
     */
    protected function SetUserID($account): void
    {
        $response = $this->GetAccounts();
        $this->userID = $response[$account]['id'];
    }
}
