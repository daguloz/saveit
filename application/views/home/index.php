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
				<span id="link-list-header-text">My links</span>
				<span id="link-list-header-buttons">
					<span id="button-show-link-add" class="icon icon-plus"></span>
					<span id="button-show-link-edit" class="icon icon-pencil"></span>
					<span id="button-show-link-delete" class="icon icon-cross"></span>
				</span>
			</h3>
		</div>
		<div class="panel-body noshow" id="alert-info-edit">
			<div class="alert alert-info fade in" role="alert">Select a link to edit</div>
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

						echo "\n\t\t\t<a href=\"{$l->url}\" title=\"{$name}\" alt=\"{$l->description}\" data-node-id=\"{$l->id}\" target=\"_blank\" class=\"list-group-item\">\n";
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
		<div class="panel-body noshow" id="form-link-add" class="form-link">
			<label for=""node-add-type">Type</label>
			<div class="radio">
				<label>
					<input type="radio" name="node-add-type" id="node-add-type-link" value="link" checked>
					Link
				</label>
			</div>
			<div class="radio">
				<label>
					<input type="radio" name="node-add-type" id="node-add-type-category" value="category">
					Category
				</label>
			</div>

			<form method="POST" id="node-add-link">
				<input type="hidden" name="type" value="link-add">
				<div class="form-group">
					<label for="link-add-url">Url</label>
					<input type="url" class="form-control" name="url" id="link-add-url" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="link-add-name">Name</label>
					<input type="text" class="form-control" name="name" id="link-add-name" placeholder="">
				</div>
				<div class="form-group">
					<label for="link-add-description">Description</label>
					<input type="text" class="form-control" name="description" id="link-add-description" placeholder="">
				</div>
				<div class="form-group">
					<label for="link-add-category">Category</label>
					<select class="form-control select2" name="category" id="link-add-category">
						<option value="" selected>Uncategorized</option>
						<?php foreach ($categories as $cat) {
							echo "<option value=\"{$cat->id}\">{$cat->name}</option>";
						} ?>
					</select>
				</div>
				<div class="form-group">
					<label for="link-add-tags">Tags</label>
					<input type="text" class="form-control" name="tags" id="link-add-tags" placeholder="Enter tags separated by commas">
				</div>
				<input type="submit" class="submit" style="display:none;">
			</form>
			<form method="POST" id="node-add-category" class="noshow">
				<input type="hidden" name="type" value="category-add">
				<div class="form-group">
					<label for="category-add-name">Name</label>
					<input type="text" class="form-control" name="name" id="category-add-name" placeholder="" required>
				</div>
				<div class="form-group">
					<label for="category-add-name">Parent Category</label>
					<select class="form-control select2" name="parent" id="category-add-parent">
						<option value="" selected>None</option>
						<?php foreach ($categories as $cat) {
							echo "<option value=\"{$cat->id}\">{$cat->name}</option>";
						} ?>
					</select>
				</div>
				<div class="form-group">
					<label for="category-add-description">Description</label>
					<input type="text" class="form-control" name="description" id="category-add-description" placeholder="">
				</div>
				<div class="form-group">
					<label for="category-add-tags">Tags</label>
					<input type="text" class="form-control" name="tags" id="category-add-tags" placeholder="Enter tags separated by commas">
				</div>
				<input type="submit" class="submit" style="display:none;">
			</form>
		</div>
		<div class="panel-body noshow" id="form-link-edit" class="form-link">
			<form method="POST">
				<input type="hidden" name="type" value="link-edit">
				<div class="form-group">
					<label for="link-edit-url">Url</label>
					<input type="url" class="form-control" name="url" id="link-edit-url" placeholder="http://www.google.com" required>
				</div>
				<div class="form-group">
					<label for="link-edit-name">Name</label>
					<input type="text" class="form-control" name="name" id="link-edit-name" placeholder="Google">
				</div>
				<div class="form-group">
					<label for="link-edit-description">Description</label>
					<input type="text" class="form-control" name="description" id="link-edit-description" placeholder="Search Engine">
				</div>
				<div class="form-group">
					<label for="link-edit-tags">Tags</label>
					<input type="text" class="form-control" name="tags" id="link-edit-tags" placeholder="Enter tags separated by commas">
				</div>
				<input type="submit" class="submit" style="display:none;">
			</form>
		</div>
		
		<div class="panel-footer noshow" id="link-list-footer-buttons">
			<button type="button" id="button-link-save" class="btn btn-success">Save</a>
			<button type="button" class="btn btn-default button-link-cancel">Cancel</a>
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

<div class="modal fade" id="link-delete-dialog" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Confirm deletion</h4>
			</div>
			<div class="modal-body">
				<p>Do you really want to delete this link?</p>
				<ul>
					<li><strong>URL: </strong><span id="link-delete-url"></span></li>
					<li><strong>Name: </strong><span id="link-delete-name"></span></li>
					<li><strong>Description: </strong><span id="link-delete-description"></span></li>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default button-link-cancel" data-dismiss="modal">Cancel</button>
				<button type="button" id="link-delete-confirm" class="btn btn-danger">Delete</button>
			</div>
			<form method="POST" id="link-delete-form">
				<input type="hidden" name="type" value="link-delete">
				<input type="hidden" name="id" id="link-delete-id">
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->