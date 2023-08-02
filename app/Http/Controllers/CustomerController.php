<?php

namespace App\Http\Controllers;

use App\Mail\CustomerMail;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    function CustomerPage():View{
        return view('pages.dashboard.customer-page');
    }

    function MessagePage():View{
        return view('pages.dashboard.customer-message-page');
    }

    function CustomerList(Request $request){
        $user_id=$request->header('id');
        return Customer::where('user_id',$user_id)->get();
    }

    function CustomerCreate(Request $request){
        $user_id=$request->header('id');
        return Customer::create([
            'cus_name'=>$request->input('cus_name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
            'address'=>$request->input('address'),
            'user_id'=>$user_id
        ]);
    }

    function CustomerByID(Request $request){
        $cus_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$cus_id)->where('user_id',$user_id)->first();
    }

    function CustomerUpdate(Request $request){
        $cus_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$cus_id)->where('user_id',$user_id)->update([
            'cus_name'=>$request->input('cus_name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
            'address'=>$request->input('address')
        ]);
    }

    function CustomerDelete(Request $request){
        $cus_id=$request->input('id');
        $user_id=$request->header('id');
        return Customer::where('id',$cus_id)->where('user_id',$user_id)->delete();
    }

    public function SendMessage(Request $request)
    {
        $email = $request->input('email');
        $messageText = $request->input('messageText');
        $customer = Customer::where('email', '=', $email)->count();

        if ($customer == 1) {
            Mail::to($email)->send(new CustomerMail($messageText));
            Customer::where('email', '=', $email)->update(['status' => 'done']);
            return response()->json([
                "message" => "Email Send Successful.",
                "status" => "success",
            ], 200);
        } else {
            return response()->json([
                "message" => "unauthorized",
                "status" => "failed",
            ], 401);
        }

    }
}
