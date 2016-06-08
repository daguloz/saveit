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
			<h3 class="panel-title" id="link-list-header">
				My links
				<span id="btn_show_edit_link" class="icon icon-pencil"></span>
			</h3>
		</div>
		<div class="panel-body noshow" id="alert_edit_info">
			<div class="alert alert-info fade in" role="alert">Selec a link to edit</div>
		</div>
		<div class="list-group" id="link-list">
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

						echo "\n\t\t\t<a href=\"{$l->url}\" title=\"{$name}\" alt=\"{$l->description}\" target=\"_blank\" class=\"list-group-item\">\n";
						echo "\t\t\t\t<h4 class=\"list-group-item-heading\">{$name}</h4>\n";
						echo "\t\t\t\t<p class=\"list-group-item-text\">{$l->description}</p>\n";
						echo "\t\t\t\t<div class=\"list-group-item-labels\">\n";
						foreach ($l->tags as $t)
						{
							echo "\t\t\t\t\t<span class=\"badge badge-default\" href=\"" . site_url('tag/' . $t->id) . "\">{$t->name}</span>\n";
						}
						echo "\t\t\t\t</div>\n";
						echo "\t\t\t</a>";
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
				<div class="form-group">
					<label for="add_link_tags">Tags</label>
					<input type="text" class="form-control" name="tags" id="add_link_tags" placeholder="Enter tags separated by commas">
				</div>
				<button type="submit" class="btn btn-success">Add new link</button>
			</form>
		</div>
		<div class="panel-body noshow" id="form-link-edit">
			<form method="POST">
				<input type="hidden" name="type" value="edit_link">
				<div class="form-group">
					<label for="edit_link_url">Url</label>
					<input type="url" class="form-control" name="url" id="edit_link_url" placeholder="http://www.google.com" required>
				</div>
				<div class="form-group">
					<label for="edit_link_name">Name</label>
					<input type="text" class="form-control" name="name" id="edit_link_name" placeholder="Google">
				</div>
				<div class="form-group">
					<label for="edit_link_description">Description</label>
					<input type="text" class="form-control" name="description" id="edit_link_description" placeholder="Search Engine">
				</div>
				<div class="form-group">
					<label for="edit_link_tags">Tags</label>
					<input type="text" class="form-control" name="tags" id="edit_link_tags" placeholder="Enter tags separated by commas">
				</div>
				<button type="submit" class="btn btn-success">Save</button>
				<button type="button" id="edit-link-cancel" class="btn btn-default">Cancel</button>
			</form>
		</div>
		
		<div class="panel-footer" id="link-list-bottom-buttons">
			<a id="btn_show_add_link" class="btn btn-primary">Add new link</a>
		</div>
		<?php else: ?>
		<div class="panel-heading">
			<h3 class="panel-title">Welcome to Saveit</h3>
		</div>
		<div class="panel-body">
			<ul class="nav nav-pills">
				<li role="presentation"><a class="btn btn-default" href="<?php echo site_url('register'); ?>">Register</a></li>
				<li role="presentation"><a class="btn btn-default" href="<?php echo site_url('login'); ?>">Login</a></li>
			</ul>
		</div>
		<?php endif; ?>
	</div>

</div>