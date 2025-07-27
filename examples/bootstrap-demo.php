<?php
/**
 * Bootstrap 5.3 框架演示
 * 展示分页类在Bootstrap环境下的各种用法
 */

require_once '../EnhancedPagination.php';
require_once '../data.php';

// 演示数据
$total = 500;
$current = $_GET['page'] ?? 1;
$pageSize = $_GET['size'] ?? 15;

// 获取实际数据用于演示
$result = getPaginatedUsers('', '', '', '', '', $current, $pageSize);
$users = $result['users'];
$actualTotal = $result['total'];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap 5.3 分页演示 - EnhancedPagination</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .demo-section {
            margin-bottom: 3rem;
            padding: 2rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            background: #fff;
        }
        
        .demo-title {
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .code-preview {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0 0.25rem 0.25rem 0;
        }
        
        .pagination-result {
            border: 1px solid #e9ecef;
            border-radius: 0.25rem;
            padding: 1.5rem;
            background: #fdfdfe;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    
    <!-- 页面标题 -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center bg-primary text-white p-4 rounded">
                <h1 class="display-4 mb-3">
                    <i class="bi bi-bootstrap"></i> Bootstrap 5.3 分页演示
                </h1>
                <p class="lead mb-0">展示 EnhancedPagination 在 Bootstrap 框架下的各种用法和样式</p>
            </div>
        </div>
    </div>

    <!-- 返回主页 -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="../index.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> 返回主页
            </a>
        </div>
    </div>

    <!-- 1. 基础分页演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            <i class="bi bi-1-circle-fill"></i> 基础分页演示
        </h2>
        
        <div class="row">
            <div class="col-md-6">
                <div class="code-preview">
                    <h6>代码示例：</h6>
                    <pre><code>$pagination = new EnhancedPagination(
    500, 1, 20, [], [
    'framework' => 'bootstrap',
    'language' => 'zh-cn'
]);</code></pre>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pagination-result">
                    <h6>效果展示：</h6>
                    <?php
                    $basic = new EnhancedPagination(500, 1, 20, [], [
                        'framework' => 'bootstrap',
                        'language' => 'zh-cn'
                    ]);
                    echo $basic->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. 不同尺寸演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            <i class="bi bi-2-circle-fill"></i> 不同尺寸演示
        </h2>
        
        <div class="row">
            <div class="col-lg-4 mb-3">
                <h5>小尺寸 (sm)</h5>
                <div class="pagination-result">
                    <?php
                    $small = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'size' => 'sm',
                        'showStats' => false
                    ]);
                    echo $small->render();
                    ?>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <h5>默认尺寸</h5>
                <div class="pagination-result">
                    <?php
                    $normal = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'showStats' => false
                    ]);
                    echo $normal->render();
                    ?>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <h5>大尺寸 (lg)</h5>
                <div class="pagination-result">
                    <?php
                    $large = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'size' => 'lg',
                        'showStats' => false
                    ]);
                    echo $large->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. 对齐方式演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            <i class="bi bi-3-circle-fill"></i> 对齐方式演示
        </h2>
        
        <div class="row">
            <div class="col-12 mb-3">
                <h5>左对齐 (start)</h5>
                <div class="pagination-result">
                    <?php
                    $start = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'alignment' => 'start',
                        'showStats' => false
                    ]);
                    echo $start->render();
                    ?>
                </div>
            </div>
            <div class="col-12 mb-3">
                <h5>居中对齐 (center)</h5>
                <div class="pagination-result">
                    <?php
                    $center = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'alignment' => 'center',
                        'showStats' => false
                    ]);
                    echo $center->render();
                    ?>
                </div>
            </div>
            <div class="col-12 mb-3">
                <h5>右对齐 (end)</h5>
                <div class="pagination-result">
                    <?php
                    $end = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'alignment' => 'end',
                        'showStats' => false
                    ]);
                    echo $end->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. 多语言演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            <i class="bi bi-4-circle-fill"></i> 多语言演示
        </h2>
        
        <div class="row">
            <div class="col-lg-4 mb-3">
                <h5>中文 (zh-cn)</h5>
                <div class="pagination-result">
                    <?php
                    $zhcn = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'language' => 'zh-cn'
                    ]);
                    echo $zhcn->render();
                    ?>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <h5>英文 (en)</h5>
                <div class="pagination-result">
                    <?php
                    $en = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'language' => 'en'
                    ]);
                    echo $en->render();
                    ?>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <h5>日文 (ja)</h5>
                <div class="pagination-result">
                    <?php
                    $ja = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'language' => 'ja'
                    ]);
                    echo $ja->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. 功能组合演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            <i class="bi bi-5-circle-fill"></i> 功能组合演示
        </h2>
        
        <div class="row">
            <div class="col-12 mb-4">
                <h5>完整功能（大尺寸 + 英文 + 跳转 + 统计）</h5>
                <div class="pagination-result">
                    <?php
                    $full = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'language' => 'en',
                        'size' => 'lg',
                        'alignment' => 'center',
                        'showJumper' => true,
                        'showStats' => true,
                        'mobileOptimized' => true
                    ]);
                    echo $full->render();
                    ?>
                </div>
            </div>
            
            <div class="col-12 mb-4">
                <h5>精简版（小尺寸 + 无跳转 + 无统计）</h5>
                <div class="pagination-result">
                    <?php
                    $simple = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'size' => 'sm',
                        'alignment' => 'start',
                        'showJumper' => false,
                        'showStats' => false,
                        'range' => 2
                    ]);
                    echo $simple->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. 实际数据演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            <i class="bi bi-6-circle-fill"></i> 实际数据演示
        </h2>
        
        <div class="row">
            <div class="col-12">
                <p class="text-muted">以下是使用真实数据的分页演示，包含数据表格：</p>
                
                <!-- 数据表格 -->
                <div class="table-responsive mb-4">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>姓名</th>
                                <th>邮箱</th>
                                <th>状态</th>
                                <th>部门</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($users, 0, 5) as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="badge <?= $user['status'] === 'active' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $user['status'] === 'active' ? '活跃' : '非活跃' ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($user['department']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- 分页组件 -->
                <div class="pagination-result">
                    <?php
                    $realData = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'bootstrap',
                        'language' => 'zh-cn',
                        'showJumper' => true,
                        'showStats' => true
                    ]);
                    echo $realData->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. API数据演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            <i class="bi bi-7-circle-fill"></i> API数据演示
        </h2>
        
        <div class="row">
            <div class="col-12">
                <p class="text-muted">分页组件返回的JSON数据结构：</p>
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-code-square"></i> JSON Response
                        </h6>
                    </div>
                    <div class="card-body">
                        <pre class="mb-0"><code><?= json_encode($realData->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 页脚 -->
    <footer class="text-center mt-5 py-4 border-top">
        <p class="text-muted mb-2">
            <i class="bi bi-github"></i> 
            <a href="https://github.com/bluesailor/enhanced-pagination" target="_blank" class="text-decoration-none">
                Enhanced Pagination
            </a> - Bootstrap 5.3 演示
        </p>
        <p class="text-muted small mb-0">
            切换到其他框架：
            <a href="tailwind-demo.php" class="text-decoration-none">Tailwind CSS</a> |
            <a href="custom-demo.php" class="text-decoration-none">自定义CSS</a>
        </p>
    </footer>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>