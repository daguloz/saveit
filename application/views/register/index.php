<div class="col-xs-12">

	<?php if (isset($error)): ?>
		<div class="alert alert-danger fade in" data-dismiss="alert" role="alert">
			<?php echo $error; ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif; ?>
	<p>
		Here you can register an account to use Saveit. If you have one already, <a href="<?php echo site_url('login'); ?>">please login</a>.
	</p>
</div>

<div class="col-xs-12 col-md-offset-4 col-md-4">
	<form class="form-horizontal" action="" method="POST">
		<div class="form-group">
			<label for="email" class="col-md-3 control-label">Email</label>
			<div class="col-md-9">
				<input type="email" class="form-control" id="email" name="email" placeholder="you@email.com" required>
			</div>
		</div>
		<div class="form-group">
			<label for="name" class="col-md-3 control-label">Name</label>
			<div class="col-md-9">
				<input type="text" class="form-control" id="name" name="name" required>
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="col-md-3 control-label">Password</label>
			<div class="col-md-9">
				<input type="password" class="form-control" id="password" name="password" placeholder="******" required>
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="col-md-3 control-label">Confirm password</label>
			<div class="col-md-9">
				<input type="password" class="form-control" id="password_check" name="password_check" placeholder="******" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-3 col-md-9">
				<?php if ($return) echo '<input type="hidden" name="return" value="' . $return . '">'; ?>
				<button type="submit" name="type" value="register" class="btn btn-primary">Register</button>
			</div>
		</div>
	</form>

</div>