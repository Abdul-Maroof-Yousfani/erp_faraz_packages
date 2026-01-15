<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\JobTitle;
use Illuminate\Http\Request;
use App\Models\Prospect;
use Illuminate\Support\Facades\DB;
use Exception;

class ProspectController extends Controller
{
    public function prospectList(Request $request)
    {
        if($request->ajax())
        {
            $prospects =  Prospect::where('status',1)->where('prospect_type',1)->get();

            return view('selling.prospect.prospectListAjax',compact('prospects'));
        }
        return view('selling.prospect.prospectList');
    }
    public function createProspect ()
    {
        return view('selling.prospect.createProspect');
    }   

    public function prospectStore(Request $request)
    {
        
    DB::Connection('mysql2')->beginTransaction();
        try {

        $prospect = new  Prospect;
        $prospect->contact_id  = $request->contact_id;
        $prospect->company_name  = $request->company_name;
        $prospect->company_address  = $request->company_Address ?? '';
        $prospect->company_location_id  = $request->company_location;
        $prospect->customer_group_id  = $request->company_group;
        $prospect->save();
        DB::Connection('mysql2')->commit();
    }
    catch (Exception $e) {
        DB::Connection('mysql2')->rollBack();
        return response()->json(['error' => $e->getMessage()]);
    }
    return response()->json(['success' => 'successfully Added .']);


    }

    public function viewProspect(Request $request) {
        $prospect = Prospect::with('contact', 'companyLocation', 'companyGroup')->where([['id','=', $request->id],['status','=', 1]])->first();
        return view('selling.prospect.viewProspect', compact('prospect'));
    }
    public function createJobtitle(Request $request)
    {
        return view('selling.prospect.createJobtitle');
    }

    public function JobtitleStore(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
                    $job = new JobTitle();
                    $job->name = $request->name;
                    $job->save();
       DB::Connection('mysql2')->commit();
    }
    catch (Exception $e) {
        DB::Connection('mysql2')->rollBack();
        return response()->json(['error' => $e->getMessage()]);
    }
    return response()->json(['success' => 'successfully Added .']);
    }
    
    public function getJobtitle(Request $request)
    {
      $data = JobTitle::where('status',1)->get();
      return $data;
    }
    
    public function getContactByprospect(Request $request)
    {
      $prospect =   Prospect::where('id',$request->prospect_id)->where('status',1)->first();
      $contact =   Contact::find($prospect->contact_id);
      return $contact;
    }
    
    public function deleteProspect($id)
    {
        $ProspectData = Prospect::where('id',$id)->first();
        
        if($ProspectData)
        {
            Prospect::where('id',$id)->update([
                'status' => 0
            ]);
        }
        else
        {

        }

    }
    
    public function editProspect($id)
    {
        $ProspectData = Prospect::where('id',$id)->first();
        
        if($ProspectData)
        {
            return view('selling.prospect.editProspect', compact('ProspectData'));         
        }
        else
        {

        }

    }
    
    public function editProspectDetail(Request $request,$id)
    {
        DB::Connection('mysql2')->beginTransaction();

        try {
            $prospect = Prospect::find($id); // Assuming 'id' is the primary key

            if (!$prospect) {
                throw new Exception('Prospect not found');
            }

            $prospect->contact_id = $request->contact_id;
            $prospect->company_name = $request->company_name;
            $prospect->company_address = $request->company_Address;
            $prospect->company_location_id = $request->company_location;
            $prospect->customer_group_id = $request->company_group;

            $prospect->save();

            DB::Connection('mysql2')->commit();
            return redirect('/prospect/prospectList')->with('success', 'Successfully updated.');
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollBack();
            return redirect('/prospect/prospectList')->with('error', $e->getMessage());
        }

    }


}
