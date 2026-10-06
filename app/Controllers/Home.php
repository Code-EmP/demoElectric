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
        if ($redirect = $this->requireLogin()) {
            return $redirect;
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
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $account = $this->customerModel->find($id);

        if ($account === null) {
            return redirect()->to('/dashboard')->with('error', 'Account not found.');
        }

        return view('home/view_account', ['account' => $account]);
    }

    public function newAccount()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('home/account_form', [
            'title' => 'Add Customer Account',
            'account' => [],
            'formAction' => base_url('accounts'),
        ]);
    }

    public function createAccount()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $validation = $this->validateAccountInput();
        if ($validation !== []) {
            return redirect()->to('/accounts/new')->withInput()->with('errors', $validation);
        }

        if (!$this->customerModel->insert($this->accountInput())) {
            log_message('error', 'Could not create customer account: {errors}', [
                'errors' => json_encode($this->customerModel->errors()),
            ]);
            return redirect()->to('/accounts/new')->withInput()->with('error', 'The account could not be created. Please check the submitted information and try again.');
        }

        return redirect()->to('/dashboard')->with('success', 'Customer account created successfully.');
    }

    public function editAccount(int $id)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $account = $this->customerModel->find($id);
        if ($account === null) {
            return redirect()->to('/dashboard')->with('error', 'Account not found.');
        }

        return view('home/account_form', [
            'title' => 'Edit Customer Account',
            'account' => $account,
            'formAction' => base_url('account/' . $id),
        ]);
    }

    public function updateAccount(int $id)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        if ($this->customerModel->find($id) === null) {
            return redirect()->to('/dashboard')->with('error', 'Account not found.');
        }

        $validation = $this->validateAccountInput();
        if ($validation !== []) {
            return redirect()->to('/account/' . $id . '/edit')->withInput()->with('errors', $validation);
        }

        if (!$this->customerModel->update($id, $this->accountInput())) {
            log_message('error', 'Could not update customer account {id}: {errors}', [
                'id' => $id,
                'errors' => json_encode($this->customerModel->errors()),
            ]);
            return redirect()->to('/account/' . $id . '/edit')->withInput()->with('error', 'The account could not be updated. Please check the submitted information and try again.');
        }

        return redirect()->to('/dashboard')->with('success', 'Customer account updated successfully.');
    }

    public function deleteAccount(int $id)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        if ($this->customerModel->find($id) === null) {
            return redirect()->to('/dashboard')->with('error', 'Account not found.');
        }

        if (!$this->customerModel->delete($id)) {
            log_message('error', 'Could not delete customer account {id}.', ['id' => $id]);
            return redirect()->to('/dashboard')->with('error', 'The account could not be deleted. Please try again.');
        }

        return redirect()->to('/dashboard')->with('success', 'Customer account deleted successfully.');
    }

    private function requireLogin()
    {
        return session()->get('is_logged_in') ? null : redirect()->to('/login');
    }

    private function validateAccountInput(): array
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'account_number' => 'required|max_length[50]',
            'customer_name' => 'required|min_length[2]|max_length[150]',
            'address' => 'required|max_length[255]',
            'phone' => 'required|min_length[7]|max_length[20]',
            'email' => 'required|valid_email|max_length[255]',
            'meter_number' => 'required|max_length[50]',
            'connection_type' => 'required|in_list[residential,commercial,industrial]',
            'status' => 'required|in_list[active,inactive,suspended]',
        ]);

        return $validation->withRequest($this->request)->run()
            ? []
            : $validation->getErrors();
    }

    private function accountInput(): array
    {
        return [
            'account_number' => trim((string) $this->request->getPost('account_number')),
            'customer_name' => trim((string) $this->request->getPost('customer_name')),
            'address' => trim((string) $this->request->getPost('address')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'email' => trim((string) $this->request->getPost('email')),
            'meter_number' => trim((string) $this->request->getPost('meter_number')),
            'connection_type' => (string) $this->request->getPost('connection_type'),
            'status' => (string) $this->request->getPost('status'),
        ];
    }
}
