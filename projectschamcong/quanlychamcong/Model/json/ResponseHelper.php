<?php
class ResponseHelper {
    
    /**
     * Trả về JSON response và dừng script
     */
    public static function json($data, $statusCode = 200) {
        // ✅ Xóa TOÀN BỘ output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // ✅ Set HTTP status code
        http_response_code($statusCode);
        
        // ✅ Set header JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // ✅ Tắt error display để tránh HTML lẫn vào JSON
        ini_set('display_errors', 0);
        
        // ✅ Output JSON
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    /**
     * Trả về JSON thành công
     */
    public static function success($data = [], $message = null) {
        $response = ['success' => true];
        
        if ($message) {
            $response['message'] = $message;
        }
        
        if (!empty($data)) {
            $response['data'] = $data;
        }
        
        self::json($response, 200);
    }
    
    /**
     * Trả về JSON lỗi
     */
    public static function error($message, $errorType = null, $statusCode = 400) {
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if ($errorType) {
            $response['error_type'] = $errorType;
        }
        
        self::json($response, $statusCode);
    }
}
?>