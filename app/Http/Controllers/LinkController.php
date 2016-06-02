<?php

namespace App\Http\Controllers;

use App\Link;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\LinkRepository;

class LinkController extends Controller
{
	/**
     * The link repository instance.
     *
     * @var LinkRepository
     */
	protected $links;

	/**
     * Create a new controller instance.
     *
     * @param  LinkRepository  $links
     * @return void
     */
    public function __construct(LinkRepository $links)
    {
    	$this->middleware('auth');

    	$this->links = $links;
    }

	/**
     * Display a list of all of the user's links.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {   	
    	return view('links.index', [
    		'links' => $this->links->forUser($request->user()),
		]);
    }

    /**
     * Create a new link.
     *
     * @param Request $request
     * @return Response
     */

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required|max:255',
    		'url' => 'required|max:255',
    		'description' => 'required|max:255'
		]);

        $tagsExploded = explode(',', $request->tags);
        $taglist = [];

        foreach ($taglist as $t) {
            $taglist[] = new App\Tag(['name' => $t]);
        }
        
    	$request->user()->links()->create([
    		'name' => $request->name,
    		'url' => $request->url,
    		'description' => $request->description,
        ]);
        
        $request->user()->links()->tags()->saveMany($taglist);
		
		return redirect('/links');
    }

    public function destroy(Request $request, Link $link)
    {
    	$this->authorize('destroy', $link);

    	$link->delete();

    	return redirect('/links');
    }
}
