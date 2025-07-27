<?php
/**
 * Tailwind CSS 4.1 框架演示
 * 展示分页类在Tailwind环境下的各种用法
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
    <title>Tailwind CSS 分页演示 - EnhancedPagination</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .demo-section {
            @apply mb-12 p-8 border border-gray-200 rounded-lg bg-white shadow-sm;
        }
        
        .demo-title {
            @apply text-gray-700 border-b-2 border-blue-500 pb-2 mb-6 text-2xl font-semibold;
        }
        
        .code-preview {
            @apply bg-gray-50 border-l-4 border-blue-500 p-4 my-4 rounded-r-md;
        }
        
        .pagination-result {
            @apply border border-gray-200 rounded-md p-6 bg-gray-50;
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-blue': '#3b82f6',
                        'custom-gray': '#6b7280'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="container mx-auto px-4 py-8 max-w-7xl">
    
    <!-- 页面标题 -->
    <div class="mb-12">
        <div class="text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white p-8 rounded-lg shadow-lg">
            <h1 class="text-4xl font-bold mb-4">
                🎨 Tailwind CSS 分页演示
            </h1>
            <p class="text-xl opacity-90">展示 EnhancedPagination 在 Tailwind 框架下的各种用法和样式</p>
        </div>
    </div>

    <!-- 返回主页 -->
    <div class="mb-8">
        <a href="../index.php" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition-colors">
            ← 返回主页
        </a>
    </div>

    <!-- 1. 基础分页演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            1️⃣ 基础分页演示
        </h2>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <div class="code-preview">
                    <h6 class="font-semibold text-gray-700 mb-2">代码示例：</h6>
                    <pre class="text-sm text-gray-600"><code>$pagination = new EnhancedPagination(
    500, 1, 20, [], [
    'framework' => 'tailwind',
    'language' => 'zh-cn'
]);</code></pre>
                </div>
            </div>
            <div>
                <div class="pagination-result">
                    <h6 class="font-semibold text-gray-700 mb-4">效果展示：</h6>
                    <?php
                    $basic = new EnhancedPagination(500, 1, 20, [], [
                        'framework' => 'tailwind',
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
            2️⃣ 不同尺寸演示
        </h2>
        
        <div class="grid lg:grid-cols-3 gap-6">
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">小尺寸 (sm)</h5>
                <div class="pagination-result">
                    <?php
                    $small = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
                        'size' => 'sm',
                        'showStats' => false
                    ]);
                    echo $small->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">默认尺寸</h5>
                <div class="pagination-result">
                    <?php
                    $normal = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
                        'showStats' => false
                    ]);
                    echo $normal->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">大尺寸 (lg)</h5>
                <div class="pagination-result">
                    <?php
                    $large = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
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
            3️⃣ 对齐方式演示
        </h2>
        
        <div class="space-y-6">
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">左对齐 (start)</h5>
                <div class="pagination-result">
                    <?php
                    $start = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
                        'alignment' => 'start',
                        'showStats' => false
                    ]);
                    echo $start->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">居中对齐 (center)</h5>
                <div class="pagination-result">
                    <?php
                    $center = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
                        'alignment' => 'center',
                        'showStats' => false
                    ]);
                    echo $center->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">右对齐 (end)</h5>
                <div class="pagination-result">
                    <?php
                    $end = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
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
            4️⃣ 多语言演示
        </h2>
        
        <div class="grid lg:grid-cols-3 gap-6">
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">中文 (zh-cn)</h5>
                <div class="pagination-result">
                    <?php
                    $zhcn = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
                        'language' => 'zh-cn'
                    ]);
                    echo $zhcn->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">英文 (en)</h5>
                <div class="pagination-result">
                    <?php
                    $en = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
                        'language' => 'en'
                    ]);
                    echo $en->render();
                    ?>
                </div>
            </div>
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-3">日文 (ja)</h5>
                <div class="pagination-result">
                    <?php
                    $ja = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
                        'language' => 'ja'
                    ]);
                    echo $ja->render();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. 现代化样式演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            5️⃣ 现代化样式演示
        </h2>
        
        <div class="space-y-8">
            <!-- 渐变风格 -->
            <div class="bg-gradient-to-r from-purple-100 to-pink-100 p-6 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">渐变背景风格</h5>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <?php
                    $gradient = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
                        'language' => 'en',
                        'size' => 'lg',
                        'showStats' => false
                    ]);
                    echo $gradient->render();
                    ?>
                </div>
            </div>
            
            <!-- 暗色主题风格 -->
            <div class="bg-gray-800 p-6 rounded-lg">
                <h5 class="text-lg font-semibold text-white mb-4">暗色主题（需要自定义CSS）</h5>
                <div class="bg-gray-700 p-4 rounded-lg">
                    <div class="text-center text-gray-300 py-4">
                        暗色主题需要自定义CSS覆盖，请查看自定义CSS演示
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. 功能组合演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            6️⃣ 功能组合演示
        </h2>
        
        <div class="space-y-8">
            <div>
                <h5 class="text-lg font-semibold text-gray-700 mb-4">完整功能（大尺寸 + 英文 + 跳转 + 统计）</h5>
                <div class="pagination-result">
                    <?php
                    $full = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
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
                <h5 class="text-lg font-semibold text-gray-700 mb-4">精简版（小尺寸 + 无跳转 + 无统计）</h5>
                <div class="pagination-result">
                    <?php
                    $simple = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                        'framework' => 'tailwind',
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

    <!-- 7. 实际数据演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            7️⃣ 实际数据演示
        </h2>
        
        <div>
            <p class="text-gray-600 mb-6">以下是使用真实数据的分页演示，包含数据表格：</p>
            
            <!-- 数据表格 -->
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">姓名</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">邮箱</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">状态</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">部门</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach (array_slice($users, 0, 5) as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($user['name']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                        <?= $user['status'] === 'active' ? '活跃' : '非活跃' ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($user['department']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- 分页组件 -->
            <div class="pagination-result">
                <?php
                $realData = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                    'framework' => 'tailwind',
                    'language' => 'zh-cn',
                    'showJumper' => true,
                    'showStats' => true
                ]);
                echo $realData->render();
                ?>
            </div>
        </div>
    </div>

    <!-- 8. API数据演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            8️⃣ API数据演示
        </h2>
        
        <div>
            <p class="text-gray-600 mb-4">分页组件返回的JSON数据结构：</p>
            <div class="bg-gray-900 rounded-lg overflow-hidden">
                <div class="bg-gray-800 px-4 py-2 border-b border-gray-700">
                    <h6 class="text-white font-medium">
                        📄 JSON Response
                    </h6>
                </div>
                <div class="p-4">
                    <pre class="text-green-400 text-sm overflow-x-auto"><code><?= json_encode($realData->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></code></pre>
                </div>
            </div>
        </div>
    </div>

    <!-- 9. 响应式演示 -->
    <div class="demo-section">
        <h2 class="demo-title">
            9️⃣ 响应式演示
        </h2>
        
        <div>
            <p class="text-gray-600 mb-6">调整浏览器窗口大小查看响应式效果：</p>
            
            <div class="grid gap-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h6 class="font-semibold text-blue-800 mb-2">🖥️ 桌面端视图</h6>
                    <p class="text-blue-700 text-sm mb-4">显示完整的分页导航和所有功能</p>
                    <div class="hidden md:block">
                        <?php
                        $desktop = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                            'framework' => 'tailwind',
                            'mobileOptimized' => true,
                            'showStats' => false
                        ]);
                        echo $desktop->render();
                        ?>
                    </div>
                    <div class="md:hidden text-center py-4 text-blue-600">
                        请在桌面端查看完整效果
                    </div>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h6 class="font-semibold text-green-800 mb-2">📱 移动端视图</h6>
                    <p class="text-green-700 text-sm mb-4">显示简化的分页导航，更适合触摸操作</p>
                    <div class="block md:hidden">
                        <?php
                        $mobile = new EnhancedPagination($actualTotal, $current, $pageSize, $_GET, [
                            'framework' => 'tailwind',
                            'mobileOptimized' => true,
                            'showStats' => false
                        ]);
                        echo $mobile->render();
                        ?>
                    </div>
                    <div class="hidden md:block text-center py-4 text-green-600">
                        请在移动端查看简化效果
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 页脚 -->
    <footer class="text-center mt-12 py-8 border-t border-gray-200">
        <p class="text-gray-600 mb-4">
            🚀 
            <a href="https://github.com/bluesailor/enhanced-pagination" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
                Enhanced Pagination
            </a> - Tailwind CSS 演示
        </p>
        <p class="text-gray-500 text-sm">
            切换到其他框架：
            <a href="bootstrap-demo.php" class="text-blue-600 hover:text-blue-800 transition-colors">Bootstrap 5.3</a> |
            <a href="custom-demo.php" class="text-blue-600 hover:text-blue-800 transition-colors">自定义CSS</a>
        </p>
    </footer>

</div>

</body>
</html>