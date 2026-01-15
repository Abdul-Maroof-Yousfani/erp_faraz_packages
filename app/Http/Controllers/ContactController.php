<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;


class ContactController extends Controller
{
    public function createContact()
    {
        return view('selling.prospect.createContact');
    }

    public function contactStore(Request $request)
    {
        // dd($request->all());
        DB::Connection('mysql2')->beginTransaction();
        try {

        $conatct = new Contact;
        $conatct->first_name  = $request->first_name;
        $conatct->last_name  = $request->last_name;
        $conatct->gender  = $request->gender;
        $conatct->personal_title  = $request->title;
        $conatct->cell  = $request->cell;
        $conatct->phone  = $request->phone;
        $conatct->email  = $request->email;
        $conatct->website    = $request->website;
        $conatct->job_title    = $request->job_title;
        $conatct->save();
        DB::Connection('mysql2')->commit();
    }
    catch (Exception $e) {
        DB::Connection('mysql2')->rollBack();
        return response()->json(['error' => $e->getMessage()]);
    }
    return response()->json(['success' => 'successfully Added .']);
    }

    public function getContact(Request $request)
    {
        $data = [];
        $contacts = Contact::where('status', 1)->get();
    
        foreach ($contacts as $contact)
        {
            $data[] = [
                'id' => $contact->id,
                'first_name' => $contact->first_name .' '.$contact->last_name,
            ];
        }
        return response()->json($data);
        return view('prospect.option',compact('contacts'));
    }
    public function contactList(Request $request)
    {
        if($request->ajax())
        {
            $contacts = Contact::where('status',1)->get();
            return view('selling.contact.contactListAjax',compact('contacts'));
        }

        return view('selling.contact.contactList');

    }

    public function editContact(Request $request)
    {
       $contact =  Contact::find($request->id);
       return view('selling.contact.editContact',compact('contact'));
    }

    public function contactUpdate(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {

        $conatct =  Contact::find($request->id);
        $conatct->first_name  = $request->first_name;
        $conatct->last_name  = $request->last_name;
        $conatct->gender  = $request->gender;
        $conatct->personal_title  = $request->title;
        $conatct->cell  = $request->cell;
        $conatct->phone  = $request->phone;
        $conatct->email  = $request->email;
        $conatct->website    = $request->website;
        $conatct->job_title    = $request->job_title;
        $conatct->save();
        DB::Connection('mysql2')->commit();
    }
    catch (Exception $e) {
        DB::Connection('mysql2')->rollBack();
        return redirect()->route('contactList')->with('error', $e->getMessage());
    }
    return redirect()->route('contactList')->with('success','successfully Added .');
    }
    public function contactDelete(Request $request)
    {
        $contact = Contact::find($request->id);
        $contact->status =0 ;
        $contact->save();
         return redirect()->route('contactList')->with('success','successfully Deleted .');
    }
}
