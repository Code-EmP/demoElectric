<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<style>
    .login-section {
        background: #f8fafc;
        min-height: 65vh;
    }

    .login-icon {
        align-items: center;
        background: linear-gradient(135deg, #1e40af, #10b981);
        border-radius: 50%;
        color: #fff;
        display: inline-flex;
        font-size: 1.5rem;
        height: 3.5rem;
        justify-content: center;
        width: 3.5rem;
    }
</style>
<section class="login-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-7 col-lg-5">
                <div class="card border-0 shadow">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="login-icon mb-3" aria-hidden="true">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h1 class="h2 fw-bold text-primary-custom mb-2">Welcome Back</h1>
                            <p class="text-muted mb-0">Log in to your Puihaha Electric account</p>
                        </div>

                        <?php if (! empty($error)): ?>
                            <div class="alert alert-danger" role="alert"><?= esc($error) ?></div>
                        <?php endif; ?>

                        <?php if (! empty($success)): ?>
                            <div class="alert alert-success" role="alert"><?= esc($success) ?></div>
                        <?php endif; ?>

                        <form action="<?= base_url('login') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email address</label>
                                <input
                                    type="email"
                                    class="form-control form-control-lg"
                                    id="email"
                                    name="email"
                                    value="<?= esc(old('email')) ?>"
                                    autocomplete="email"
                                    required
                                    autofocus>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="password"
                                    name="password"
                                    autocomplete="current-password"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-right-to-bracket me-2" aria-hidden="true"></i>Log In
                            </button>
                        </form>

                        <p class="text-center text-muted mt-4 mb-0">
                            Don't have an account?
                            <a href="<?= base_url('register') ?>" class="fw-semibold">Register</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
