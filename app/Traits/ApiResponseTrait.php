<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    private bool $_success = true;
    private bool $_failed = false;

    /**
     * @param $msg
     * @param $statusCode
     * @return JsonResponse
     */
    public function returnError($msg, $statusCode): JsonResponse
    {
        return response()->json([
            "status" => $this->_failed,
            "msg" => $msg
        ], $statusCode);
    }

    /**
     * @param $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public function returnSuccessMessage($msg, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            "status" => $this->_success,
            "msg" => $msg
        ], $statusCode);
    }

    /**
     * @param $key
     * @param $data
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public function returnData($key, $data, string $msg = "", int $statusCode = 200): JsonResponse
    {
        return response()->json([
            "status" => $this->_success,
            "msg" => $msg,
            $key => $data,
        ], $statusCode);
    }

        /**
     * @param $key
     * @param $data
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public function returnDataWithError($key, $data, string $msg = "", int $statusCode = 200): JsonResponse
    {
        return response()->json([
            "status" => $this->_failed,
            "msg" => $msg,
            $key => $data,
        ], $statusCode);
    }


    public function returnValidationError($validator): JsonResponse
    {
        return $this->returnError($validator->errors()->first(), 200);
    }

    public function returnValidationMsg($msg, $statusCode): JsonResponse
    {
        return response()->json([
            "status" => $this->_success,
            "msg" => $msg
        ], $statusCode);
    }


    public function getSuccessMessage(): string
    {
        return request()->header('accept-language') == 'ar' ? 'تم بنجاح' : 'success';
    }

    public function getFailedMessage(): string
    {
        return request()->header('accept-language') == 'ar' ? 'حدث خطأ برجاء المحاولة مرة اخرى' : 'try again please';
    }
}
