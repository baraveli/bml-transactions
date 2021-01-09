<?php

namespace Baraveli\BMLTransaction;

class BML
{
    public $client;

    public $userID;
    public $authenticationStatus;

    private $accounts; // accounts from the selected profile

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Attempts to login to BML and also sets the userid on the process.
     *
     * @param string $username
     * @param string $password
     * @param int    $account
     *
     * Note: $account, override default account index
     *
     * @return BML
     */
    public function login(string $username, string $password, int $account = 0): BML
    {
        $response = $this->client->postRequest(['j_username' => $username, 'j_password' => $password], 'm/login');
        $this->authenticationStatus = $response['authenticated'];

        $this->accounts = $this->GetAccounts();
        $this->SetUserID($account);

        return $this;
    }

    /**
     * Get the transactions made today.
     *
     * @return array
     */
    public function GetTodayTransactions(int $account = null): array
    {
        $account = $account ?? $this->userID;

        return $this->client->getRequest("account/$account/history/today");
    }

    /**
     * Get the Pending Transactions.
     *
     * @return array
     */
    public function GetPendingTransactions(int $account = null): array
    {
        $account = $account ?? $this->userID;

        return $this->client->getRequest("history/pending/$account");
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
    public function GetTransactionsBetween(string $from, string $to, string $page = '1', int $account = null): array
    {
        $account = $account ?? $this->userID;

        $from = date('Ymd', strtotime($from));
        $to = date('Ymd', strtotime($to));

        return $this->client->getRequest("account/$account/history/$from/$to/$page");
    }

    /**
     * Get Accounts.
     *
     * @return array
     */
    public function GetAccounts(): array
    {
        $response = $this->client->getRequest('dashboard');

        return $response['dashboard'];
    }

    /**
     * SetUserID.
     *
     * @param int $account
     *
     * @return void
     */
    protected function SetUserID($account): void
    {
        $this->userID = $this->accounts[$account]['id'];
    }

    /**
     * Get profiles.
     *
     * @return array
     */
    public function getProfile(): array
    {
        return $this->client->getRequest('profile');
    }

    /**
     * Select Profile.
     *
     * @param int $id
     */
    public function setProfile($id)
    {
        return $this->client->postRequest('profile', ['profile' => $id]);
    }
}
