<?php
/**
 * 自定义CSS演示
 * 展示分页类在自定义CSS环境下的各种用法和主题
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
    <title>自定义CSS分页演示 - EnhancedPagination</title>
    
    <style>
        /* 基础样式重置 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* 页面标题样式 */
        .page-header {
            text-align: center;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 20px;
            border-radius: 15px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        
        .page-header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        /* 导航按钮 */
        .nav-btn {
            display: inline-block;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin-bottom: 30px;
            backdrop-filter: blur(5px);
        }
        
        .nav-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        /* 演示区块样式 */
        .demo-section {
            background: rgba(255, 255, 255, 0.95);
            margin-bottom: 40px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .demo-title {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 30px;
            font-size: 1.5rem;
            position: relative;
        }
        
        .demo-title::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(45deg, #667eea, #764ba2);
        }
        
        /* 代码预览样式 */
        .code-preview {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
            position: relative;
        }
        
        .code-preview::before {
            content: '</>';
            position: absolute;
            top: 10px;
            right: 15px;
            color: #667eea;
            font-weight: bold;
        }
        
        .code-preview h6 {
            color: #667eea;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .code-preview pre {
            background: #2d3748;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        
        /* 分页结果展示样式 */
        .pagination-result {
            border: 2px dashed #e0e6ed;
            border-radius: 10px;
            padding: 20px;
            background: #f8f9fb;
            position: relative;
        }
        
        .pagination-result::before {
            content: '🎯 效果展示';
            position: absolute;
            top: -10px;
            left: 15px;
            background: #f8f9fb;
            padding: 0 10px;
            color: #667eea;
            font-size: 12px;
            font-weight: 600;
        }
        
        /* 网格布局 */
        .grid {
            display: grid;
            gap: 20px;
        }
        
        .grid-2 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
        
        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        
        /* 主题变体样式 */
        .theme-dark {
            background: #2d3748;
            color: white;
        }
        
        .theme-dark .pagination-result {
            background: #4a5568;
            border-color: #667eea;
        }
        
        .theme-colorful {
            background: linear-gradient(45deg, #ff6b6b, #feca57, #48dbfb, #ff9ff3);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* 表格样式 */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .custom-table th,
        .custom-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .custom-table th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        
        .custom-table tr:hover {
            background: #f8f9fa;
        }
        
        /* 状态徽章 */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-active {
            background: linear-gradient(45deg, #10b981, #34d399);
            color: white;
        }
        
        .status-inactive {
            background: linear-gradient(45deg, #ef4444, #f87171);
            color: white;
        }
        
        /* API展示样式 */
        .api-display {
            background: #1a202c;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .api-header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
        }
        
        .api-content {
            padding: 20px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .api-content pre {
            color: #68d391;
            margin: 0;
            white-space: pre-wrap;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 13px;
            line-height: 1.4;
        }
        
        /* 页脚样式 */
        .footer {
            text-align: center;
            margin-top: 50px;
            padding: 30px 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: #764ba2;
        }
        
        /* 特效按钮 */
        .effect-btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            border: 2px solid #667eea;
            border-radius: 25px;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .effect-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            transition: left 0.3s ease;
            z-index: -1;
        }
        
        .effect-btn:hover::before {
            left: 0;
        }
        
        .effect-btn:hover {
            color: white;
            transform: translateY(-2px);
        }
        
        /* 响应式设计 */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .demo-section {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }
            
            .custom-table {
                font-size: 14px;
            }
            
            .custom-table th,
            .custom-table td {
                padding: 8px 10px;
            }
        }
        
        /* 加载动画 */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .demo-section {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .demo-section:nth-child(2) { animation-delay: 0.1s; }
        .demo-section:nth-child(3) { animation-delay: 0.2s; }
        .demo-section:nth-child(4) { animation-delay: 0.3s; }
        .demo-section:nth-child(5) { animation-delay: 0.4s; }
        
        /* 暗色主题样式 */
        .theme-dark .pagination-link {
            background-color: #4a5568 !important;
            border-color: #68d391 !important;
            color: #e2e8f0 !important;
        }
        
        .theme-dark .pagination-link:hover {
            background-color: #68d391 !important;
            color: #1a202c !important;
        }
        
        .theme-dark .pagination-active .pagination-link {
            background-color: #68d391 !important;
            border-color: #68d391 !important;
            color: #1a202c !important;
        }
        
        /* 彩色主题样式 */
        .theme-colorful .pagination-link {
            background: linear-gradient(45deg, #ff6b6b, #feca57) !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
        }
        
        .theme-colorful .pagination-link:hover {
            background: linear-gradient(45deg, #48dbfb, #ff9ff3) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3) !important;
        }
        
        .theme-colorful .pagination-active .pagination-link {
            background: linear-gradient(45deg, #ff9ff3, #48dbfb) !important;
            transform: scale(1.1) !important;
        }
        
        /* 动画分页样式 */
        .animated-pagination .pagination-link {
            background: linear-gradient(45deg, #667eea, #764ba2) !important;
            color: white !important;
            border: none !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative !important;
            overflow: hidden !important;
        }
        
        .animated-pagination .pagination-link::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: -100% !important;
            width: 100% !important;
            height: 100% !important;
            background: linear-gradient(45deg, rgba(255,255,255,0.2), rgba(255,255,255,0.4)) !important;
            transition: left 0.5s ease !important;
        }
        
        .animated-pagination .pagination-link:hover::before {
            left: 100% !important;
        }
        
        .animated-pagination .pagination-link:hover {
            transform: translateY(-3px) rotate(2deg) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        }
        
        .animated-pagination .pagination-active .pagination-link {
            background: linear-gradient(45deg, #48dbfb, #ff9ff3) !important;
            transform: scale(1.1) !important;
            animation: pulse 2s infinite !important;
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(72, 219, 251, 0.4); }
            50% { box-shadow: 0 0 0 10px rgba(72, 219, 251, 0); }
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- 页面标题 -->
    <div class="page-header">
        <h1>🎨 自定义CSS分页演示</h1>
        <p>展示 EnhancedPagination 在自定义CSS环境下的各种用法、主题和特效</p>
    </div>

    <!-- 返回主页 -->
    <a href="../index.php" class="nav-btn">← 返回主页</a>

    <!-- 1. 基础分页演示 -->
    <div class="demo-section">
        <h2 class="demo-title">1️⃣ 基础分页演示</h2>
        
        <div class="grid grid-2">
            <div>
                <div class="code-preview">
                    <h6>代码示例：</h6>
                    <pre><code>$pagination = new EnhancedPagination(
    500, 1, 20, [], [
    'framework' => 'custom',
    'language' => 'zh-cn'
]);</code></pre>
                </div>
            </div>
            <div>
                <div class="pagination-result">
                    <?php
                    $basic = new EnhancedPagination(500, 1, 20, [], [
                        'framework' => 'custom',
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
        <h2 class="demo-title">2️⃣ 不同尺寸演示</h2>
        
        <div class="grid grid-3">
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">小尺寸 (sm)</h5>
                <div class="pagination-result">
                    <?php
                    $small = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
                        'size' => 'sm',
                        'showStats' => false
                    ]);
                    echo $small->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">默认尺寸</h5>
                <div class="pagination-result">
                    <?php
                    $normal = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
                        'showStats' => false
                    ]);
                    echo $normal->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">大尺寸 (lg)</h5>
                <div class="pagination-result">
                    <?php
                    $large = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
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
        <h2 class="demo-title">3️⃣ 对齐方式演示</h2>
        
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">左对齐 (start)</h5>
                <div class="pagination-result">
                    <?php
                    $start = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
                        'alignment' => 'start',
                        'showStats' => false
                    ]);
                    echo $start->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">居中对齐 (center)</h5>
                <div class="pagination-result">
                    <?php
                    $center = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
                        'alignment' => 'center',
                        'showStats' => false
                    ]);
                    echo $center->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">右对齐 (end)</h5>
                <div class="pagination-result">
                    <?php
                    $end = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
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
        <h2 class="demo-title">4️⃣ 多语言演示</h2>
        
        <div class="grid grid-3">
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">中文 (zh-cn)</h5>
                <div class="pagination-result">
                    <?php
                    $zhcn = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
                        'language' => 'zh-cn'
                    ]);
                    echo $zhcn->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">英文 (en)</h5>
                <div class="pagination-result">
                    <?php
                    $en = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
                        'language' => 'en'
                    ]);
                    echo $en->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">日文 (ja)</h5>
                <div class="pagination-result">
                    <?php
                    $ja = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
                        'language' => 'ja'
                    ]);
                    echo $ja->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. 暗色主题演示 -->
    <div class="demo-section theme-dark">
        <h2 class="demo-title" style="color: #68d391; border-color: #68d391;">5️⃣ 暗色主题演示</h2>
        
        <div>
            <p style="color: #a0aec0; margin-bottom: 20px;">通过CSS覆盖可以轻松实现暗色主题：</p>
            <div class="pagination-result">
                <?php
                $dark = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                    'framework' => 'custom',
                    'language' => 'en',
                    'showStats' => false
                ]);
                echo $dark->render();
                ?>
            </div>
        </div>
    </div>

    <!-- 6. 彩色主题演示 -->
    <div class="demo-section theme-colorful">
        <h2 class="demo-title" style="color: white; border-color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">6️⃣ 彩色动态主题</h2>
        
        <div>
            <p style="color: white; margin-bottom: 20px; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">渐变背景动画 + 自定义分页样式：</p>
            <div class="pagination-result" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);">
                <?php
                $colorful = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                    'framework' => 'custom',
                    'language' => 'zh-cn',
                    'showStats' => false
                ]);
                echo $colorful->render();
                ?>
            </div>
        </div>
    </div>

    <!-- 7. 功能组合演示 -->
    <div class="demo-section">
        <h2 class="demo-title">7️⃣ 功能组合演示</h2>
        
        <div style="display: flex; flex-direction: column; gap: 30px;">
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">完整功能（大尺寸 + 英文 + 跳转 + 统计）</h5>
                <div class="pagination-result">
                    <?php
                    $full = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
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
            
            <div>
                <h5 style="color: #667eea; margin-bottom: 15px;">精简版（小尺寸 + 无跳转 + 无统计）</h5>
                <div class="pagination-result">
                    <?php
                    $simple = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
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

    <!-- 8. 实际数据演示 -->
    <div class="demo-section">
        <h2 class="demo-title">8️⃣ 实际数据演示</h2>
        
        <div>
            <p style="color: #666; margin-bottom: 30px;">以下是使用真实数据的分页演示，包含数据表格：</p>
            
            <!-- 数据表格 -->
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>姓名</th>
                        <th>邮箱</th>
                        <th>状态</th>
                        <th>部门</th>
                        <th>城市</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($users, 0, 5) as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td style="font-weight: 600;"><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="status-badge <?= $user['status'] === 'active' ? 'status-active' : 'status-inactive' ?>">
                                    <?= $user['status'] === 'active' ? '活跃' : '非活跃' ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($user['department']) ?></td>
                            <td><?= htmlspecialchars($user['city']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- 分页组件 -->
            <div class="pagination-result">
                <?php
                $realData = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                    'framework' => 'custom',
                    'language' => 'zh-cn',
                    'showJumper' => true,
                    'showStats' => true
                ]);
                echo $realData->render();
                ?>
            </div>
        </div>
    </div>

    <!-- 9. API数据演示 -->
    <div class="demo-section">
        <h2 class="demo-title">9️⃣ API数据演示</h2>
        
        <div>
            <p style="color: #666; margin-bottom: 20px;">分页组件返回的JSON数据结构：</p>
            <div class="api-display">
                <div class="api-header">
                    📄 JSON Response Data
                </div>
                <div class="api-content">
                    <pre><?= json_encode($realData->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- 10. CSS定制指南 -->
    <div class="demo-section">
        <h2 class="demo-title">🔟 CSS定制指南</h2>
        
        <div>
            <p style="color: #666; margin-bottom: 20px;">自定义CSS的优势在于完全可控的样式定制：</p>
            
            <div class="code-preview">
                <h6>主要CSS类名：</h6>
                <pre><code>/* 主要组件 */
.pagination-wrapper     - 分页容器
.pagination-nav         - 导航容器  
.pagination-list        - 页码列表
.pagination-item        - 页码项
.pagination-link        - 页码链接

/* 状态类 */
.pagination-active      - 当前页
.pagination-disabled    - 禁用状态

/* 功能组件 */
.pagination-jumper      - 跳转组件
.pagination-stats       - 统计信息
.pagination-mobile      - 移动端容器

/* 尺寸变体 */
.pagination-sm          - 小尺寸
.pagination-lg          - 大尺寸

/* 对齐方式 */
.pagination-start       - 左对齐
.pagination-center      - 居中对齐  
.pagination-end         - 右对齐</code></pre>
            </div>
            
            <div class="grid grid-2" style="margin-top: 30px;">
                <div>
                    <h6 style="color: #667eea; margin-bottom: 15px;">🎨 样式定制示例</h6>
                    <div class="code-preview">
                        <pre><code>/* 圆角风格 */
.pagination-link {
    border-radius: 50%;
    width: 40px;
    height: 40px;
}

/* 渐变按钮 */
.pagination-link {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    border: none;
}

/* 悬浮特效 */
.pagination-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}</code></pre>
                    </div>
                </div>
                
                <div>
                    <h6 style="color: #667eea; margin-bottom: 15px;">📱 响应式设计</h6>
                    <div class="code-preview">
                        <pre><code>/* 移动端优化 */
@media (max-width: 768px) {
    .pagination-link {
        padding: 8px;
        min-width: 36px;
    }
    
    .pagination-text {
        display: none;
    }
    
    .pagination-mobile {
        display: block;
    }
    
    .pagination-desktop {
        display: none;
    }
}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 11. 动画效果演示 -->
    <div class="demo-section">
        <h2 class="demo-title">1️⃣1️⃣ 动画效果演示</h2>
        
        <div>
            <p style="color: #666; margin-bottom: 20px;">添加CSS动画让分页更加生动：</p>
            
            <div class="pagination-result">
                <div class="animated-pagination">
                    <?php
                    $animated = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'custom',
                        'language' => 'zh-cn',
                        'showStats' => false
                    ]);
                    echo $animated->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 页脚 -->
    <footer class="footer">
        <p style="margin-bottom: 10px;">
            🚀 
            <a href="https://github.com/bluesailor/enhanced-pagination" target="_blank">
                Enhanced Pagination
            </a> - 自定义CSS演示
        </p>
        <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
            切换到其他框架：
            <a href="bootstrap-demo.php" class="effect-btn">Bootstrap 5.3</a>
            <a href="tailwind-demo.php" class="effect-btn">Tailwind CSS</a>
        </p>
        <p style="color: #888; font-size: 12px;">
            💡 提示：自定义CSS版本提供了最大的设计自由度，你可以创造任何你想要的分页样式！
        </p>
    </footer>

</div>

<script>
// 添加一些交互效果
document.addEventListener('DOMContentLoaded', function() {
    // 为演示区块添加鼠标悬浮效果
    const sections = document.querySelectorAll('.demo-section');
    sections.forEach(section => {
        section.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        section.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // 代码预览区域点击复制功能
    const codeBlocks = document.querySelectorAll('.code-preview pre code');
    codeBlocks.forEach(code => {
        code.style.cursor = 'pointer';
        code.title = '点击复制代码';
        
        code.addEventListener('click', function() {
            const text = this.textContent;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    // 显示复制成功提示
                    const originalText = this.textContent;
                    this.textContent = '✅ 代码已复制到剪贴板！';
                    this.style.color = '#10b981';
                    
                    setTimeout(() => {
                        this.textContent = originalText;
                        this.style.color = '';
                    }, 2000);
                });
            }
        });
    });
    
    // 平滑滚动效果
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

</body>
</html>