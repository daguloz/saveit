<?php

namespace App\Repositories;

use App\User;

class LinkRepository
{
	
	/**
	 * Get all of the links for a given user.
	 *
	 * @param User $user
	 * @return Collection
	 */
	public function forUser(User $user)
	{
		return $user->links()
					->orderBy('created_at', 'asc')
					->get();
	}
}