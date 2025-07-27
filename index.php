<?php
/**
 * EnhancedPagination 演示页面
 * 使用JSON数组模拟数据库展示分页效果
 */

require_once 'EnhancedPagination.php';
require_once 'data.php';

// 处理搜索参数
$keyword = $_GET['keyword'] ?? '';
$status = $_GET['status'] ?? '';
$category = $_GET['category'] ?? '';
$department = $_GET['department'] ?? '';
$city = $_GET['city'] ?? '';
$current = intval($_GET['page'] ?? 1);

// 获取当前框架和语言设置
$framework = $_GET['framework'] ?? 'bootstrap';
$language = $_GET['language'] ?? 'zh-cn';
$pageSize = intval($_GET['pageSize'] ?? 10);

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

// 获取筛选选项
$filterOptions = getFilterOptions();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnhancedPagination 演示</title>
    
    <!-- Bootstrap CSS -->
    <?php if ($framework === 'bootstrap'): ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <?php endif; ?>
    
    <!-- Tailwind CSS -->
    <?php if ($framework === 'tailwind'): ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php endif; ?>
    
    <style>
        body {
            <?php if ($framework !== 'bootstrap'): ?>
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            <?php endif; ?>
        }
        
        .demo-container {
            <?php if ($framework === 'bootstrap'): ?>
            /* Bootstrap样式由框架处理 */
            <?php elseif ($framework === 'tailwind'): ?>
            /* Tailwind样式 */
            <?php else: ?>
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            <?php endif; ?>
        }
        
        .demo-header {
            <?php if ($framework === 'custom'): ?>
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            <?php endif; ?>
        }
        
        .search-form {
            <?php if ($framework === 'custom'): ?>
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            <?php endif; ?>
        }
        
        .user-table {
            <?php if ($framework === 'custom'): ?>
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            <?php endif; ?>
        }
        
        .user-table th,
        .user-table td {
            <?php if ($framework === 'custom'): ?>
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            <?php endif; ?>
        }
        
        .user-table th {
            <?php if ($framework === 'custom'): ?>
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            <?php endif; ?>
        }
        
        .status-badge {
            <?php if ($framework === 'custom'): ?>
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            <?php endif; ?>
        }
        
        .status-active {
            <?php if ($framework === 'custom'): ?>
            background: #d4edda;
            color: #155724;
            <?php endif; ?>
        }
        
        .status-inactive {
            <?php if ($framework === 'custom'): ?>
            background: #f8d7da;
            color: #721c24;
            <?php endif; ?>
        }
        
        .framework-selector {
            <?php if ($framework === 'custom'): ?>
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            <?php endif; ?>
        }
        
        .api-demo {
            <?php if ($framework === 'custom'): ?>
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            <?php endif; ?>
        }
    </style>
</head>
<body>

<?php
// 获取当前框架的容器类
$containerClass = match($framework) {
    'bootstrap' => 'container-fluid',
    'tailwind' => 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
    default => 'demo-container'
};

$cardClass = match($framework) {
    'bootstrap' => 'card',
    'tailwind' => 'bg-white shadow-lg rounded-lg',
    default => ''
};

$formClass = match($framework) {
    'bootstrap' => 'card-body',
    'tailwind' => 'p-6',
    default => 'search-form'
};
?>

<div class="<?= $containerClass ?>">
    
    <!-- 框架选择器 -->
    <div class="<?= $framework === 'bootstrap' ? 'position-fixed top-0 end-0 m-3' : ($framework === 'tailwind' ? 'fixed top-5 right-5 bg-white p-4 rounded-lg shadow-lg z-50' : 'framework-selector') ?>">
        <form method="get" class="<?= $framework === 'bootstrap' ? 'd-flex flex-column gap-2' : ($framework === 'tailwind' ? 'space-y-2' : '') ?>">
            <?php foreach ($_GET as $key => $value): ?>
                <?php if (!in_array($key, ['framework', 'language'])): ?>
                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            
            <select name="framework" onchange="this.form.submit()" class="<?= $framework === 'bootstrap' ? 'form-select form-select-sm' : ($framework === 'tailwind' ? 'block w-full rounded-md border-gray-300 shadow-sm' : '') ?>">
                <option value="bootstrap" <?= $framework === 'bootstrap' ? 'selected' : '' ?>>Bootstrap 5.3</option>
                <option value="tailwind" <?= $framework === 'tailwind' ? 'selected' : '' ?>>Tailwind CSS</option>
                <option value="custom" <?= $framework === 'custom' ? 'selected' : '' ?>>自定义CSS</option>
            </select>
            
            <select name="language" onchange="this.form.submit()" class="<?= $framework === 'bootstrap' ? 'form-select form-select-sm' : ($framework === 'tailwind' ? 'block w-full rounded-md border-gray-300 shadow-sm' : '') ?>">
                <option value="zh-cn" <?= $language === 'zh-cn' ? 'selected' : '' ?>>中文</option>
                <option value="en" <?= $language === 'en' ? 'selected' : '' ?>>English</option>
                <option value="ja" <?= $language === 'ja' ? 'selected' : '' ?>>日本語</option>
            </select>
        </form>
    </div>

    <!-- 页面标题 -->
    <div class="<?= $framework === 'bootstrap' ? 'text-center py-5 mb-4 bg-primary text-white' : ($framework === 'tailwind' ? 'text-center py-12 mb-8 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg' : 'demo-header') ?>">
        <h1 class="<?= $framework === 'bootstrap' ? 'display-4' : ($framework === 'tailwind' ? 'text-4xl font-bold' : '') ?>">EnhancedPagination 演示</h1>
        <p class="<?= $framework === 'bootstrap' ? 'lead' : ($framework === 'tailwind' ? 'text-xl mt-4 opacity-90' : '') ?>">功能强大的PHP分页类 - 支持多框架、国际化、移动端优化</p>
        <p class="<?= $framework === 'bootstrap' ? 'mb-0' : ($framework === 'tailwind' ? 'mt-2 opacity-75' : '') ?>">当前使用: <?= ucfirst($framework) ?> | 语言: <?= strtoupper($language) ?> | 总计 <?= $total ?> 条记录</p>
    </div>

    <!-- 搜索表单 -->
    <div class="<?= $cardClass ?> <?= $framework === 'bootstrap' ? 'mb-4' : ($framework === 'tailwind' ? 'mb-6' : '') ?>">
        <div class="<?= $formClass ?>">
            <form method="get" class="<?= $framework === 'bootstrap' ? 'row g-3' : ($framework === 'tailwind' ? 'grid grid-cols-1 md:grid-cols-7 gap-4' : '') ?>">
                <input type="hidden" name="framework" value="<?= htmlspecialchars($framework) ?>">
                <input type="hidden" name="language" value="<?= htmlspecialchars($language) ?>">
                
                <div class="<?= $framework === 'bootstrap' ? 'col-md-3' : '' ?>">
                    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" 
                           placeholder="搜索姓名或邮箱" 
                           class="<?= $framework === 'bootstrap' ? 'form-control' : ($framework === 'tailwind' ? 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500' : '') ?>">
                </div>
                
                <div class="<?= $framework === 'bootstrap' ? 'col-md-2' : '' ?>">
                    <select name="status" class="<?= $framework === 'bootstrap' ? 'form-select' : ($framework === 'tailwind' ? 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500' : '') ?>">
                        <option value="">所有状态</option>
                        <?php foreach ($filterOptions['statuses'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $status === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="<?= $framework === 'bootstrap' ? 'col-md-2' : '' ?>">
                    <select name="category" class="<?= $framework === 'bootstrap' ? 'form-select' : ($framework === 'tailwind' ? 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500' : '') ?>">
                        <option value="">所有分类</option>
                        <?php foreach ($filterOptions['categories'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $category === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="<?= $framework === 'bootstrap' ? 'col-md-2' : '' ?>">
                    <select name="department" class="<?= $framework === 'bootstrap' ? 'form-select' : ($framework === 'tailwind' ? 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500' : '') ?>">
                        <option value="">所有部门</option>
                        <?php foreach ($filterOptions['departments'] as $dept): ?>
                            <option value="<?= $dept ?>" <?= $department === $dept ? 'selected' : '' ?>><?= $dept ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="<?= $framework === 'bootstrap' ? 'col-md-2' : '' ?>">
                    <select name="city" class="<?= $framework === 'bootstrap' ? 'form-select' : ($framework === 'tailwind' ? 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500' : '') ?>">
                        <option value="">所有城市</option>
                        <?php foreach ($filterOptions['cities'] as $c): ?>
                            <option value="<?= $c ?>" <?= $city === $c ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="<?= $framework === 'bootstrap' ? 'col-md-2' : '' ?>">
                    <select name="pageSize" class="<?= $framework === 'bootstrap' ? 'form-select' : ($framework === 'tailwind' ? 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500' : '') ?>">
                        <option value="5" <?= $pageSize === 5 ? 'selected' : '' ?>>5条/页</option>
                        <option value="10" <?= $pageSize === 10 ? 'selected' : '' ?>>10条/页</option>
                        <option value="20" <?= $pageSize === 20 ? 'selected' : '' ?>>20条/页</option>
                        <option value="50" <?= $pageSize === 50 ? 'selected' : '' ?>>50条/页</option>
                    </select>
                </div>
                
                <div class="<?= $framework === 'bootstrap' ? 'col-md-3' : '' ?>">
                    <button type="submit" class="<?= $framework === 'bootstrap' ? 'btn btn-primary me-2' : ($framework === 'tailwind' ? 'bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md mr-2' : '') ?>">搜索</button>
                    <a href="?" class="<?= $framework === 'bootstrap' ? 'btn btn-outline-secondary' : ($framework === 'tailwind' ? 'bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md' : '') ?>">重置</a>
                </div>
            </form>
        </div>
    </div>

    <!-- 数据表格 -->
    <div class="<?= $cardClass ?>">
        <div class="<?= $framework === 'bootstrap' ? 'card-body' : ($framework === 'tailwind' ? 'p-6' : '') ?>">
            <?php if (empty($users)): ?>
                <div class="<?= $framework === 'bootstrap' ? 'text-center py-5 text-muted' : ($framework === 'tailwind' ? 'text-center py-12 text-gray-500' : 'text-center py-5') ?>">
                    <p>没有找到符合条件的数据</p>
                </div>
            <?php else: ?>
                <div class="<?= $framework === 'bootstrap' ? 'table-responsive' : ($framework === 'tailwind' ? 'overflow-x-auto' : '') ?>">
                    <table class="<?= $framework === 'bootstrap' ? 'table table-hover' : ($framework === 'tailwind' ? 'min-w-full divide-y divide-gray-200' : 'user-table') ?>">
                        <thead class="<?= $framework === 'tailwind' ? 'bg-gray-50' : '' ?>">
                            <tr>
                                <th class="<?= $framework === 'tailwind' ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' : '' ?>">ID</th>
                                <th class="<?= $framework === 'tailwind' ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' : '' ?>">姓名</th>
                                <th class="<?= $framework === 'tailwind' ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' : '' ?>">邮箱</th>
                                <th class="<?= $framework === 'tailwind' ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' : '' ?>">状态</th>
                                <th class="<?= $framework === 'tailwind' ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' : '' ?>">部门</th>
                                <th class="<?= $framework === 'tailwind' ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' : '' ?>">城市</th>
                                <th class="<?= $framework === 'tailwind' ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' : '' ?>">年龄</th>
                                <th class="<?= $framework === 'tailwind' ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' : '' ?>">注册时间</th>
                            </tr>
                        </thead>
                        <tbody class="<?= $framework === 'tailwind' ? 'bg-white divide-y divide-gray-200' : '' ?>">
                            <?php foreach ($users as $user): ?>
                                <tr class="<?= $framework === 'tailwind' ? 'hover:bg-gray-50' : '' ?>">
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap text-sm text-gray-900' : '' ?>"><?= $user['id'] ?></td>
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900' : '' ?>"><?= htmlspecialchars($user['name']) ?></td>
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap text-sm text-gray-500' : '' ?>"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap' : '' ?>">
                                        <?php 
                                        $statusClass = $user['status'] === 'active' ? 'status-active' : 'status-inactive';
                                        if ($framework === 'bootstrap') {
                                            $statusClass = $user['status'] === 'active' ? 'badge bg-success' : 'badge bg-danger';
                                        } elseif ($framework === 'tailwind') {
                                            $statusClass = $user['status'] === 'active' ? 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800' : 'inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800';
                                        }
                                        ?>
                                        <span class="<?= $statusClass ?>"><?= $user['status'] === 'active' ? '活跃' : '非活跃' ?></span>
                                    </td>
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap text-sm text-gray-500' : '' ?>"><?= htmlspecialchars($user['category']) ?></td>
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap text-sm text-gray-500' : '' ?>"><?= htmlspecialchars($user['department']) ?></td>
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap text-sm text-gray-500' : '' ?>"><?= htmlspecialchars($user['city']) ?></td>
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap text-sm text-gray-500' : '' ?>"><?= $user['age'] ?>岁</td>
                                    <td class="<?= $framework === 'tailwind' ? 'px-6 py-4 whitespace-nowrap text-sm text-gray-500' : '' ?>"><?= $user['created_at'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <!-- 分页组件 -->
            <div class="<?= $framework === 'bootstrap' ? 'mt-4' : ($framework === 'tailwind' ? 'mt-6' : '') ?>">
                <?= $pagination->render() ?>
            </div>
        </div>
    </div>

    <!-- API 演示 -->
    <div class="<?= $cardClass ?> <?= $framework === 'bootstrap' ? 'mt-4' : ($framework === 'tailwind' ? 'mt-6' : '') ?>">
        <div class="<?= $framework === 'bootstrap' ? 'card-header' : ($framework === 'tailwind' ? 'px-6 py-3 bg-gray-50 border-b border-gray-200' : '') ?>">
            <h5 class="<?= $framework === 'bootstrap' ? 'mb-0' : ($framework === 'tailwind' ? 'text-lg font-medium text-gray-900' : '') ?>">API 数据格式演示</h5>
        </div>
        <div class="<?= $framework === 'bootstrap' ? 'card-body' : ($framework === 'tailwind' ? 'p-6' : 'api-demo') ?>">
            <p class="<?= $framework === 'tailwind' ? 'text-sm text-gray-600 mb-4' : '' ?>">以下是分页组件返回的JSON格式数据，可用于API接口：</p>
            <pre class="<?= $framework === 'bootstrap' ? 'bg-light p-3 rounded' : ($framework === 'tailwind' ? 'bg-gray-100 p-4 rounded-lg text-sm overflow-x-auto' : '') ?>"><code><?= json_encode($pagination->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></code></pre>
            
            <div class="<?= $framework === 'bootstrap' ? 'mt-3' : ($framework === 'tailwind' ? 'mt-4' : '') ?>">
                <a href="api.php?<?= http_build_query($_GET) ?>" target="_blank" 
                   class="<?= $framework === 'bootstrap' ? 'btn btn-outline-info btn-sm' : ($framework === 'tailwind' ? 'inline-flex items-center px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200' : '') ?>">
                    查看完整API响应
                </a>
            </div>
        </div>
    </div>
    
    <!-- 项目信息 -->
    <footer class="<?= $framework === 'bootstrap' ? 'mt-5 py-4 text-center text-muted border-top' : ($framework === 'tailwind' ? 'mt-8 py-6 text-center text-gray-500 border-t border-gray-200' : '') ?>">
        <p>EnhancedPagination - 开源PHP分页类 | 使用JSON数组模拟数据库演示</p>
        <p>
            <a href="https://github.com/bluesailor/enhanced-pagination" target="_blank" 
               class="<?= $framework === 'bootstrap' ? 'text-decoration-none' : ($framework === 'tailwind' ? 'text-blue-600 hover:text-blue-800' : '') ?>">
                GitHub项目地址
            </a>
            |
            <a href="README.md" target="_blank" 
               class="<?= $framework === 'bootstrap' ? 'text-decoration-none' : ($framework === 'tailwind' ? 'text-blue-600 hover:text-blue-800' : '') ?>">
                使用文档
            </a>
        </p>
    </footer>

</div>

<?php if ($framework === 'bootstrap'): ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php endif; ?>

</body>
</html>