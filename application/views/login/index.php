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
		Welcome to Saveit. To continue, please login, or <a href="/register">register a new account</a>.
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
			<label for="password" class="col-md-3 control-label">Password</label>
			<div class="col-md-9">
				<input type="password" class="form-control" id="password" name="password" placeholder="******" required>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-3 col-md-9">
				<div class="checkbox">
					<label>
						<input name="save" type="checkbox"> Save session
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-offset-3 col-md-9">
				<?php if ($return) echo '<input type="hidden" name="return" value="' . $return . '">'; ?>
				<button type="submit" name="type" value="login" class="btn btn-primary">Login</button>
			</div>
		</div>
	</form>

</div>