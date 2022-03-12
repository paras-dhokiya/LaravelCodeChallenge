<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\loantable;
use Carbon\Carbon;
class LoantableController extends Controller
{
    /**
        * @OA\Post(
        * path="/api/ApproveLoan",
        * operationId="ApproveLoan",
        * tags={"ApproveLoan"},
        * summary="Approved User loan",
        *       security={
        *           {"bearerAuth": {}}
        *       },
        * description="",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"amount","term"},
        *               @OA\Property(property="amount", type="text"),
        *               @OA\Property(property="term", type="text"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Loan is approved successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Loan is approved successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function ApproveLoan(Request $request)
      {
          $validated = $request->validate([
              'amount' => 'required',
              'term' => 'required',
              
          ]);

          //$data = $request->all();
          //dd($request->user()->id);
          $userid = $request->user()->id;
          $dataisexsist = loantable::where('user_id', '=', $userid)->first();
          if ($dataisexsist === null) {
            $nextdate = date("Y-m-d", strtotime("+1 week"));
            $data['user_id']=$request->user()->id;
            $data['amount']=$request->get('amount');
            $data['term']=$request->get('term');
            $data['next_payment_date']=$nextdate;
            
            $user = loantable::create($data);
            $success['status'] = 1;
            $success['msg'] = 'Loan is approved successfully';
            $success['next_payment_date'] = $nextdate;
            return response()->json(['success' => $success]);
          }else{
            $success['status'] = 0;
            $success['msg'] = 'Your loan is already approved';
            return response()->json(['success' => $success]);
          }
          
      }
      /**
        * @OA\Post(
        * path="/api/PayDuePayment",
        * operationId="PayDuePayment",
        * tags={"PayDuePayment"},
        * summary="Pay loan amount",
        *       security={
        *           {"bearerAuth": {}}
        *       },
        * description="",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"amount"},
        *               @OA\Property(property="amount", type="text"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Payment is done",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Payment is done",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
      public function PayDuePayment(Request $request)
      {
          $validated = $request->validate([
              'amount' => 'required',
               
          ]);
          $userid = $request->user()->id;
          $dataisexsist = loantable::where('user_id', '=', $userid)->first();
          if ($dataisexsist === null) {
            $success['status'] = 0;
            $success['msg'] = "You can't pay rignt now because your loan is not approved yet.";
            return response()->json(['success' => $success]);
          }
          if($dataisexsist->amount != $request->get('amount'))
          {
            $success['status'] = 0;
            $success['msg'] = "Invalid Loan amount";
            return response()->json(['success' => $success]);
          }
          //dd($dataisexsist);
          $start_date = $dataisexsist->next_payment_date;  
          $date = strtotime($start_date);
          $date = strtotime("+1 week", $date);
          $nextdate = date("Y-m-d", $date);
          $data['status']=1;
          
          $updatestatus = loantable::where('user_id', $userid)->update($data);
          $success['status'] = 1;
          $success['msg'] = 'Payment is done';
          $success['next_payment_date'] = $nextdate;
          return response()->json(['success' => $success]);
      }
      /**
        * @OA\Post(
        * path="/api/CheckPaymentStatus",
        * operationId="CheckPaymentStatus",
        * tags={"CheckPaymentStatus"},
        * summary="Check Payment Status is loan amount is paid or not",
        *       security={
        *           {"bearerAuth": {}}
        *       },
        * description="",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *     
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Your Loan amount is already paid",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Your Loan amount is already paid",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
      public function CheckPaymentStatus(Request $request)
      {
        $userid = $request->user()->id;
        $dataisexsist = loantable::where('user_id', '=', $userid)->first();
        if ($dataisexsist === null) {
            $success['status'] = 0;
            $success['msg'] = "You can't check payment status because your loan is not approved yet.";
            return response()->json(['success' => $success]);
        }
        if($dataisexsist->status == 1)
        {
          $success['status'] = 1;
          $success['msg'] = 'Your Loan amount is already paid';
          $success['next_payment_date'] = $dataisexsist->next_payment_date;
          return response()->json(['success' => $success]);
        }else{
          $success['status'] = 0;
          $success['msg'] = 'Your loan payment is pending please pay before '.$dataisexsist->next_payment_date;
          return response()->json(['success' => $success]);
        }
          
      }
}
