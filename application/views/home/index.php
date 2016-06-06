<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div class="col-xs-12">

	<?php if (isset($error)): ?>
		<div class="alert alert-danger fade in" data-dismiss="alert" role="alert">
			<?php echo $error; ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<div class="panel panel-primary">
		<?php if ($logged_in === true): ?>
		<div class="panel-heading">
			<h3 class="panel-title">My links</h3>
		</div>
		<div class="list-group">
			<?php
				if (count($links) == 0)
				{
					echo "<h4 class=\"list-group-item\">You have no links saved :(</h4>";
				}
				else
				{
					foreach ($links as $l)
					{
						$l->name === NULL ? $name = $l->url : $name = $l->name;

						echo "<a href=\"{$l->url}\" target=\"_blank\" class=\"list-group-item\">";
						echo "<h4 class=\"list-group-item-heading\">{$name}</h4>";
						echo "<p>{$l->description}</p>";
						echo "</a>";
					}
				}
			?>
		</div>
		<div class="panel-body noshow" id="form_add_link">
			<form method="POST">
				<input type="hidden" name="type" value="add_link">
				<div class="form-group">
					<label for="add_link_url">Url</label>
					<input type="url" class="form-control" name="url" id="add_link_url" placeholder="http://www.google.com" required>
				</div>
				<div class="form-group">
					<label for="add_link_name">Name</label>
					<input type="text" class="form-control" name="name" id="add_link_name" placeholder="Google">
				</div>
				<div class="form-group">
					<label for="add_link_description">Description</label>
					<input type="text" class="form-control" name="description" id="add_link_description" placeholder="Search Engine">
				</div>
				<button type="submit" class="btn btn-success">Add new link</button>
			</form>
		</div>
		<div class="panel-footer">
			<a id="btn_show_add_link" class="btn btn-primary">Add new link</a>
		</div>
		<?php else: ?>
		<div class="panel-heading">
			<h3 class="panel-title">Welcome to Saveit</h3>
		</div>
		<div class="panel-body">
			<ul class="nav nav-pills">
				<li role="presentation"><a class="btn btn-default" href="/register">Register</a></li>
				<li role="presentation"><a class="btn btn-default" href="/login">Login</a></li>
			</ul>
		</div>
		<?php endif; ?>
	</div>

</div>