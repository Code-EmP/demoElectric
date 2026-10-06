<?php

namespace App\Controllers;

use App\Models\CustomerAccountModel;

class Home extends BaseController
{
    protected CustomerAccountModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerAccountModel();
    }

    public function index()
    {
        return view('home', [
            'title' => 'PowerFlow Electric - Reliable Energy Solutions',
            'page' => 'home',
        ]);
    }

    public function management()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $keyword = trim((string) $this->request->getGet('search'));
        $status = trim((string) $this->request->getGet('status'));
        $type = trim((string) $this->request->getGet('type'));

        $query = $this->customerModel->orderBy('created_at', 'DESC');

        if ($keyword !== '') {
            $query->groupStart()
                ->like('account_number', $keyword)
                ->orLike('customer_name', $keyword)
                ->orLike('email', $keyword)
                ->orLike('phone', $keyword)
                ->groupEnd();
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($type !== '') {
            $query->where('connection_type', $type);
        }

        $accounts = $query->paginate(10);

        $data = [
            'accounts' => $accounts,
            'pager' => $this->customerModel->pager,
            'total_accounts' => (new CustomerAccountModel())->countAll(),
            'active_accounts' => (new CustomerAccountModel())->where('status', 'active')->countAllResults(),
            'inactive_accounts' => (new CustomerAccountModel())->where('status', 'inactive')->countAllResults(),
            'suspended_accounts' => (new CustomerAccountModel())->where('status', 'suspended')->countAllResults(),
            'current_page' => $this->request->getGet('page') ?? 1,
            'search_keyword' => $keyword,
            'filter_status' => $status,
            'filter_type' => $type,
        ];

        return view('home/index', $data);
    }

    public function viewAccount(int $id)
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $account = $this->customerModel->find($id);

        if ($account === null) {
            return redirect()->to('/dashboard')->with('error', 'Account not found.');
        }

        return view('home/view_account', ['account' => $account]);
    }
}
