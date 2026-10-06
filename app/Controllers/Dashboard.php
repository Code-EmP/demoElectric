<?php

namespace App\Controllers;

use App\Models\CustomerAccountModel;

class Dashboard extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerAccountModel();
    }

    public function index()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $accounts = $this->getDashboardAccounts();

        $data = [
            'title' => 'Puihaha Electric Company',
            'page' => 'dashboard',
            'accounts' => $accounts,
            'stats' => [
                'total_accounts' => count($accounts),
                'active_accounts' => count(array_filter($accounts, fn ($account) => strtolower((string) ($account['status'] ?? '')) === 'active')),
                'inactive_accounts' => count(array_filter($accounts, fn ($account) => strtolower((string) ($account['status'] ?? '')) === 'inactive')),
                'suspended_accounts' => count(array_filter($accounts, fn ($account) => strtolower((string) ($account['status'] ?? '')) === 'suspended')),
            ],
            'search_keyword' => '',
            'filter_status' => 'All Status',
            'filter_type' => 'All Types',
        ];

        return view('dashboard', $data);
    }

    public function tasks()
    {
        $data = [
            'title' => 'Project Tasks',
            'page' => 'tasks',
            'tasks' => [
                'Add CRUD functionalities',
                'Connect it to Puihaha Electric Company',
                'Add session (login form) that will redirect it to the CRUD / Dashboard',
            ],
        ];

        return view('tasks', $data);
    }

    protected function getDashboardAccounts(): array
    {
        $db = \Config\Database::connect();

        if (!$db->tableExists('customer_accounts')) {
            return [
                [
                    'account_number' => 'EC-2024-0001',
                    'customer_name' => 'John Smith',
                    'email' => 'john.smith@email.com',
                    'phone' => '555-0101',
                    'connection_type' => 'Residential',
                    'status' => 'Active',
                ],
                [
                    'account_number' => 'EC-2024-0002',
                    'customer_name' => 'Sarah Johnson',
                    'email' => 'sarah.j@email.com',
                    'phone' => '555-0102',
                    'connection_type' => 'Residential',
                    'status' => 'Active',
                ],
                [
                    'account_number' => 'EC-2024-0003',
                    'customer_name' => 'ABC Corporation',
                    'email' => 'contact@abc.com',
                    'phone' => '555-0103',
                    'connection_type' => 'Commercial',
                    'status' => 'Active',
                ],
                [
                    'account_number' => 'EC-2024-0004',
                    'customer_name' => 'Michael Brown',
                    'email' => 'mbrown@email.com',
                    'phone' => '555-0104',
                    'connection_type' => 'Residential',
                    'status' => 'Active',
                ],
                [
                    'account_number' => 'EC-2024-0005',
                    'customer_name' => 'Tech Industries Inc',
                    'email' => 'info@techindustries.com',
                    'phone' => '555-0105',
                    'connection_type' => 'Industrial',
                    'status' => 'Active',
                ],
                [
                    'account_number' => 'EC-2024-0006',
                    'customer_name' => 'Emily Davis',
                    'email' => 'emily.d@email.com',
                    'phone' => '555-0106',
                    'connection_type' => 'Residential',
                    'status' => 'Active',
                ],
            ];
        }

        return $this->customerModel->orderBy('created_at', 'DESC')->findAll(10);
    }
}
