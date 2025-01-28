<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Job;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use AuthorizesRequests;
    public function index(): View
    {
        $jobs = Job::latest()->paginate(9);
        return view('jobs.index')->with('jobs', $jobs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags'=>'nullable|string',
            'job_type'=>'required|string',
            'remote'=>'required|boolean',
            'requirements'=>'nullable|string',
            'benefits'=>'nullable|string',
            'address'=>'nullable|string',
            'city'=>'required|string',
            'state'=>'required|string',
            'zipcode'=>'nullable|string',
            'contact_email'=>'required|string',
            'contact_phone'=>'nullable|string',
            'company_name'=>'required|string',
            'company_description'=>'nullable|string',
            'company_logo'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_website'=>'nullable|url']);

        $validatedData['user_id']= auth()->user()->id;

        //Chech for image
        if($request->hasFile('company_logo')){
            $path=$request->file('company_logo')->store('logo','public');
            $validatedData['company_logo']=$path;
        }
        //Submit to database
        Job::create($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job): View
    {
        return view('jobs.show')->with('job', $job);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job): string
    {
        $this->authorize('update', $job);

        return view('jobs.edit')->with('job', $job);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job): string
    {
        $this->authorize('update', $job);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags'=>'nullable|string',
            'job_type'=>'required|string',
            'remote'=>'required|boolean',
            'requirements'=>'nullable|string',
            'benefits'=>'nullable|string',
            'address'=>'nullable|string',
            'city'=>'required|string',
            'state'=>'required|string',
            'zipcode'=>'nullable|string',
            'contact_email'=>'required|string',
            'contact_phone'=>'nullable|string',
            'company_name'=>'required|string',
            'company_description'=>'nullable|string',
            'company_logo'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_website'=>'nullable|url']);

        //Check for image
        if($request->hasFile('company_logo')){
            //Delete old image
            Storage::delete('public/logos' . basename($job->company_logo));
            $path=$request->file('company_logo')->store('logo','public');
            $validatedData['company_logo']=$path;
        }
        //Submit to database
        $job->update($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job): RedirectResponse
    {
        $this->authorize('delete', $job);

        if($job->company_logo){
            Storage::delete('public/logos'.$job->company_logo);
        }
        $job->delete();

        if(request()->query('from') == 'dashboard'){
            return redirect()->route('dashboard')->with('success', 'Job deleted successfully.');
        }

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function search(Request $request):View{
        $keywords= strtolower($request->input('keywords'));
        $location= strtolower($request->input('location'));

        $query= Job::query();

        if($keywords){
            $query->where(function($q) use($keywords){
                $q->whereRaw('LOWER(title) like ?', ['%'.$keywords.'%'])->
                    orWhereRaw('LOWER(description) like ?', ['%'.$keywords.'%'])->
                    orWhereRaw('LOWER(tags) like ?', ['%'.$keywords.'%']);
            });
        }
        if($location){
            $query->where(function($q) use($location){
                $q->whereRaw('LOWER(address) like ?', ['%'.$location.'%'])->
                orWhereRaw('LOWER(city) like ?', ['%'.$location.'%'])->
                orWhereRaw('LOWER(state) like ?', ['%'.$location.'%'])->
                orWhereRaw('LOWER(zip_code) like ?', ['%'.$location.'%']);
            });
        }

        $jobs = $query->paginate(12);
        return view('jobs.index')->with('jobs', $jobs);
    }
}
