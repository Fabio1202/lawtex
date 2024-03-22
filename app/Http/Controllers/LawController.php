<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Parsers\Base\LawParser;
use App\Parsers\Base\ParserNotFoundException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Gate;

class LawController extends Controller
{
    public function store(Project $project, LawParser $parser)
    {
        Gate::authorize('update', $project);

        $data = request()->validate([
            'url' => 'required|url',
        ]);

        if ($project->laws()->where('url', $data['url'])->exists()) {
            return back()->withErrors(['create.url' => 'This law is already in the project.'])->withInput();
        }

        try {
            $parsedInformation = $parser->parseInformation($data['url']);
            $law = $parsedInformation->toLaw();
            $law->project()->associate($project);
            $law->save();
        } catch (ParserNotFoundException $e) {
            back()->withErrors(['create.url' => 'The provided URL is not yet supported.'])->withInput();
        } catch (ConnectException $e) {
            back()->withErrors(['create.url' => 'The provided URL could not be reached.'])->withInput();
        }

        return back();
    }
}
