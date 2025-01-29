<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Job;
use App\Mail\JobApplied;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{

    public function store(Request $request, Job $job): RedirectResponse{
        $existingApplication = Applicant::where('job_id', $job->id)->where('user_id', auth()->id())->exists();

        if($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job');
        }
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:255',
            'contact_email' => 'required|string|max:255',
            'message' => 'required|string',
            'location' => 'required|string|max:255',
            'resume' => 'required|mimes:pdf|max:2048'
        ]);
        if($request->hasFile('resume')){
            $path = $request->file('resume')->store('resumes', 'public');
            $validatedData['resume_path'] = $path;
        }
        $applicant=new Applicant($validatedData);
        $applicant->job_id = $job->id;
        $applicant->user_id = auth()->id();
        $applicant->save();

       // Mail::to($job->user->email)->send(new JobApplied());

        return redirect()->back()->with('success', 'Your application has been submitted!');
    }

    public function destroy($id): RedirectResponse
    {
        $applicant=Applicant::findOrFail($id);
        $applicant->delete();
        return redirect()->route('dashboard')->with('success', 'Applicant has been deleted!');
    }

}
