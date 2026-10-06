<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Puihaha Electric Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 30px;
            margin: 20px auto;
            max-width: 900px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container">
            <h1 class="mb-4"><?= esc($title) ?></h1>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary mb-4">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>

            <?php if ($message = session()->getFlashdata('error')): ?>
                <div class="alert alert-danger" role="alert"><?= esc($message) ?></div>
            <?php endif; ?>

            <?php $errors = session()->getFlashdata('errors') ?? []; ?>
            <?php if ($errors !== []): ?>
                <div class="alert alert-danger" role="alert">
                    <p class="mb-1">Please correct the following:</p>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= esc($formAction) ?>">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="account_number" class="form-label">Account Number</label>
                        <input id="account_number" name="account_number" class="form-control" maxlength="50" required value="<?= esc(old('account_number', $account['account_number'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Customer Name</label>
                        <input id="customer_name" name="customer_name" class="form-control" maxlength="150" required value="<?= esc(old('customer_name', $account['customer_name'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" class="form-control" maxlength="255" required value="<?= esc(old('email', $account['email'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" name="phone" type="tel" class="form-control" maxlength="20" required value="<?= esc(old('phone', $account['phone'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="meter_number" class="form-label">Meter Number</label>
                        <input id="meter_number" name="meter_number" class="form-control" maxlength="50" required value="<?= esc(old('meter_number', $account['meter_number'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input id="address" name="address" class="form-control" maxlength="255" required value="<?= esc(old('address', $account['address'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="connection_type" class="form-label">Connection Type</label>
                        <?php $connectionType = old('connection_type', $account['connection_type'] ?? 'residential'); ?>
                        <select id="connection_type" name="connection_type" class="form-select" required>
                            <option value="residential" <?= $connectionType === 'residential' ? 'selected' : '' ?>>Residential</option>
                            <option value="commercial" <?= $connectionType === 'commercial' ? 'selected' : '' ?>>Commercial</option>
                            <option value="industrial" <?= $connectionType === 'industrial' ? 'selected' : '' ?>>Industrial</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <?php $status = old('status', $account['status'] ?? 'active'); ?>
                        <select id="status" name="status" class="form-select" required>
                            <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            <option value="suspended" <?= $status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> <?= isset($account['id']) ? 'Save Changes' : 'Create Account' ?>
                    </button>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
