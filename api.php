<?php
/**
 * API 接口演示
 * 返回JSON格式的分页数据
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'EnhancedPagination.php';
require_once 'data.php';

try {
    // 获取请求参数
    $keyword = $_GET['keyword'] ?? '';
    $status = $_GET['status'] ?? '';
    $category = $_GET['category'] ?? '';
    $department = $_GET['department'] ?? '';
    $city = $_GET['city'] ?? '';
    $current = intval($_GET['page'] ?? 1);
    $pageSize = intval($_GET['pageSize'] ?? 10);
    $framework = $_GET['framework'] ?? 'bootstrap';
    $language = $_GET['language'] ?? 'zh-cn';
    
    // 参数验证
    $pageSize = max(1, min(100, $pageSize)); // 限制每页显示数量
    $current = max(1, $current);
    
    // 获取分页数据
    $result = getPaginatedUsers($keyword, $status, $category, $department, $city, $current, $pageSize);
    $users = $result['users'];
    $total = $result['total'];
    
    // 创建分页对象
    $pagination = new EnhancedPagination($total, $current, $pageSize, $_GET, [
        'framework' => $framework,
        'language' => $language,
        'showJumper' => true,
        'mobileOptimized' => true,
        'showStats' => true
    ]);
    
    // 数据处理
    foreach ($users as &$user) {
        $user['status_text'] = $user['status'] === 'active' ? '活跃' : '非活跃';
        $user['category_text'] = match($user['category']) {
            'admin' => '管理员',
            'user' => '普通用户',
            'vip' => 'VIP用户',
            default => $user['category']
        };
    }
    
    // 构建响应数据
    $response = [
        'code' => 200,
        'message' => 'success',
        'data' => [
            'list' => $users,
            'pagination' => $pagination->toArray(),
            'filters' => [
                'keyword' => $keyword,
                'status' => $status,
                'category' => $category,
                'department' => $department,
                'city' => $city
            ],
            'meta' => [
                'framework' => $framework,
                'language' => $language,
                'request_time' => date('Y-m-d H:i:s'),
                'execution_time' => number_format((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2) . 'ms'
            ]
        ]
    ];
    
    // 添加统计信息
    if (empty($keyword) && empty($status) && empty($category) && empty($department) && empty($city)) {
        // 只在无筛选条件时提供统计信息，避免重复查询
        $response['data']['statistics'] = getStatistics();
    }
    
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    // 错误处理
    $errorResponse = [
        'code' => 500,
        'message' => 'Internal Server Error',
        'error' => $e->getMessage(),
        'data' => null
    ];
    
    http_response_code(500);
    echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>