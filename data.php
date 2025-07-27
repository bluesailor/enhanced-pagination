<?php
/**
 * JSON数组模拟数据库
 * 生成200条示例用户数据
 */

function generateMockData() {
    // 中文姓名数据
    $chineseNames = [
        '张伟', '王芳', '李娜', '刘洋', '陈静', '杨帆', '赵敏', '黄强', '周丽', '吴刚',
        '徐磊', '孙艳', '马超', '朱雯', '胡斌', '郭敏', '林峰', '何丽', '高翔', '梁静',
        '谢涛', '唐雅', '韩磊', '冯敏', '曹翔', '彭丽', '蒋斌', '魏敏', '董强', '薛雯',
        '范磊', '邓丽', '石峰', '姚敏', '谭翔', '廖雯', '金斌', '汪敏', '崔强', '田丽',
        '蔡峰', '卢敏', '袁翔', '贾雯', '聂斌', '龚敏', '程强', '傅丽', '丁峰', '余敏'
    ];
    
    // 英文姓名数据
    $englishNames = [
        'John Smith', 'Emma Johnson', 'Michael Brown', 'Olivia Davis', 'William Miller',
        'Sophia Wilson', 'James Moore', 'Isabella Taylor', 'Benjamin Anderson', 'Charlotte Thomas',
        'Lucas Jackson', 'Amelia White', 'Henry Harris', 'Mia Martin', 'Alexander Thompson',
        'Harper Garcia', 'Sebastian Martinez', 'Evelyn Robinson', 'Jack Clark', 'Abigail Rodriguez',
        'Owen Lewis', 'Emily Lee', 'Theodore Walker', 'Elizabeth Hall', 'Connor Allen',
        'Sofia Young', 'Caleb Hernandez', 'Avery King', 'Ryan Wright', 'Ella Lopez',
        'Nathan Hill', 'Scarlett Scott', 'Isaac Green', 'Grace Adams', 'Gabriel Baker',
        'Chloe Gonzalez', 'Anthony Nelson', 'Victoria Carter', 'Joshua Mitchell', 'Aria Perez',
        'Andrew Roberts', 'Luna Turner', 'Samuel Phillips', 'Nora Campbell', 'Christopher Parker',
        'Layla Evans', 'Matthew Edwards', 'Penelope Collins', 'David Stewart', 'Riley Sanchez'
    ];
    
    // 合并姓名数组
    $names = array_merge($chineseNames, $englishNames);
    
    // 邮箱域名
    $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', '163.com', 'qq.com', 'sina.com', 'outlook.com'];
    
    // 状态选项
    $statuses = ['active', 'inactive'];
    
    // 分类选项
    $categories = ['admin', 'user', 'vip'];
    
    // 部门选项
    $departments = ['技术部', '市场部', '销售部', '财务部', '人事部', '客服部', '运营部'];
    
    // 城市选项
    $cities = ['北京', '上海', '广州', '深圳', '杭州', '南京', '成都', '武汉', '西安', '重庆'];
    
    $users = [];
    $usedEmails = [];
    
    for ($i = 1; $i <= 200; $i++) {
        $name = $names[array_rand($names)];
        
        // 生成唯一邮箱
        $emailPrefix = strtolower(str_replace(' ', '.', $name));
        $emailPrefix = preg_replace('/[^\w\.]/', '', $emailPrefix); // 移除特殊字符
        $domain = $domains[array_rand($domains)];
        $email = $emailPrefix . $i . '@' . $domain;
        
        // 确保邮箱唯一
        $counter = 1;
        $originalEmail = $email;
        while (in_array($email, $usedEmails)) {
            $email = str_replace('@', $counter . '@', $originalEmail);
            $counter++;
        }
        $usedEmails[] = $email;
        
        $status = $statuses[array_rand($statuses)];
        $category = $categories[array_rand($categories)];
        $department = $departments[array_rand($departments)];
        $city = $cities[array_rand($cities)];
        
        // 生成随机创建时间（过去一年内）
        $timestamp = time() - mt_rand(0, 365 * 24 * 3600);
        $createdAt = date('Y-m-d H:i:s', $timestamp);
        
        // 生成随机年龄
        $age = mt_rand(22, 65);
        
        // 生成随机薪资
        $salary = mt_rand(5000, 50000);
        
        $users[] = [
            'id' => $i,
            'name' => $name,
            'email' => $email,
            'status' => $status,
            'category' => $category,
            'department' => $department,
            'city' => $city,
            'age' => $age,
            'salary' => $salary,
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ];
    }
    
    return $users;
}

/**
 * 获取所有用户数据
 */
function getAllUsers() {
    static $users = null;
    
    if ($users === null) {
        $users = generateMockData();
    }
    
    return $users;
}

/**
 * 根据条件筛选用户数据
 */
function filterUsers($keyword = '', $status = '', $category = '', $department = '', $city = '') {
    $users = getAllUsers();
    $filtered = [];
    
    foreach ($users as $user) {
        $match = true;
        
        // 关键词搜索（姓名或邮箱）
        if (!empty($keyword)) {
            $keywordLower = strtolower($keyword);
            $nameMatch = stripos($user['name'], $keyword) !== false;
            $emailMatch = stripos($user['email'], $keyword) !== false;
            if (!$nameMatch && !$emailMatch) {
                $match = false;
            }
        }
        
        // 状态筛选
        if (!empty($status) && $user['status'] !== $status) {
            $match = false;
        }
        
        // 分类筛选
        if (!empty($category) && $user['category'] !== $category) {
            $match = false;
        }
        
        // 部门筛选
        if (!empty($department) && $user['department'] !== $department) {
            $match = false;
        }
        
        // 城市筛选
        if (!empty($city) && $user['city'] !== $city) {
            $match = false;
        }
        
        if ($match) {
            $filtered[] = $user;
        }
    }
    
    return $filtered;
}

/**
 * 获取分页数据
 */
function getPaginatedUsers($keyword = '', $status = '', $category = '', $department = '', $city = '', $page = 1, $pageSize = 20) {
    $filteredUsers = filterUsers($keyword, $status, $category, $department, $city);
    $total = count($filteredUsers);
    
    $offset = ($page - 1) * $pageSize;
    $users = array_slice($filteredUsers, $offset, $pageSize);
    
    return [
        'users' => $users,
        'total' => $total
    ];
}

/**
 * 获取统计信息
 */
function getStatistics() {
    $users = getAllUsers();
    $stats = [
        'total' => count($users),
        'by_status' => [],
        'by_category' => [],
        'by_department' => [],
        'by_city' => [],
        'age_groups' => [
            '20-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '51-60' => 0,
            '60+' => 0
        ],
        'salary_ranges' => [
            '5000-10000' => 0,
            '10001-20000' => 0,
            '20001-30000' => 0,
            '30001-40000' => 0,
            '40000+' => 0
        ]
    ];
    
    foreach ($users as $user) {
        // 状态统计
        $status = $user['status'];
        $stats['by_status'][$status] = ($stats['by_status'][$status] ?? 0) + 1;
        
        // 分类统计
        $category = $user['category'];
        $stats['by_category'][$category] = ($stats['by_category'][$category] ?? 0) + 1;
        
        // 部门统计
        $department = $user['department'];
        $stats['by_department'][$department] = ($stats['by_department'][$department] ?? 0) + 1;
        
        // 城市统计
        $city = $user['city'];
        $stats['by_city'][$city] = ($stats['by_city'][$city] ?? 0) + 1;
        
        // 年龄组统计
        $age = $user['age'];
        if ($age <= 30) {
            $stats['age_groups']['20-30']++;
        } elseif ($age <= 40) {
            $stats['age_groups']['31-40']++;
        } elseif ($age <= 50) {
            $stats['age_groups']['41-50']++;
        } elseif ($age <= 60) {
            $stats['age_groups']['51-60']++;
        } else {
            $stats['age_groups']['60+']++;
        }
        
        // 薪资范围统计
        $salary = $user['salary'];
        if ($salary <= 10000) {
            $stats['salary_ranges']['5000-10000']++;
        } elseif ($salary <= 20000) {
            $stats['salary_ranges']['10001-20000']++;
        } elseif ($salary <= 30000) {
            $stats['salary_ranges']['20001-30000']++;
        } elseif ($salary <= 40000) {
            $stats['salary_ranges']['30001-40000']++;
        } else {
            $stats['salary_ranges']['40000+']++;
        }
    }
    
    return $stats;
}

/**
 * 获取筛选选项数据
 */
function getFilterOptions() {
    $users = getAllUsers();
    $options = [
        'departments' => [],
        'cities' => [],
        'categories' => ['admin' => '管理员', 'user' => '普通用户', 'vip' => 'VIP用户'],
        'statuses' => ['active' => '活跃', 'inactive' => '非活跃']
    ];
    
    foreach ($users as $user) {
        if (!in_array($user['department'], $options['departments'])) {
            $options['departments'][] = $user['department'];
        }
        if (!in_array($user['city'], $options['cities'])) {
            $options['cities'][] = $user['city'];
        }
    }
    
    sort($options['departments']);
    sort($options['cities']);
    
    return $options;
}

/**
 * 如果直接访问此文件，显示数据统计
 */
if (basename($_SERVER['PHP_SELF']) === 'data.php') {
    header('Content-Type: application/json; charset=utf-8');
    
    $action = $_GET['action'] ?? 'stats';
    
    switch ($action) {
        case 'stats':
            echo json_encode(getStatistics(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
            
        case 'options':
            echo json_encode(getFilterOptions(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
            
        case 'users':
            $keyword = $_GET['keyword'] ?? '';
            $status = $_GET['status'] ?? '';
            $category = $_GET['category'] ?? '';
            $department = $_GET['department'] ?? '';
            $city = $_GET['city'] ?? '';
            $page = intval($_GET['page'] ?? 1);
            $pageSize = intval($_GET['pageSize'] ?? 20);
            
            $result = getPaginatedUsers($keyword, $status, $category, $department, $city, $page, $pageSize);
            echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;
            
        default:
            echo json_encode(['error' => 'Invalid action'], JSON_UNESCAPED_UNICODE);
    }
}
?>