<?php $__env->startSection('title', 'Login - Money Tracker'); ?>

<?php $__env->startSection('content'); ?>
<h2 class="text-xl font-bold mb-6 text-center">Masuk</h2>

<form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-danger text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Password</label>
        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-danger text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="remember" class="rounded bg-surface2 border-surface2 text-accent focus:ring-accent">
            <span class="text-sm text-text2">Ingat saya</span>
        </label>
        <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-accent hover:underline">Lupa password?</a>
    </div>

    <button type="submit" class="w-full px-5 py-3 rounded-xl btn-primary text-white font-medium" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">Masuk</button>
</form>

<p class="text-center text-text2 text-sm mt-6">
    Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="text-accent hover:underline">Daftar</a>
</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/money-tracker/resources/views/auth/login.blade.php ENDPATH**/ ?>