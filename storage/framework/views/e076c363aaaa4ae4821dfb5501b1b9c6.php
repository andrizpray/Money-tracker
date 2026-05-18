<?php $__env->startSection('title', 'Reset Password - Money Tracker'); ?>

<?php $__env->startSection('content'); ?>
<h2 class="text-xl font-bold mb-2 text-center">Reset Password</h2>
<p class="text-text2 text-sm text-center mb-6">Masukkan email untuk link reset</p>

<form method="POST" action="<?php echo e(route('password.email')); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>
    <div>
        <label class="block text-sm font-medium text-text2 mb-2">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="w-full px-4 py-3 rounded-xl bg-surface2 border border-surface2 text-text placeholder-text2 focus:border-accent focus:outline-none">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-danger text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <button type="submit" class="w-full px-5 py-3 rounded-xl text-white font-medium" style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">Kirim Link Reset</button>
</form>

<p class="text-center text-text2 text-sm mt-6">
    <a href="<?php echo e(route('login')); ?>" class="text-accent hover:underline">Kembali ke Login</a>
</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/money-tracker/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>