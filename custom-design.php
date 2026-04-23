<?php
require 'includes/config.php';
redirectToLogin();

$pageTitle = 'Design Your Apparel';
$bodyClass = 'app-page design-page';
$mobileDockCurrent = 'design';

// Get user discount type
$user_discount = 'regular';
$user_stmt = $conn->prepare("SELECT user_type FROM users WHERE id = ?");
$user_stmt->bind_param("i", $_SESSION['user_id']);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    $user_discount = $user_data['user_type'];
}
$user_stmt->close();

// Run migration if table doesn't exist
$tableCheck = $conn->query("SHOW TABLES LIKE 'custom_designs'");
if ($tableCheck->num_rows === 0) {
    $migrationSQL = file_get_contents(__DIR__ . '/migrate_custom_designs.sql');
    if ($migrationSQL) {
        $conn->multi_query($migrationSQL);
        while ($conn->next_result()) {;}
    }
}
?>

<?php include 'includes/header/header.php'; ?>

<style>
/* Design Tool Styles */
.design-tool-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 1.5rem;
}

.design-tool-container.app-page-shell {
    padding-top: 1.25rem;
    padding-bottom: 2rem;
}

.design-tool-header {
    text-align: center;
    margin-bottom: 2rem;
}

.design-tool-header.app-page-hero {
    padding: 1.5rem 1.25rem;
    border-radius: 28px;
}

.design-tool-header h1 {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--text-dark, #1a1a1a);
}

.design-tool-header p {
    color: var(--text-light, #666);
    font-size: 1rem;
    max-width: 600px;
    margin: 0.5rem auto 0;
}

.design-workspace {
    display: grid;
    grid-template-columns: 280px 1fr 300px;
    gap: 1.5rem;
    min-height: 650px;
}

/* Left Toolbar */
.design-toolbar {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    border: 1px solid var(--border-light, #e5e5e5);
    overflow-y: auto;
    max-height: 700px;
}

.tool-section {
    margin-bottom: 1.25rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--border-light, #eee);
}

.tool-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.tool-section h6 {
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-light, #888);
    margin-bottom: 0.75rem;
}

.tool-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.6rem 0.75rem;
    border: 1px solid var(--border-light, #e0e0e0);
    background: #fff;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.85rem;
    color: var(--text-dark, #333);
    margin-bottom: 0.4rem;
}

.tool-btn:hover {
    background: #f8f9fa;
    border-color: #ccc;
}

.tool-btn.active {
    background: var(--accent-green, #2d6a4f);
    color: #fff;
    border-color: var(--accent-green, #2d6a4f);
}

.tool-btn i {
    width: 18px;
    text-align: center;
}

/* Apparel Type Selector */
.apparel-types {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 0.5rem;
}

.apparel-type-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
    padding: 0.6rem 0.4rem;
    border: 2px solid var(--border-light, #e0e0e0);
    background: #fff;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.72rem;
    font-weight: 600;
}

.apparel-type-btn:hover {
    border-color: var(--accent-green, #2d6a4f);
}

.apparel-type-btn.active {
    border-color: var(--accent-green, #2d6a4f);
    background: rgba(45,106,79,0.06);
    color: var(--accent-green, #2d6a4f);
}

.apparel-type-btn i {
    font-size: 1.4rem;
}

/* Color Picker */
.color-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.color-swatch-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid #ddd;
    cursor: pointer;
    transition: all 0.15s;
    padding: 0;
}

.color-swatch-btn:hover,
.color-swatch-btn.active {
    transform: scale(1.15);
    border-color: #333;
    box-shadow: 0 0 0 2px rgba(0,0,0,0.15);
}

/* Brush Size */
.brush-size-slider {
    width: 100%;
    accent-color: var(--accent-green, #2d6a4f);
}

/* Canvas Area */
.design-canvas-area {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    border: 1px solid var(--border-light, #e5e5e5);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.canvas-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--border-light, #eee);
    background: #fafafa;
}

.canvas-toolbar-left,
.canvas-toolbar-right {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.canvas-action-btn {
    padding: 0.4rem 0.6rem;
    border: 1px solid #e0e0e0;
    background: #fff;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.15s;
    color: #555;
}

.canvas-action-btn:hover {
    background: #f0f0f0;
    color: #222;
}

.canvas-action-btn.danger:hover {
    background: #fee;
    color: #c0392b;
    border-color: #f5c6cb;
}

.side-toggle-btn {
    font-weight: 600;
    padding: 0.4rem 0.8rem;
}

.side-toggle-btn.active {
    background: var(--accent-green, #2ECC40);
    color: #fff;
    border-color: var(--accent-green, #2ECC40);
}

.side-toggle-btn.active:hover {
    background: #27ae60;
    color: #fff;
}

.canvas-wrapper {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    background: repeating-conic-gradient(#f0f0f0 0% 25%, #fafafa 0% 50%) 50% / 20px 20px;
    position: relative;
    overflow: hidden;
}

.mockup-container {
    position: relative;
    width: 400px;
    height: 500px;
}

.mockup-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

#designCanvas {
    position: absolute;
    z-index: 2;
    cursor: crosshair;
    border-radius: 4px;
}

/* Right Panel - Preview & Settings */
.design-preview-panel {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    border: 1px solid var(--border-light, #e5e5e5);
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.preview-section h6 {
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-light, #888);
    margin-bottom: 0.75rem;
}

.live-preview-box {
    width: 100%;
    aspect-ratio: 3/4;
    background: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.live-preview-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.preview-placeholder {
    text-align: center;
    color: #bbb;
}

.preview-placeholder i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

/* Text Input */
.text-input-group {
    display: flex;
    gap: 0.4rem;
}

.text-input-group input {
    flex: 1;
    padding: 0.5rem 0.7rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.85rem;
}

.text-input-group button {
    padding: 0.5rem 0.7rem;
    border: none;
    background: var(--accent-green, #2d6a4f);
    color: #fff;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.8rem;
}

/* Font Selector */
.font-selector {
    width: 100%;
    padding: 0.45rem 0.6rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.85rem;
    background: #fff;
    margin-bottom: 0.5rem;
}

/* Upload Area */
.upload-area {
    border: 2px dashed #ddd;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: #fafafa;
}

.upload-area:hover {
    border-color: var(--accent-green, #2d6a4f);
    background: rgba(45,106,79,0.03);
}

.upload-area i {
    font-size: 1.5rem;
    color: #bbb;
    margin-bottom: 0.4rem;
}

.upload-area p {
    font-size: 0.78rem;
    color: #999;
    margin: 0;
}

/* Submit Section */
.submit-section {
    margin-top: auto;
}

.submit-section textarea {
    width: 100%;
    padding: 0.6rem;
    border: 1px solid #ddd;
    border-radius: 10px;
    resize: vertical;
    min-height: 60px;
    font-size: 0.85rem;
    margin-bottom: 0.75rem;
}

.btn-submit-design {
    width: 100%;
    padding: 0.75rem 1.5rem;
    border: none;
    background: var(--accent-green, #2d6a4f);
    color: #fff;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-submit-design:hover {
    background: #245a42;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(45,106,79,0.3);
}

.btn-submit-design:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Apparel Color Selector */
.apparel-color-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.apparel-color-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid #ddd;
    cursor: pointer;
    transition: all 0.15s;
    padding: 0;
}

.apparel-color-btn:hover,
.apparel-color-btn.active {
    transform: scale(1.15);
    border-color: #333;
}

/* My Designs Section */
.my-designs-section {
    margin-top: 2rem;
}

.my-designs-section.app-section-surface {
    padding: 1.5rem;
    border-radius: 24px;
}

.designs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
}

.design-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #eee;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.2s;
}

.design-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.design-card-img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    background: #f8f9fa;
}

.design-card-body {
    padding: 0.75rem;
}

.design-card-body h6 {
    font-weight: 700;
    margin-bottom: 0.25rem;
    font-size: 0.85rem;
}

.design-card-body small {
    color: #999;
}

.design-status-badge {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.design-status-badge.pending { background: #fff3cd; color: #856404; }
.design-status-badge.approved { background: #d4edda; color: #155724; }
.design-status-badge.revision { background: #f8d7da; color: #721c24; }
.design-status-badge.completed { background: #d1ecf1; color: #0c5460; }
.design-status-badge.cancelled { background: #e2e3e5; color: #383d41; }

/* Responsive */
@media (max-width: 1100px) {
    .design-workspace {
        grid-template-columns: 220px 1fr;
    }
    .design-preview-panel {
        grid-column: 1 / -1;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .live-preview-box {
        aspect-ratio: auto;
        height: 200px;
        width: 200px;
    }
}

@media (max-width: 768px) {
    .design-tool-container.app-page-shell {
        padding: 1rem 0.75rem 1.5rem;
    }
    .design-tool-header.app-page-hero {
        padding: 1.25rem 1rem;
        margin-bottom: 1.25rem;
    }
    .design-workspace {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .design-toolbar,
    .design-canvas-area,
    .design-preview-panel {
        border-radius: 24px;
        box-shadow: 0 14px 40px rgba(18,18,18,0.08);
    }
    .canvas-toolbar {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
    }
    .canvas-toolbar-left,
    .canvas-toolbar-right {
        flex-wrap: wrap;
    }
    .mockup-container {
        width: 300px;
        height: 380px;
    }
    .design-tool-header h1 {
        font-size: 1.6rem;
    }
    .my-designs-section.app-section-surface {
        margin-top: 1rem;
        padding: 1.1rem;
    }
}

/* Draggable elements */
.draggable-element {
    position: absolute;
    cursor: move;
    z-index: 10;
    user-select: none;
    border: 2px solid transparent;
    padding: 2px;
}

.draggable-element:hover,
.draggable-element.selected {
    border-color: var(--accent-green, #2d6a4f);
}

.draggable-element .resize-handle {
    position: absolute;
    width: 10px;
    height: 10px;
    background: var(--accent-green, #2d6a4f);
    border-radius: 50%;
    bottom: -5px;
    right: -5px;
    cursor: se-resize;
    display: none;
}

.draggable-element.selected .resize-handle {
    display: block;
}

.draggable-element .delete-handle {
    position: absolute;
    width: 18px;
    height: 18px;
    background: #e74c3c;
    color: #fff;
    border-radius: 50%;
    top: -8px;
    right: -8px;
    cursor: pointer;
    display: none;
    font-size: 10px;
    line-height: 18px;
    text-align: center;
}

.draggable-element.selected .delete-handle {
    display: block;
}

/* ===== AI Design Suggestion ===== */
.ai-suggest-input {
    display: flex;
    gap: 0.4rem;
}
.ai-suggest-input input {
    flex: 1;
    padding: 0.5rem 0.7rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.82rem;
}
.ai-suggest-input button {
    padding: 0.5rem 0.7rem;
    border: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.8rem;
    white-space: nowrap;
    transition: all 0.2s;
}
.ai-suggest-input button:hover { opacity: 0.9; transform: translateY(-1px); }
.ai-suggest-input button:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

.ai-suggestions-list {
    margin-top: 0.6rem;
    max-height: 300px;
    overflow-y: auto;
}
.ai-suggestion-card {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f0ff 100%);
    border: 1px solid #e0e0f0;
    border-radius: 10px;
    padding: 0.65rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
}
.ai-suggestion-card:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102,126,234,0.15);
}
.ai-suggestion-card h6 {
    font-size: 0.78rem;
    font-weight: 700;
    color: #4a4a8a;
    margin-bottom: 0.3rem;
}
.ai-suggestion-card p {
    font-size: 0.72rem;
    color: #666;
    margin: 0 0 0.3rem;
    line-height: 1.4;
}
.ai-suggestion-colors {
    display: flex;
    gap: 4px;
    margin-bottom: 0.25rem;
}
.ai-suggestion-colors span {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 1px solid #ccc;
    display: inline-block;
    cursor: pointer;
    transition: transform 0.15s;
}
.ai-suggestion-colors span:hover { transform: scale(1.3); }
.ai-suggestion-tip {
    font-size: 0.68rem;
    color: #8888aa;
    font-style: italic;
}
.ai-apply-btn {
    display: block;
    width: 100%;
    margin-top: 0.5rem;
    padding: 0.35rem 0.6rem;
    background: linear-gradient(135deg, #6C63FF, #FF6584);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.15s;
}
.ai-apply-btn:hover { opacity: 0.9; transform: translateY(-1px); }
.ai-apply-btn:active { transform: scale(0.97); }
.ai-loading {
    text-align: center;
    padding: 1rem;
    color: #888;
}
.ai-loading i { animation: spin 1s linear infinite; }

/* ===== 3D Preview ===== */
.preview-3d-container {
    perspective: 800px;
    width: 100%;
    aspect-ratio: 3/4;
    position: relative;
    cursor: grab;
}
.preview-3d-container:active { cursor: grabbing; }
.preview-3d-inner {
    width: 100%;
    height: 100%;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.1s ease-out;
}
.preview-3d-inner.spinning {
    animation: spin3d 6s linear infinite;
}
.preview-3d-face {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 12px;
    overflow: hidden;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}
.preview-3d-face canvas {
    max-width: 100%;
    max-height: 100%;
}
.preview-3d-front { transform: rotateY(0deg); }
.preview-3d-back { transform: rotateY(180deg); }
.preview-3d-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}
.preview-3d-controls button {
    padding: 0.3rem 0.6rem;
    border: 1px solid #e0e0e0;
    background: #fff;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.75rem;
    transition: all 0.15s;
    color: #555;
}
.preview-3d-controls button:hover { background: #f0f0f0; }
.preview-3d-controls button.active { background: var(--accent-green, #2d6a4f); color: #fff; border-color: var(--accent-green); }
.preview-3d-label {
    font-size: 0.7rem;
    color: #aaa;
    text-align: center;
    margin-top: 0.25rem;
}

@keyframes spin3d {
    from { transform: rotateY(0deg); }
    to { transform: rotateY(360deg); }
}

/* ===== Price Calculator ===== */
.price-calculator {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    border: 1px solid #bbf7d0;
    border-radius: 12px;
    padding: 0.85rem;
}
.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    color: #555;
    padding: 0.3rem 0;
}
.price-row.total {
    border-top: 2px solid #86efac;
    margin-top: 0.4rem;
    padding-top: 0.5rem;
    font-weight: 800;
    font-size: 1rem;
    color: var(--accent-green, #2d6a4f);
}
.price-label { color: #666; }
.price-value { font-weight: 600; color: #333; }
.price-select {
    padding: 0.3rem 0.5rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.78rem;
    background: #fff;
}
</style>

<div class="design-tool-container app-page-shell">
    <div class="design-tool-header app-page-hero">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center" style="font-size:0.85rem;">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="shop.php" class="text-decoration-none">Shop</a></li>
                <li class="breadcrumb-item active">Design Your Apparel</li>
            </ol>
        </nav>
        <h1><i class="fas fa-palette me-2"></i>Design Your Apparel</h1>
        <p>Create your own custom clothing design. Draw, upload images, add text, and preview on real apparel mockups.</p>
    </div>

    <div class="app-mobile-chip-row d-lg-none mb-3">
        <a href="shop.php" class="app-mobile-chip">Shop</a>
        <a href="cart.php" class="app-mobile-chip">Cart</a>
        <a href="my-custom-orders.php" class="app-mobile-chip">Custom Orders</a>
        <a href="profile.php" class="app-mobile-chip">Profile</a>
    </div>

    <div class="design-workspace">
        <!-- Left Toolbar -->
        <div class="design-toolbar">
            <!-- Apparel Type -->
            <div class="tool-section">
                <h6><i class="fas fa-shirt me-1"></i> Apparel Type</h6>
                <div class="apparel-types">
                    <button class="apparel-type-btn active" data-type="tshirt" onclick="setApparelType('tshirt')">
                        <i class="fas fa-shirt"></i>
                        T-Shirt
                    </button>
                    <button class="apparel-type-btn" data-type="hoodie" onclick="setApparelType('hoodie')">
                        <i class="fas fa-vest"></i>
                        Hoodie
                    </button>
                    <button class="apparel-type-btn" data-type="polo" onclick="setApparelType('polo')">
                        <i class="fas fa-shirt"></i>
                        Polo
                    </button>
                </div>
            </div>

            <!-- Apparel Color -->
            <div class="tool-section">
                <h6><i class="fas fa-fill-drip me-1"></i> Apparel Color</h6>
                <div class="apparel-color-grid">
                    <button class="apparel-color-btn active" style="background:#FFFFFF" data-color="#FFFFFF" onclick="setApparelColor(this, '#FFFFFF')" title="White"></button>
                    <button class="apparel-color-btn" style="background:#000000" data-color="#000000" onclick="setApparelColor(this, '#000000')" title="Black"></button>
                    <button class="apparel-color-btn" style="background:#001F3F" data-color="#001F3F" onclick="setApparelColor(this, '#001F3F')" title="Navy"></button>
                    <button class="apparel-color-btn" style="background:#808080" data-color="#808080" onclick="setApparelColor(this, '#808080')" title="Gray"></button>
                    <button class="apparel-color-btn" style="background:#FF4136" data-color="#FF4136" onclick="setApparelColor(this, '#FF4136')" title="Red"></button>
                    <button class="apparel-color-btn" style="background:#2ECC40" data-color="#2ECC40" onclick="setApparelColor(this, '#2ECC40')" title="Green"></button>
                    <button class="apparel-color-btn" style="background:#0074D9" data-color="#0074D9" onclick="setApparelColor(this, '#0074D9')" title="Blue"></button>
                    <button class="apparel-color-btn" style="background:#FFDC00" data-color="#FFDC00" onclick="setApparelColor(this, '#FFDC00')" title="Yellow"></button>
                    <button class="apparel-color-btn" style="background:#FF69B4" data-color="#FF69B4" onclick="setApparelColor(this, '#FF69B4')" title="Pink"></button>
                    <button class="apparel-color-btn" style="background:#800000" data-color="#800000" onclick="setApparelColor(this, '#800000')" title="Maroon"></button>
                </div>
            </div>

            <!-- Drawing Tools -->
            <div class="tool-section">
                <h6><i class="fas fa-pen me-1"></i> Drawing Tools</h6>
                <button class="tool-btn active" data-tool="brush" onclick="setTool('brush')">
                    <i class="fas fa-paintbrush"></i> Brush
                </button>
                <button class="tool-btn" data-tool="eraser" onclick="setTool('eraser')">
                    <i class="fas fa-eraser"></i> Eraser
                </button>
                <button class="tool-btn" data-tool="line" onclick="setTool('line')">
                    <i class="fas fa-minus"></i> Line
                </button>
                <button class="tool-btn" data-tool="rect" onclick="setTool('rect')">
                    <i class="fas fa-square"></i> Rectangle
                </button>
                <button class="tool-btn" data-tool="circle" onclick="setTool('circle')">
                    <i class="fas fa-circle"></i> Circle
                </button>
            </div>

            <!-- Brush Settings -->
            <div class="tool-section">
                <h6><i class="fas fa-sliders-h me-1"></i> Brush Settings</h6>
                <label style="font-size:0.78rem; color:#888;">Size: <span id="brushSizeLabel">4</span>px</label>
                <input type="range" class="brush-size-slider" id="brushSize" min="1" max="30" value="4" oninput="setBrushSize(this.value)">
                
                <label style="font-size:0.78rem; color:#888; margin-top:0.4rem; display:block;">Color</label>
                <div class="color-grid">
                    <button class="color-swatch-btn active" style="background:#000000" onclick="setBrushColor(this, '#000000')"></button>
                    <button class="color-swatch-btn" style="background:#FFFFFF" onclick="setBrushColor(this, '#FFFFFF')"></button>
                    <button class="color-swatch-btn" style="background:#FF4136" onclick="setBrushColor(this, '#FF4136')"></button>
                    <button class="color-swatch-btn" style="background:#0074D9" onclick="setBrushColor(this, '#0074D9')"></button>
                    <button class="color-swatch-btn" style="background:#2ECC40" onclick="setBrushColor(this, '#2ECC40')"></button>
                    <button class="color-swatch-btn" style="background:#FFDC00" onclick="setBrushColor(this, '#FFDC00')"></button>
                    <button class="color-swatch-btn" style="background:#FF69B4" onclick="setBrushColor(this, '#FF69B4')"></button>
                    <button class="color-swatch-btn" style="background:#B10DC9" onclick="setBrushColor(this, '#B10DC9')"></button>
                    <button class="color-swatch-btn" style="background:#FF851B" onclick="setBrushColor(this, '#FF851B')"></button>
                    <button class="color-swatch-btn" style="background:#8B4513" onclick="setBrushColor(this, '#8B4513')"></button>
                </div>
                <div class="mt-2">
                    <input type="color" id="customColor" value="#000000" style="width:100%; height: 28px; border:none; cursor:pointer; border-radius:6px;" onchange="setBrushColor(null, this.value)">
                </div>
            </div>

            <!-- Text Tool -->
            <div class="tool-section">
                <h6><i class="fas fa-font me-1"></i> Add Text</h6>
                <select class="font-selector" id="fontFamily">
                    <option value="Arial">Arial</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Impact">Impact</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Comic Sans MS">Comic Sans MS</option>
                    <option value="Trebuchet MS">Trebuchet MS</option>
                    <option value="Verdana">Verdana</option>
                    <option value="Times New Roman">Times New Roman</option>
                </select>
                <div style="display:flex; gap:0.3rem; margin-bottom:0.5rem;">
                    <input type="number" id="fontSize" value="24" min="8" max="72" style="width:60px; padding:0.35rem; border:1px solid #ddd; border-radius:6px; font-size:0.8rem;" title="Font size">
                    <button class="canvas-action-btn" id="boldBtn" onclick="toggleBold()" title="Bold"><i class="fas fa-bold"></i></button>
                    <button class="canvas-action-btn" id="italicBtn" onclick="toggleItalic()" title="Italic"><i class="fas fa-italic"></i></button>
                </div>
                <div class="text-input-group">
                    <input type="text" id="textInput" placeholder="Type text here..." maxlength="100">
                    <button onclick="addTextToCanvas()" title="Add Text"><i class="fas fa-plus"></i></button>
                </div>
            </div>

            <!-- Upload Image -->
            <div class="tool-section">
                <h6><i class="fas fa-image me-1"></i> Upload Image</h6>
                <div class="upload-area" onclick="document.getElementById('imageUpload').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload image or logo</p>
                    <p style="font-size:0.7rem;">(PNG, JPG, max 5MB)</p>
                </div>
                <input type="file" id="imageUpload" accept="image/png,image/jpeg,image/webp" style="display:none" onchange="handleImageUpload(this)">
            </div>
        </div>

        <!-- Canvas Area -->
        <div class="design-canvas-area">
            <div class="canvas-toolbar">
                <div class="canvas-toolbar-left">
                    <button class="canvas-action-btn side-toggle-btn active" id="frontSideBtn" onclick="switchSide('front')" title="Front View"><i class="fas fa-tshirt"></i> Front</button>
                    <button class="canvas-action-btn side-toggle-btn" id="backSideBtn" onclick="switchSide('back')" title="Back View"><i class="fas fa-retweet"></i> Back</button>
                    <span style="color:#ccc; font-size:0.8rem;">|</span>
                    <button class="canvas-action-btn" onclick="undoAction()" title="Undo"><i class="fas fa-undo"></i></button>
                    <button class="canvas-action-btn" onclick="redoAction()" title="Redo"><i class="fas fa-redo"></i></button>
                    <span style="color:#ccc; font-size:0.8rem;">|</span>
                    <button class="canvas-action-btn" onclick="zoomIn()" title="Zoom In"><i class="fas fa-search-plus"></i></button>
                    <button class="canvas-action-btn" onclick="zoomOut()" title="Zoom Out"><i class="fas fa-search-minus"></i></button>
                    <button class="canvas-action-btn" onclick="resetZoom()" title="Reset View"><i class="fas fa-expand"></i></button>
                </div>
                <div class="canvas-toolbar-right">
                    <button class="canvas-action-btn" onclick="deleteSelected()" title="Delete Selected"><i class="fas fa-trash"></i></button>
                    <button class="canvas-action-btn danger" onclick="clearCanvas()" title="Clear All"><i class="fas fa-times"></i> Clear</button>
                </div>
            </div>
            <div class="canvas-wrapper" id="canvasWrapper">
                <div class="mockup-container" id="mockupContainer">
                    <!-- SVG Mockup will be drawn here -->
                    <svg class="mockup-svg" id="mockupSvg" viewBox="0 0 400 500" xmlns="http://www.w3.org/2000/svg"></svg>
                    <canvas id="designCanvas" width="200" height="240"></canvas>
                    <!-- Draggable elements layer -->
                    <div id="elementsLayer" style="position:absolute; top:0; left:0; width:100%; height:100%; z-index:5; pointer-events:none;"></div>
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="design-preview-panel">
            <!-- 3D Apparel Preview -->
            <div class="preview-section">
                <h6><i class="fas fa-cube me-1"></i> 3D Preview</h6>
                <div class="preview-3d-container" id="preview3dContainer">
                    <div class="preview-3d-inner" id="preview3dInner">
                        <div class="preview-3d-face preview-3d-front">
                            <canvas id="previewCanvasFront" width="400" height="500"></canvas>
                        </div>
                        <div class="preview-3d-face preview-3d-back">
                            <canvas id="previewCanvasBack" width="400" height="500"></canvas>
                        </div>
                    </div>
                </div>
                <div class="preview-3d-controls">
                    <button onclick="rotate3DLeft()" title="Rotate Left"><i class="fas fa-arrow-rotate-left"></i></button>
                    <button onclick="toggle3DSpin()" id="spinBtn" class="active" title="Auto Spin"><i class="fas fa-sync-alt"></i> Spin</button>
                    <button onclick="rotate3DRight()" title="Rotate Right"><i class="fas fa-arrow-rotate-right"></i></button>
                </div>
                <div class="preview-3d-label">Drag to rotate &bull; Click Spin for auto-rotate</div>
                <!-- Hidden canvas for updatePreview compatibility -->
                <canvas id="previewCanvas" style="display:none;" width="400" height="500"></canvas>
                <div id="previewPlaceholder" style="display:none;"></div>
            </div>

            <!-- Price Auto Calculator -->
            <div class="preview-section">
                <h6><i class="fas fa-calculator me-1"></i> Price Estimate</h6>
                <div class="price-calculator">
                    <div class="price-row">
                        <span class="price-label">Apparel Base:</span>
                        <span class="price-value" id="priceBase">₱0</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Print Size:</span>
                        <select class="price-select" id="printSizeSelect" onchange="calculatePrice()">
                            <option value="small">Small (4×4")</option>
                            <option value="medium" selected>Medium (8×8")</option>
                            <option value="large">Large (12×12")</option>
                            <option value="full">Full Print</option>
                        </select>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Print Size Cost:</span>
                        <span class="price-value" id="pricePrintSize">₱0</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Colors Used:</span>
                        <span class="price-value" id="priceColorsCount">1</span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Color Cost:</span>
                        <span class="price-value" id="priceColors">₱0</span>
                    </div>
                    <div class="price-row total">
                        <span>Estimated Total:</span>
                        <span id="priceTotal">₱0</span>
                    </div>
                </div>
            </div>

            <!-- Design Info -->
            <div class="preview-section">
                <h6><i class="fas fa-info-circle me-1"></i> Design Info</h6>
                <div style="font-size:0.82rem; color:#666;">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Apparel:</span>
                        <strong id="infoType">T-Shirt</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Color:</span>
                        <strong id="infoColor">White</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Elements:</span>
                        <strong id="infoElements">0</strong>
                    </div>
                </div>
            </div>

            <!-- Size & Quantity -->
            <div class="preview-section">
                <h6><i class="fas fa-ruler me-1"></i> Size & Quantity</h6>
                <div style="margin-bottom:0.5rem;">
                    <label style="font-size:0.78rem; color:#888; display:block; margin-bottom:0.3rem;">Size</label>
                    <select class="price-select" id="sizeSelect" style="width:100%;" onchange="calculatePrice()">
                        <option value="XS">XS - Extra Small</option>
                        <option value="S">S - Small</option>
                        <option value="M" selected>M - Medium</option>
                        <option value="L">L - Large</option>
                        <option value="XL">XL - Extra Large</option>
                        <option value="2XL">2XL - Double Extra Large</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.78rem; color:#888; display:block; margin-bottom:0.3rem;">Quantity</label>
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <button type="button" onclick="adjustQty(-1)" style="width:32px;height:32px;border:1px solid #ddd;border-radius:8px;background:#fff;cursor:pointer;font-size:1rem;">−</button>
                        <input type="number" id="quantityInput" value="1" min="1" max="100" style="width:50px;text-align:center;padding:0.35rem;border:1px solid #ddd;border-radius:8px;font-size:0.85rem;" onchange="calculatePrice()">
                        <button type="button" onclick="adjustQty(1)" style="width:32px;height:32px;border:1px solid #ddd;border-radius:8px;background:#fff;cursor:pointer;font-size:1rem;">+</button>
                    </div>
                </div>
            </div>

            <!-- Discount Type -->
            <div class="preview-section">
                <h6><i class="fas fa-ticket-alt me-1"></i> Discount</h6>
                <select class="price-select" id="discountSelect" style="width:100%;" onchange="calculatePrice()">
                    <option value="regular">Regular (No Discount)</option>
                    <?php if ($user_discount === 'senior'): ?>
                    <option value="senior" selected>Senior Citizen (20% off)</option>
                    <?php endif; ?>
                    <?php if ($user_discount === 'pwd'): ?>
                    <option value="pwd" selected>PWD (20% off)</option>
                    <?php endif; ?>
                </select>
                <div id="discountRow" style="display:none; margin-top:0.5rem;">
                    <div class="price-row" style="color:#27ae60;">
                        <span class="price-label">Discount:</span>
                        <span class="price-value" id="priceDiscount" style="color:#27ae60;">-₱0</span>
                    </div>
                </div>
            </div>

            <!-- Notes & Submit -->
            <div class="submit-section">
                <h6 style="font-weight:700; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.5px; color:#888; margin-bottom:0.5rem;">
                    <i class="fas fa-sticky-note me-1"></i> Notes for Admin
                </h6>
                <textarea id="designNotes" placeholder="Any special instructions, preferred fabric, sizing notes..."></textarea>
                <button class="btn-submit-design" id="submitDesignBtn" onclick="submitDesign()">
                    <i class="fas fa-paper-plane"></i> Submit & Proceed to Order
                </button>
            </div>
        </div>
    </div>

    <!-- My Designs Section -->
    <div class="my-designs-section app-section-surface">
        <h3 style="font-weight:700; margin-bottom:1rem;"><i class="fas fa-palette me-2"></i>My Designs</h3>
        <div class="designs-grid" id="myDesignsGrid">
            <div class="text-center py-4 text-muted" id="noDesignsMsg">
                <i class="fas fa-palette" style="font-size:2rem; margin-bottom:0.5rem; display:block; opacity:0.3;"></i>
                <p>No designs yet. Create your first custom design above!</p>
            </div>
        </div>
    </div>
</div>

<script>
// ===== Design Tool State =====
const state = {
    currentTool: 'brush',
    brushColor: '#000000',
    brushSize: 4,
    apparelType: 'tshirt',
    apparelColor: '#FFFFFF',
    isDrawing: false,
    isBold: false,
    isItalic: false,
    elements: [],
    undoStack: [],
    redoStack: [],
    selectedElement: null,
    zoom: 1,
    startX: 0,
    startY: 0,
    drawingShape: null,
    currentSide: 'front',
    // Separate data for each side
    frontCanvasData: null,
    backCanvasData: null,
    frontElements: [],
    backElements: [],
    frontUndoStack: [],
    backUndoStack: [],
    frontRedoStack: [],
    backRedoStack: []
};

const canvas = document.getElementById('designCanvas');
const ctx = canvas.getContext('2d');
const mockupContainer = document.getElementById('mockupContainer');
const elementsLayer = document.getElementById('elementsLayer');

// ===== Apparel Mockup SVGs =====
const mockups = {
    tshirt: `
        <!-- T-Shirt shape -->
        <path d="M120,60 L100,60 Q60,60 50,100 L30,160 L70,180 L90,120 L90,420 Q90,440 110,440 L290,440 Q310,440 310,420 L310,120 L330,180 L370,160 L350,100 Q340,60 300,60 L280,60 Q270,40 250,30 L200,20 L150,30 Q130,40 120,60 Z"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Collar -->
        <ellipse cx="200" cy="55" rx="55" ry="20" fill="none" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Sleeves detail -->
        <path d="M90,120 L70,180 L30,160" fill="none" stroke="DETAIL_COLOR" stroke-width="1"/>
        <path d="M310,120 L330,180 L370,160" fill="none" stroke="DETAIL_COLOR" stroke-width="1"/>
    `,
    hoodie: `
        <!-- Hoodie shape -->
        <path d="M120,80 L100,80 Q60,80 50,120 L20,200 L70,210 L80,140 L80,420 Q80,440 100,440 L300,440 Q320,440 320,420 L320,140 L330,210 L380,200 L350,120 Q340,80 300,80 L280,80 Q270,55 250,45 L200,35 L150,45 Q130,55 120,80 Z"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Hood -->
        <path d="M120,80 Q110,30 160,15 L200,10 L240,15 Q290,30 280,80"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Pocket -->
        <rect x="140" y="300" width="120" height="60" rx="8" fill="none" stroke="DETAIL_COLOR" stroke-width="1.5"/>
        <!-- Front zipper or kangaroo pocket line -->
        <line x1="200" y1="90" x2="200" y2="300" stroke="DETAIL_COLOR" stroke-width="1"/>
        <!-- Drawstrings -->
        <line x1="180" y1="85" x2="175" y2="130" stroke="STROKE_COLOR" stroke-width="1.5"/>
        <line x1="220" y1="85" x2="225" y2="130" stroke="STROKE_COLOR" stroke-width="1.5"/>
    `,
    polo: `
        <!-- Polo shape -->
        <path d="M120,65 L100,65 Q60,65 50,105 L30,170 L75,185 L90,120 L90,420 Q90,440 110,440 L290,440 Q310,440 310,420 L310,120 L325,185 L370,170 L350,105 Q340,65 300,65 L280,65 Q270,45 250,35 L200,25 L150,35 Q130,45 120,65 Z"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Collar (polo style) -->
        <path d="M145,55 Q155,35 200,30 Q245,35 255,55 L250,70 Q240,50 200,45 Q160,50 150,70 Z"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Button placket -->
        <line x1="200" y1="60" x2="200" y2="160" stroke="STROKE_COLOR" stroke-width="1.5"/>
        <circle cx="200" cy="80" r="3" fill="STROKE_COLOR"/>
        <circle cx="200" cy="105" r="3" fill="STROKE_COLOR"/>
        <circle cx="200" cy="130" r="3" fill="STROKE_COLOR"/>
    `
};

// Back view mockup SVGs
const mockupsBack = {
    tshirt: `
        <!-- T-Shirt back shape -->
        <path d="M120,60 L100,60 Q60,60 50,100 L30,160 L70,180 L90,120 L90,420 Q90,440 110,440 L290,440 Q310,440 310,420 L310,120 L330,180 L370,160 L350,100 Q340,60 300,60 L280,60 Q270,40 250,30 L200,20 L150,30 Q130,40 120,60 Z"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Back neckline -->
        <path d="M145,55 Q170,65 200,67 Q230,65 255,55" fill="none" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Sleeves detail -->
        <path d="M90,120 L70,180 L30,160" fill="none" stroke="DETAIL_COLOR" stroke-width="1"/>
        <path d="M310,120 L330,180 L370,160" fill="none" stroke="DETAIL_COLOR" stroke-width="1"/>
    `,
    hoodie: `
        <!-- Hoodie back shape -->
        <path d="M120,80 L100,80 Q60,80 50,120 L20,200 L70,210 L80,140 L80,420 Q80,440 100,440 L300,440 Q320,440 320,420 L320,140 L330,210 L380,200 L350,120 Q340,80 300,80 L280,80 Q270,55 250,45 L200,35 L150,45 Q130,55 120,80 Z"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Hood (back view) -->
        <path d="M120,80 Q110,30 160,15 L200,10 L240,15 Q290,30 280,80"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Hood center seam -->
        <line x1="200" y1="10" x2="200" y2="80" stroke="DETAIL_COLOR" stroke-width="1.5"/>
    `,
    polo: `
        <!-- Polo back shape -->
        <path d="M120,65 L100,65 Q60,65 50,105 L30,170 L75,185 L90,120 L90,420 Q90,440 110,440 L290,440 Q310,440 310,420 L310,120 L325,185 L370,170 L350,105 Q340,65 300,65 L280,65 Q270,45 250,35 L200,25 L150,35 Q130,45 120,65 Z"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Back collar -->
        <path d="M145,55 Q155,35 200,30 Q245,35 255,55 L250,70 Q240,50 200,45 Q160,50 150,70 Z"
              fill="APPAREL_COLOR" stroke="STROKE_COLOR" stroke-width="2"/>
        <!-- Back yoke seam -->
        <path d="M90,140 Q200,120 310,140" fill="none" stroke="DETAIL_COLOR" stroke-width="1"/>
    `
};

// Design area positioning on mockup
const designAreas = {
    tshirt: { x: 130, y: 120, w: 140, h: 180 },
    hoodie: { x: 130, y: 130, w: 140, h: 160 },
    polo:   { x: 130, y: 120, w: 140, h: 180 }
};

// ===== Init =====
function init() {
    updateMockup();
    positionCanvas();
    setupCanvasEvents();
    loadMyDesigns();
    saveCanvasState();
}

function getContrastStroke(hexColor) {
    const r = parseInt(hexColor.slice(1,3), 16);
    const g = parseInt(hexColor.slice(3,5), 16);
    const b = parseInt(hexColor.slice(5,7), 16);
    const brightness = (r * 299 + g * 587 + b * 114) / 1000;
    if (brightness < 128) {
        return { stroke: 'rgba(255,255,255,0.25)', detail: 'rgba(255,255,255,0.15)' };
    }
    return { stroke: 'rgba(0,0,0,0.13)', detail: 'rgba(0,0,0,0.07)' };
}

function updateMockup() {
    const svg = document.getElementById('mockupSvg');
    const colors = getContrastStroke(state.apparelColor);
    const mockupSet = state.currentSide === 'front' ? mockups : mockupsBack;
    const mockupSvg = mockupSet[state.apparelType]
        .replace(/APPAREL_COLOR/g, state.apparelColor)
        .replace(/STROKE_COLOR/g, colors.stroke)
        .replace(/DETAIL_COLOR/g, colors.detail);
    svg.innerHTML = mockupSvg;
}

function positionCanvas() {
    const area = designAreas[state.apparelType];
    canvas.style.left = area.x + 'px';
    canvas.style.top = area.y + 'px';
    canvas.width = area.w;
    canvas.height = area.h;
    canvas.style.width = area.w + 'px';
    canvas.style.height = area.h + 'px';
    
    // Restore drawing
    restoreCanvasState();
}

// ===== Tool Selection =====
function setTool(tool) {
    state.currentTool = tool;
    document.querySelectorAll('.tool-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`.tool-btn[data-tool="${tool}"]`).classList.add('active');
    canvas.style.cursor = tool === 'eraser' ? 'cell' : 'crosshair';
}

function setApparelType(type) {
    state.apparelType = type;
    document.querySelectorAll('.apparel-type-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`.apparel-type-btn[data-type="${type}"]`).classList.add('active');
    
    const names = { tshirt: 'T-Shirt', hoodie: 'Hoodie', polo: 'Polo' };
    document.getElementById('infoType').textContent = names[type];
    
    // Reset both sides when changing apparel type
    state.frontCanvasData = null;
    state.backCanvasData = null;
    state.frontElements = [];
    state.backElements = [];
    state.frontUndoStack = [];
    state.backUndoStack = [];
    state.frontRedoStack = [];
    state.backRedoStack = [];
    state.undoStack = [];
    state.redoStack = [];
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    elementsLayer.innerHTML = '';
    state.elements = [];
    state.selectedElement = null;
    
    updateMockup();
    positionCanvas();
    saveCanvasState();
    updatePreview();
}

function setApparelColor(btn, color) {
    state.apparelColor = color;
    document.querySelectorAll('.apparel-color-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    
    const colorNames = {'#FFFFFF':'White','#000000':'Black','#001F3F':'Navy','#808080':'Gray','#FF4136':'Red','#2ECC40':'Green','#0074D9':'Blue','#FFDC00':'Yellow','#FF69B4':'Pink','#800000':'Maroon'};
    document.getElementById('infoColor').textContent = colorNames[color] || color;
    
    updateMockup();
    updatePreview();
}

// ===== Front/Back Side Switching =====
function switchSide(side) {
    if (state.currentSide === side) return;
    
    // Save current side's canvas data and elements
    saveCurrentSideData();
    
    // Switch side
    state.currentSide = side;
    
    // Update toggle buttons
    document.getElementById('frontSideBtn').classList.toggle('active', side === 'front');
    document.getElementById('backSideBtn').classList.toggle('active', side === 'back');
    
    // Restore the other side's data
    restoreSideData(side);
    
    // Update mockup SVG
    updateMockup();
    positionCanvas();
    updatePreview();
}

function saveCurrentSideData() {
    const dataUrl = canvas.toDataURL();
    const side = state.currentSide;
    
    if (side === 'front') {
        state.frontCanvasData = dataUrl;
        state.frontElements = Array.from(elementsLayer.children);
        state.frontUndoStack = [...state.undoStack];
        state.frontRedoStack = [...state.redoStack];
    } else {
        state.backCanvasData = dataUrl;
        state.backElements = Array.from(elementsLayer.children);
        state.backUndoStack = [...state.undoStack];
        state.backRedoStack = [...state.redoStack];
    }
}

function restoreSideData(side) {
    // Clear current canvas and elements
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    elementsLayer.innerHTML = '';
    state.elements = [];
    state.selectedElement = null;
    
    const savedData = side === 'front' ? state.frontCanvasData : state.backCanvasData;
    const savedElements = side === 'front' ? state.frontElements : state.backElements;
    
    // Restore undo/redo stacks
    state.undoStack = side === 'front' ? [...state.frontUndoStack] : [...state.backUndoStack];
    state.redoStack = side === 'front' ? [...state.frontRedoStack] : [...state.backRedoStack];
    
    // Restore canvas drawing
    if (savedData) {
        const img = new Image();
        img.onload = function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
        };
        img.src = savedData;
    }
    
    // Restore draggable elements
    if (savedElements && savedElements.length > 0) {
        savedElements.forEach(el => {
            elementsLayer.appendChild(el);
            state.elements.push(el);
        });
    }
    
    updateElementCount();
    
    // If no undo data exists yet for this side, initialize it
    if (state.undoStack.length === 0) {
        saveCanvasState();
    }
}

function setBrushColor(btn, color) {
    state.brushColor = color;
    document.querySelectorAll('.color-swatch-btn').forEach(b => b.classList.remove('active'));
    if (btn) btn.classList.add('active');
    document.getElementById('customColor').value = color;
}

function setBrushSize(size) {
    state.brushSize = parseInt(size);
    document.getElementById('brushSizeLabel').textContent = size;
}

function toggleBold() {
    state.isBold = !state.isBold;
    document.getElementById('boldBtn').classList.toggle('active', state.isBold);
}

function toggleItalic() {
    state.isItalic = !state.isItalic;
    document.getElementById('italicBtn').classList.toggle('active', state.isItalic);
}

// ===== Canvas Drawing =====
function setupCanvasEvents() {
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseleave', stopDrawing);
    
    // Touch support
    canvas.addEventListener('touchstart', (e) => { e.preventDefault(); startDrawing(getTouchEvent(e)); });
    canvas.addEventListener('touchmove', (e) => { e.preventDefault(); draw(getTouchEvent(e)); });
    canvas.addEventListener('touchend', (e) => { e.preventDefault(); stopDrawing(e); });
}

function getTouchEvent(e) {
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    return {
        offsetX: touch.clientX - rect.left,
        offsetY: touch.clientY - rect.top,
        preventDefault: () => {}
    };
}

function startDrawing(e) {
    state.isDrawing = true;
    const x = e.offsetX;
    const y = e.offsetY;
    state.startX = x;
    state.startY = y;
    
    if (state.currentTool === 'brush' || state.currentTool === 'eraser') {
        ctx.beginPath();
        ctx.moveTo(x, y);
        ctx.lineWidth = state.brushSize;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        
        if (state.currentTool === 'eraser') {
            ctx.globalCompositeOperation = 'destination-out';
            ctx.strokeStyle = 'rgba(0,0,0,1)';
        } else {
            ctx.globalCompositeOperation = 'source-over';
            ctx.strokeStyle = state.brushColor;
        }
    }
    
    if (['line', 'rect', 'circle'].includes(state.currentTool)) {
        state.drawingShape = ctx.getImageData(0, 0, canvas.width, canvas.height);
    }
}

function draw(e) {
    if (!state.isDrawing) return;
    
    const x = e.offsetX;
    const y = e.offsetY;
    
    if (state.currentTool === 'brush' || state.currentTool === 'eraser') {
        ctx.lineTo(x, y);
        ctx.stroke();
    } else if (state.currentTool === 'line') {
        ctx.putImageData(state.drawingShape, 0, 0);
        ctx.beginPath();
        ctx.moveTo(state.startX, state.startY);
        ctx.lineTo(x, y);
        ctx.strokeStyle = state.brushColor;
        ctx.lineWidth = state.brushSize;
        ctx.stroke();
    } else if (state.currentTool === 'rect') {
        ctx.putImageData(state.drawingShape, 0, 0);
        ctx.beginPath();
        ctx.strokeStyle = state.brushColor;
        ctx.lineWidth = state.brushSize;
        ctx.strokeRect(state.startX, state.startY, x - state.startX, y - state.startY);
    } else if (state.currentTool === 'circle') {
        ctx.putImageData(state.drawingShape, 0, 0);
        const rx = Math.abs(x - state.startX) / 2;
        const ry = Math.abs(y - state.startY) / 2;
        const cx = state.startX + (x - state.startX) / 2;
        const cy = state.startY + (y - state.startY) / 2;
        ctx.beginPath();
        ctx.ellipse(cx, cy, rx, ry, 0, 0, Math.PI * 2);
        ctx.strokeStyle = state.brushColor;
        ctx.lineWidth = state.brushSize;
        ctx.stroke();
    }
    
    updatePreview();
}

function stopDrawing(e) {
    if (state.isDrawing) {
        state.isDrawing = false;
        ctx.globalCompositeOperation = 'source-over';
        saveCanvasState();
        updatePreview();
    }
}

// ===== Text Tool =====
function addTextToCanvas() {
    const text = document.getElementById('textInput').value.trim();
    if (!text) return;
    
    const fontFamily = document.getElementById('fontFamily').value;
    const fontSize = parseInt(document.getElementById('fontSize').value) || 24;
    
    let fontStyle = '';
    if (state.isItalic) fontStyle += 'italic ';
    if (state.isBold) fontStyle += 'bold ';
    
    // Create draggable text element
    const el = document.createElement('div');
    el.className = 'draggable-element';
    el.style.pointerEvents = 'auto';
    el.innerHTML = `
        <span style="font-family:'${fontFamily}'; font-size:${fontSize}px; color:${state.brushColor}; ${state.isBold ? 'font-weight:bold;' : ''} ${state.isItalic ? 'font-style:italic;' : ''} white-space:nowrap; user-select:none;">${escapeHtml(text)}</span>
        <div class="resize-handle"></div>
        <div class="delete-handle">&times;</div>
    `;
    
    const area = designAreas[state.apparelType];
    el.style.left = (area.x + 10) + 'px';
    el.style.top = (area.y + 10) + 'px';
    el.dataset.type = 'text';
    
    setupDraggable(el);
    elementsLayer.appendChild(el);
    state.elements.push(el);
    updateElementCount();
    
    document.getElementById('textInput').value = '';
    updatePreview();
}

// ===== Image Upload =====
function handleImageUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    if (file.size > 5 * 1024 * 1024) {
        showToast('Image must be less than 5MB', 'error');
        return;
    }
    
    if (!['image/png', 'image/jpeg', 'image/webp'].includes(file.type)) {
        showToast('Only PNG, JPG, and WEBP images are allowed', 'error');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            const area = designAreas[state.apparelType];
            const maxW = area.w - 20;
            const maxH = area.h - 20;
            let w = img.width;
            let h = img.height;
            
            if (w > maxW) { h = h * (maxW / w); w = maxW; }
            if (h > maxH) { w = w * (maxH / h); h = maxH; }
            
            const el = document.createElement('div');
            el.className = 'draggable-element';
            el.style.pointerEvents = 'auto';
            el.innerHTML = `
                <img src="${e.target.result}" style="width:${w}px; height:${h}px; display:block; user-select:none; pointer-events:none;">
                <div class="resize-handle"></div>
                <div class="delete-handle">&times;</div>
            `;
            
            el.style.left = (area.x + 10) + 'px';
            el.style.top = (area.y + 10) + 'px';
            el.dataset.type = 'image';
            
            setupDraggable(el);
            elementsLayer.appendChild(el);
            state.elements.push(el);
            updateElementCount();
            updatePreview();
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
    input.value = '';
}

// ===== Drag & Resize =====
function setupDraggable(el) {
    let isDragging = false;
    let isResizing = false;
    let startX, startY, startLeft, startTop, startW, startH;
    
    el.addEventListener('mousedown', function(e) {
        if (e.target.classList.contains('delete-handle')) {
            el.remove();
            state.elements = state.elements.filter(item => item !== el);
            updateElementCount();
            updatePreview();
            return;
        }
        
        // Select this element
        document.querySelectorAll('.draggable-element').forEach(d => d.classList.remove('selected'));
        el.classList.add('selected');
        state.selectedElement = el;
        
        if (e.target.classList.contains('resize-handle')) {
            isResizing = true;
            const content = el.querySelector('img, span');
            startW = content.offsetWidth;
            startH = content.offsetHeight;
            startX = e.clientX;
            startY = e.clientY;
        } else {
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            startLeft = el.offsetLeft;
            startTop = el.offsetTop;
        }
        
        e.stopPropagation();
    });
    
    document.addEventListener('mousemove', function(e) {
        if (isDragging) {
            el.style.left = (startLeft + (e.clientX - startX)) + 'px';
            el.style.top = (startTop + (e.clientY - startY)) + 'px';
            updatePreview();
        }
        if (isResizing) {
            const content = el.querySelector('img, span');
            const newW = Math.max(20, startW + (e.clientX - startX));
            const ratio = newW / startW;
            content.style.width = newW + 'px';
            if (content.tagName === 'IMG') {
                content.style.height = (startH * ratio) + 'px';
            } else {
                content.style.fontSize = (parseFloat(content.style.fontSize) * ratio) + 'px';
            }
            updatePreview();
        }
    });
    
    document.addEventListener('mouseup', function() {
        isDragging = false;
        isResizing = false;
    });
}

// Click on canvas wrapper to deselect elements
document.getElementById('canvasWrapper').addEventListener('click', function(e) {
    if (e.target === this || e.target.id === 'canvasWrapper') {
        document.querySelectorAll('.draggable-element').forEach(d => d.classList.remove('selected'));
        state.selectedElement = null;
    }
});

function deleteSelected() {
    if (state.selectedElement) {
        state.selectedElement.remove();
        state.elements = state.elements.filter(item => item !== state.selectedElement);
        state.selectedElement = null;
        updateElementCount();
        updatePreview();
    }
}

// ===== Undo / Redo =====
function saveCanvasState() {
    state.undoStack.push(canvas.toDataURL());
    if (state.undoStack.length > 30) state.undoStack.shift();
    state.redoStack = [];
}

function restoreCanvasState() {
    if (state.undoStack.length > 0) {
        const img = new Image();
        img.onload = function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
        };
        img.src = state.undoStack[state.undoStack.length - 1];
    }
}

function undoAction() {
    if (state.undoStack.length > 1) {
        state.redoStack.push(state.undoStack.pop());
        const img = new Image();
        img.onload = function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
            updatePreview();
        };
        img.src = state.undoStack[state.undoStack.length - 1];
    }
}

function redoAction() {
    if (state.redoStack.length > 0) {
        const data = state.redoStack.pop();
        state.undoStack.push(data);
        const img = new Image();
        img.onload = function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
            updatePreview();
        };
        img.src = data;
    }
}

function clearCanvas() {
    if (!confirm('Clear all drawing and elements?')) return;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    elementsLayer.innerHTML = '';
    state.elements = [];
    state.selectedElement = null;
    saveCanvasState();
    updateElementCount();
    updatePreview();
}

// ===== Zoom =====
function zoomIn() {
    state.zoom = Math.min(state.zoom + 0.1, 2);
    mockupContainer.style.transform = `scale(${state.zoom})`;
}

function zoomOut() {
    state.zoom = Math.max(state.zoom - 0.1, 0.5);
    mockupContainer.style.transform = `scale(${state.zoom})`;
}

function resetZoom() {
    state.zoom = 1;
    mockupContainer.style.transform = 'scale(1)';
}

// ===== Live Preview =====
function updatePreview() {
    const frontCanvas = document.getElementById('previewCanvasFront');
    const backCanvas = document.getElementById('previewCanvasBack');
    const frontCtx = frontCanvas.getContext('2d');
    const backCtx = backCanvas.getContext('2d');

    frontCanvas.width = 400;
    frontCanvas.height = 500;
    backCanvas.width = 400;
    backCanvas.height = 500;

    // Draw front side
    drawPreviewSide(frontCtx, 'front');
    // Draw back side
    drawPreviewSide(backCtx, 'back');

    // Update price when preview updates
    calculatePrice();
}

function drawPreviewSide(pCtx, side) {
    pCtx.clearRect(0, 0, 400, 500);
    pCtx.fillStyle = '#f0f0f0';
    pCtx.fillRect(0, 0, 400, 500);

    const colors = getContrastStroke(state.apparelColor);
    const mockupSet = side === 'front' ? mockups : mockupsBack;
    const svgStr = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 500">${mockupSet[state.apparelType].replace(/APPAREL_COLOR/g, state.apparelColor).replace(/STROKE_COLOR/g, colors.stroke).replace(/DETAIL_COLOR/g, colors.detail)}</svg>`;
    const svgBlob = new Blob([svgStr], { type: 'image/svg+xml;charset=utf-8' });
    const svgUrl = URL.createObjectURL(svgBlob);
    const svgImg = new Image();

    svgImg.onload = function() {
        pCtx.drawImage(svgImg, 0, 0, 400, 500);
        URL.revokeObjectURL(svgUrl);

        const area = designAreas[state.apparelType];

        if (side === state.currentSide) {
            // Active side: draw directly from the live canvas and elements
            pCtx.drawImage(canvas, area.x, area.y, area.w, area.h);
            renderElementsToCanvas(pCtx);
        } else {
            // Inactive side: draw from saved data
            const savedData = side === 'front' ? state.frontCanvasData : state.backCanvasData;
            const savedElements = side === 'front' ? state.frontElements : state.backElements;
            
            if (savedData) {
                const savedImg = new Image();
                savedImg.onload = function() {
                    pCtx.drawImage(savedImg, area.x, area.y, area.w, area.h);
                    // Render saved elements
                    if (savedElements && savedElements.length > 0) {
                        renderSavedElementsToCanvas(pCtx, savedElements);
                    }
                };
                savedImg.src = savedData;
            } else if (savedElements && savedElements.length > 0) {
                renderSavedElementsToCanvas(pCtx, savedElements);
            }
        }
    };
    svgImg.src = svgUrl;
}

function renderElementsToCanvas(targetCtx) {
    state.elements.forEach(el => {
        const left = parseInt(el.style.left) || 0;
        const top = parseInt(el.style.top) || 0;
        
        if (el.dataset.type === 'text') {
            const span = el.querySelector('span');
            if (span) {
                targetCtx.font = span.style.fontStyle + ' ' + span.style.fontWeight + ' ' + span.style.fontSize + ' ' + span.style.fontFamily;
                targetCtx.fillStyle = span.style.color;
                targetCtx.fillText(span.textContent, left, top + parseInt(span.style.fontSize));
            }
        } else if (el.dataset.type === 'image') {
            const img = el.querySelector('img');
            if (img && img.complete) {
                targetCtx.drawImage(img, left, top, parseInt(img.style.width), parseInt(img.style.height));
            }
        }
    });
}

function renderSavedElementsToCanvas(targetCtx, savedElements) {
    savedElements.forEach(el => {
        const left = parseInt(el.style.left) || 0;
        const top = parseInt(el.style.top) || 0;
        
        if (el.dataset.type === 'text') {
            const span = el.querySelector('span');
            if (span) {
                targetCtx.font = span.style.fontStyle + ' ' + span.style.fontWeight + ' ' + span.style.fontSize + ' ' + span.style.fontFamily;
                targetCtx.fillStyle = span.style.color;
                targetCtx.fillText(span.textContent, left, top + parseInt(span.style.fontSize));
            }
        } else if (el.dataset.type === 'image') {
            const img = el.querySelector('img');
            if (img && img.complete) {
                targetCtx.drawImage(img, left, top, parseInt(img.style.width), parseInt(img.style.height));
            }
        }
    });
}

function updateElementCount() {
    document.getElementById('infoElements').textContent = state.elements.length;
}

// ===== Submit Design =====
function submitDesign() {
    const btn = document.getElementById('submitDesignBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
    
    // Save current side data first
    saveCurrentSideData();
    
    // Generate front image
    generateSideImage('front', function(frontImageData) {
        // Generate back image
        generateSideImage('back', function(backImageData) {
            const notes = document.getElementById('designNotes').value.trim();
            
            const formData = new FormData();
            formData.append('action', 'save');
            formData.append('product_type', state.apparelType);
            formData.append('design_image', frontImageData);
            formData.append('design_image_back', backImageData);
            formData.append('notes', notes);
            const priceData = {
                apparelColor: state.apparelColor,
                apparelType: state.apparelType,
                elementsCount: state.elements.length,
                printSize: document.getElementById('printSizeSelect')?.value || 'medium',
                colorsUsed: countColorsUsed(),
                size: document.getElementById('sizeSelect')?.value || 'M',
                quantity: parseInt(document.getElementById('quantityInput')?.value) || 1,
                discountType: document.getElementById('discountSelect')?.value || 'regular',
                baseCost: pricing.base[state.apparelType] || 350,
                printSizeCost: pricing.printSize[document.getElementById('printSizeSelect')?.value || 'medium'] || 100,
                colorCost: Math.max(0, (countColorsUsed() - 1)) * pricing.colorCost
            };

            formData.append('design_data', JSON.stringify(priceData));
            
            fetch('includes/custom-design-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Design submitted! Redirecting to order summary...', 'success');
                    const params = new URLSearchParams({
                        design_id: data.design_id,
                        type: priceData.apparelType,
                        color: priceData.apparelColor,
                        size: priceData.size,
                        qty: priceData.quantity,
                        print_size: priceData.printSize,
                        discount: priceData.discountType
                    });
                    setTimeout(() => {
                        window.location.href = 'custom-order-summary.php?' + params.toString();
                    }, 1000);
                } else {
                    showToast(data.message || 'Failed to submit design.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Network error. Please try again.', 'error');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit & Proceed to Order';
            });
        });
    });
}

function generateSideImage(side, callback) {
    const finalCanvas = document.createElement('canvas');
    finalCanvas.width = 400;
    finalCanvas.height = 500;
    const fctx = finalCanvas.getContext('2d');
    
    fctx.fillStyle = '#f0f0f0';
    fctx.fillRect(0, 0, 400, 500);
    
    const submitColors = getContrastStroke(state.apparelColor);
    const mockupSet = side === 'front' ? mockups : mockupsBack;
    const svgStr = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 500">${mockupSet[state.apparelType].replace(/APPAREL_COLOR/g, state.apparelColor).replace(/STROKE_COLOR/g, submitColors.stroke).replace(/DETAIL_COLOR/g, submitColors.detail)}</svg>`;
    const svgBlob = new Blob([svgStr], { type: 'image/svg+xml;charset=utf-8' });
    const svgUrl = URL.createObjectURL(svgBlob);
    const svgImg = new Image();
    
    svgImg.onload = function() {
        fctx.drawImage(svgImg, 0, 0, 400, 500);
        URL.revokeObjectURL(svgUrl);
        
        const area = designAreas[state.apparelType];
        
        if (side === state.currentSide) {
            // Active side: use live canvas
            fctx.drawImage(canvas, area.x, area.y, area.w, area.h);
            renderElementsToCanvas(fctx);
            callback(finalCanvas.toDataURL('image/png'));
        } else {
            // Saved side: restore from saved data
            const savedData = side === 'front' ? state.frontCanvasData : state.backCanvasData;
            const savedElements = side === 'front' ? state.frontElements : state.backElements;
            
            if (savedData) {
                const savedImg = new Image();
                savedImg.onload = function() {
                    fctx.drawImage(savedImg, area.x, area.y, area.w, area.h);
                    if (savedElements && savedElements.length > 0) {
                        renderSavedElementsToCanvas(fctx, savedElements);
                    }
                    callback(finalCanvas.toDataURL('image/png'));
                };
                savedImg.src = savedData;
            } else {
                if (savedElements && savedElements.length > 0) {
                    renderSavedElementsToCanvas(fctx, savedElements);
                }
                callback(finalCanvas.toDataURL('image/png'));
            }
        }
    };
    svgImg.src = svgUrl;
}

// ===== Load My Designs =====
function loadMyDesigns() {
    fetch('includes/custom-design-ajax.php?action=list')
    .then(r => r.json())
    .then(data => {
        const grid = document.getElementById('myDesignsGrid');
        const noMsg = document.getElementById('noDesignsMsg');
        
        if (data.success && data.designs.length > 0) {
            if (noMsg) noMsg.style.display = 'none';
            grid.innerHTML = data.designs.map(d => `
                <div class="design-card">
                    <img src="${escapeHtml(d.design_image)}" class="design-card-img" alt="Design" onerror="this.src='https://placehold.co/300x300/f0f0f0/999?text=Design'">
                    <div class="design-card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6>${escapeHtml(d.product_type.charAt(0).toUpperCase() + d.product_type.slice(1))}</h6>
                            <span class="design-status-badge ${escapeHtml(d.status)}">${escapeHtml(d.status)}</span>
                        </div>
                        ${d.notes ? `<p style="font-size:0.78rem; color:#888; margin:0.25rem 0 0;">${escapeHtml(d.notes.substring(0, 60))}${d.notes.length > 60 ? '...' : ''}</p>` : ''}
                        <small>${new Date(d.created_at).toLocaleDateString()}</small>
                        <a href="custom-order-summary.php?design_id=${d.id}&type=${encodeURIComponent(d.product_type)}&color=%23FFFFFF&size=M&qty=1&print_size=medium&discount=regular" class="btn btn-sm w-100 mt-2" style="background:var(--accent-green);color:#fff;border-radius:8px;font-size:0.78rem;font-weight:600;">
                            <i class="fas fa-shopping-cart"></i> Order Now
                        </a>
                    </div>
                </div>
            `).join('');
        } else {
            grid.innerHTML = '';
            if (noMsg) {
                noMsg.style.display = 'block';
                grid.appendChild(noMsg);
            }
        }
    })
    .catch(err => console.error('Failed to load designs:', err));
}

// ===== Utilities =====
function escapeHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

// ===== AI Design Suggestions =====
function getAISuggestions() {
    const prompt = document.getElementById('aiPromptInput').value.trim();
    if (!prompt) {
        showToast('Please describe your design idea first.', 'error');
        return;
    }

    const btn = document.getElementById('aiSuggestBtn');
    const container = document.getElementById('aiSuggestionsContainer');

    btn.disabled = true;
    container.innerHTML = '<div class="ai-loading"><i class="fas fa-spinner fa-spin"></i><p style="font-size:0.78rem; margin-top:0.4rem;">Generating ideas...</p></div>';

    fetch('includes/design-ai-suggest.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ prompt: prompt, apparel_type: state.apparelType })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success && data.suggestions) {
            renderAISuggestions(data.suggestions);
        } else {
            container.innerHTML = `<p style="font-size:0.78rem; color:#c0392b; margin-top:0.5rem;">${escapeHtml(data.error || 'Failed to generate suggestions.')}</p>`;
        }
    })
    .catch(() => {
        container.innerHTML = '<p style="font-size:0.78rem; color:#c0392b; margin-top:0.5rem;">Network error. Please try again.</p>';
    })
    .finally(() => {
        btn.disabled = false;
    });
}

function renderAISuggestions(suggestions) {
    const container = document.getElementById('aiSuggestionsContainer');
    container.innerHTML = '<div class="ai-suggestions-list">' + suggestions.map((s, i) => `
        <div class="ai-suggestion-card" onclick="applyAISuggestion(${i})">
            <h6><i class="fas fa-lightbulb me-1" style="color:#f0c040;"></i>${escapeHtml(s.name || 'Suggestion ' + (i+1))}</h6>
            <p>${escapeHtml(s.description || '')}</p>
            ${s.colors && s.colors.length ? `
                <div class="ai-suggestion-colors">
                    ${s.colors.map(c => `<span style="background:${escapeHtml(c)}" title="${escapeHtml(c)}" onclick="event.stopPropagation(); setBrushColor(null, '${escapeHtml(c)}')"></span>`).join('')}
                </div>
            ` : ''}
            ${s.placement ? `<p style="font-size:0.7rem; margin:0;"><strong>Placement:</strong> ${escapeHtml(s.placement)}</p>` : ''}
            ${s.tip ? `<div class="ai-suggestion-tip"><i class="fas fa-star me-1"></i>${escapeHtml(s.tip)}</div>` : ''}
            <button class="ai-apply-btn" onclick="event.stopPropagation(); applyAISuggestion(${i})">
                <i class="fas fa-paint-brush me-1"></i> Apply Design
            </button>
        </div>
    `).join('') + '</div>';

    // Store suggestions for applying
    window._aiSuggestions = suggestions;
}

function applyAISuggestion(index) {
    const s = window._aiSuggestions?.[index];
    if (!s) return;

    // Apply first suggested color as brush color
    if (s.colors && s.colors.length > 0) {
        setBrushColor(null, s.colors[0]);
        document.getElementById('customColor').value = s.colors[0];
    }

    // Auto-draw the design on canvas
    autoDrawDesign(s);

    showToast(`Applied "${s.name}" design to canvas!`, 'success');
}

// ===== Auto Design Drawing Engine =====
function autoDrawDesign(suggestion) {
    const colors = suggestion.colors || ['#333333', '#FFFFFF'];
    const name = (suggestion.name || '').toLowerCase();
    const desc = (suggestion.description || '').toLowerCase();
    const w = canvas.width;
    const h = canvas.height;

    ctx.save();

    // Detect theme from suggestion name/description keywords
    const theme = detectDesignTheme(name, desc);

    // Draw based on detected theme
    switch(theme) {
        case 'floral':     drawFloralDesign(colors, w, h); break;
        case 'geometric':  drawGeometricDesign(colors, w, h); break;
        case 'minimalist': drawMinimalistDesign(colors, w, h); break;
        case 'vintage':    drawVintageDesign(colors, w, h); break;
        case 'abstract':   drawAbstractDesign(colors, w, h); break;
        case 'streetwear': drawStreetwearDesign(colors, w, h); break;
        case 'nature':     drawNatureDesign(colors, w, h); break;
        case 'typography': drawTypographyDesign(colors, w, h, suggestion.name); break;
        default:           drawDefaultDesign(colors, w, h, suggestion.name); break;
    }

    ctx.restore();
    saveCanvasState();
    updatePreview();
}

function detectDesignTheme(name, desc) {
    const text = name + ' ' + desc;
    const themes = {
        'floral':     ['floral', 'flower', 'bloom', 'botanical', 'petal', 'garden', 'rose', 'tropical', 'leaf', 'leaves'],
        'geometric':  ['geometric', 'geometry', 'prism', 'grid', 'polygon', 'triangle', 'hexagon', 'sacred', 'shape'],
        'minimalist': ['minimalist', 'minimal', 'clean line', 'negative space', 'simple', 'subtle'],
        'vintage':    ['vintage', 'retro', 'badge', 'faded', '70s', '80s', 'old school', 'classic', 'nostalgic', 'sunset'],
        'abstract':   ['abstract', 'splash', 'ink blot', 'color block', 'paint', 'watercolor', 'expressionist'],
        'streetwear': ['street', 'urban', 'graffiti', 'glitch', 'tag', 'hype', 'edge', 'drip'],
        'nature':     ['mountain', 'ocean', 'wave', 'forest', 'tree', 'wild', 'nature', 'outdoor', 'adventure', 'sea'],
        'typography': ['type only', 'typograph', 'lettering', 'font', 'text', 'statement', 'word'],
    };
    for (const [theme, keywords] of Object.entries(themes)) {
        for (const kw of keywords) {
            if (text.includes(kw)) return theme;
        }
    }
    return 'default';
}

// --- Floral Design ---
function drawFloralDesign(colors, w, h) {
    const cx = w / 2, cy = h / 2 - 10;

    // Draw main flower
    drawFlower(cx, cy, 28, 6, colors[0], colors[1] || '#FFFFFF');

    // Smaller accent flowers
    drawFlower(cx - 40, cy - 30, 14, 5, colors[2] || colors[0], colors[1] || '#FFFFFF');
    drawFlower(cx + 38, cy - 25, 12, 5, colors[2] || colors[0], colors[1] || '#FFFFFF');
    drawFlower(cx - 25, cy + 40, 10, 5, colors[3] || colors[0], colors[1] || '#FFFFFF');
    drawFlower(cx + 30, cy + 35, 11, 5, colors[2] || colors[0], colors[1] || '#FFFFFF');

    // Stems and leaves
    ctx.strokeStyle = colors[2] || '#3E5C50';
    ctx.lineWidth = 1.5;
    ctx.beginPath();
    ctx.moveTo(cx, cy + 28); ctx.quadraticCurveTo(cx - 10, cy + 60, cx - 5, cy + 80);
    ctx.stroke();
    ctx.moveTo(cx - 40, cy - 16); ctx.quadraticCurveTo(cx - 35, cy + 10, cx - 10, cy + 50);
    ctx.stroke();

    // Leaves
    drawLeaf(cx + 10, cy + 50, 12, 0.3, colors[2] || '#3E5C50');
    drawLeaf(cx - 20, cy + 35, 10, -0.4, colors[2] || '#3E5C50');
}

function drawFlower(x, y, radius, petals, petalColor, centerColor) {
    for (let i = 0; i < petals; i++) {
        const angle = (Math.PI * 2 / petals) * i;
        const px = x + Math.cos(angle) * radius * 0.6;
        const py = y + Math.sin(angle) * radius * 0.6;
        ctx.beginPath();
        ctx.ellipse(px, py, radius * 0.55, radius * 0.3, angle, 0, Math.PI * 2);
        ctx.fillStyle = petalColor;
        ctx.globalAlpha = 0.8;
        ctx.fill();
        ctx.globalAlpha = 1;
    }
    // Center
    ctx.beginPath();
    ctx.arc(x, y, radius * 0.25, 0, Math.PI * 2);
    ctx.fillStyle = centerColor;
    ctx.fill();
}

function drawLeaf(x, y, size, angle, color) {
    ctx.save();
    ctx.translate(x, y);
    ctx.rotate(angle);
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.quadraticCurveTo(size * 0.6, -size * 0.5, size, 0);
    ctx.quadraticCurveTo(size * 0.6, size * 0.5, 0, 0);
    ctx.fillStyle = color;
    ctx.globalAlpha = 0.7;
    ctx.fill();
    ctx.globalAlpha = 1;
    ctx.restore();
}

// --- Geometric Design ---
function drawGeometricDesign(colors, w, h) {
    const cx = w / 2, cy = h / 2 - 10;

    // Outer circle
    ctx.beginPath();
    ctx.arc(cx, cy, 55, 0, Math.PI * 2);
    ctx.strokeStyle = colors[0];
    ctx.lineWidth = 2;
    ctx.stroke();

    // Inner triangles
    for (let i = 0; i < 3; i++) {
        const angle = (Math.PI * 2 / 3) * i - Math.PI / 2;
        ctx.beginPath();
        for (let j = 0; j < 3; j++) {
            const a = angle + (Math.PI * 2 / 3) * j;
            const px = cx + Math.cos(a) * 40;
            const py = cy + Math.sin(a) * 40;
            j === 0 ? ctx.moveTo(px, py) : ctx.lineTo(px, py);
        }
        ctx.closePath();
        ctx.strokeStyle = colors[i % colors.length];
        ctx.lineWidth = 1.5;
        ctx.stroke();
    }

    // Center hexagon
    ctx.beginPath();
    for (let i = 0; i < 6; i++) {
        const a = (Math.PI / 3) * i - Math.PI / 6;
        const px = cx + Math.cos(a) * 18;
        const py = cy + Math.sin(a) * 18;
        i === 0 ? ctx.moveTo(px, py) : ctx.lineTo(px, py);
    }
    ctx.closePath();
    ctx.fillStyle = colors[1] || colors[0];
    ctx.globalAlpha = 0.3;
    ctx.fill();
    ctx.globalAlpha = 1;
    ctx.strokeStyle = colors[0];
    ctx.lineWidth = 1.5;
    ctx.stroke();

    // Radiating lines
    for (let i = 0; i < 12; i++) {
        const a = (Math.PI / 6) * i;
        ctx.beginPath();
        ctx.moveTo(cx + Math.cos(a) * 20, cy + Math.sin(a) * 20);
        ctx.lineTo(cx + Math.cos(a) * 55, cy + Math.sin(a) * 55);
        ctx.strokeStyle = colors[2] || colors[0];
        ctx.globalAlpha = 0.25;
        ctx.lineWidth = 0.8;
        ctx.stroke();
        ctx.globalAlpha = 1;
    }

    // Small dots at intersections
    for (let i = 0; i < 6; i++) {
        const a = (Math.PI / 3) * i;
        ctx.beginPath();
        ctx.arc(cx + Math.cos(a) * 40, cy + Math.sin(a) * 40, 3, 0, Math.PI * 2);
        ctx.fillStyle = colors[1] || '#FFFFFF';
        ctx.fill();
    }
}

// --- Minimalist Design ---
function drawMinimalistDesign(colors, w, h) {
    const cx = w / 2, cy = h / 2 - 20;

    // Simple continuous line drawing — abstract face/shape
    ctx.strokeStyle = colors[0];
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    ctx.beginPath();
    ctx.moveTo(cx - 20, cy - 15);
    ctx.quadraticCurveTo(cx - 25, cy - 30, cx - 10, cy - 35);
    ctx.quadraticCurveTo(cx + 5, cy - 40, cx + 15, cy - 30);
    ctx.quadraticCurveTo(cx + 25, cy - 20, cx + 20, cy - 5);
    ctx.quadraticCurveTo(cx + 15, cy + 10, cx, cy + 15);
    ctx.quadraticCurveTo(cx - 15, cy + 20, cx - 20, cy + 5);
    ctx.quadraticCurveTo(cx - 25, cy - 5, cx - 20, cy - 15);
    ctx.stroke();

    // Single accent dot
    if (colors[2]) {
        ctx.beginPath();
        ctx.arc(cx + 5, cy - 20, 4, 0, Math.PI * 2);
        ctx.fillStyle = colors[2];
        ctx.fill();
    }

    // Thin horizontal line below
    ctx.beginPath();
    ctx.moveTo(cx - 35, cy + 40);
    ctx.lineTo(cx + 35, cy + 40);
    ctx.strokeStyle = colors[0];
    ctx.globalAlpha = 0.3;
    ctx.lineWidth = 1;
    ctx.stroke();
    ctx.globalAlpha = 1;
}

// --- Vintage/Retro Design ---
function drawVintageDesign(colors, w, h) {
    const cx = w / 2, cy = h / 2 - 5;

    // Outer badge circle
    ctx.beginPath();
    ctx.arc(cx, cy, 55, 0, Math.PI * 2);
    ctx.strokeStyle = colors[0] || '#2C1810';
    ctx.lineWidth = 2.5;
    ctx.stroke();

    // Inner circle
    ctx.beginPath();
    ctx.arc(cx, cy, 48, 0, Math.PI * 2);
    ctx.strokeStyle = colors[0] || '#2C1810';
    ctx.lineWidth = 1;
    ctx.stroke();

    // Fill inside with subtle color
    ctx.beginPath();
    ctx.arc(cx, cy, 47, 0, Math.PI * 2);
    ctx.fillStyle = colors[2] || '#F5E6D0';
    ctx.globalAlpha = 0.2;
    ctx.fill();
    ctx.globalAlpha = 1;

    // Star at center
    drawStar(cx, cy - 5, 12, 6, 5, colors[0] || '#2C1810');

    // "EST." text on top curve
    ctx.save();
    ctx.font = 'bold 8px Georgia, serif';
    ctx.fillStyle = colors[0] || '#2C1810';
    ctx.textAlign = 'center';
    ctx.fillText('★ EST. 2024 ★', cx, cy - 28);

    // Bottom text
    ctx.font = 'bold 7px Georgia, serif';
    ctx.fillText('PREMIUM QUALITY', cx, cy + 20);

    // Decorative lines
    ctx.beginPath();
    ctx.moveTo(cx - 35, cy + 10); ctx.lineTo(cx - 10, cy + 10);
    ctx.moveTo(cx + 10, cy + 10); ctx.lineTo(cx + 35, cy + 10);
    ctx.strokeStyle = colors[0] || '#2C1810';
    ctx.lineWidth = 1;
    ctx.stroke();
    ctx.restore();
}

function drawStar(cx, cy, outerR, innerR, points, color) {
    ctx.beginPath();
    for (let i = 0; i < points * 2; i++) {
        const r = i % 2 === 0 ? outerR : innerR;
        const a = (Math.PI / points) * i - Math.PI / 2;
        const px = cx + Math.cos(a) * r;
        const py = cy + Math.sin(a) * r;
        i === 0 ? ctx.moveTo(px, py) : ctx.lineTo(px, py);
    }
    ctx.closePath();
    ctx.fillStyle = color;
    ctx.fill();
}

// --- Abstract Design ---
function drawAbstractDesign(colors, w, h) {
    const cx = w / 2, cy = h / 2 - 10;

    // Random paint splashes
    for (let i = 0; i < 8; i++) {
        const x = cx + (Math.random() - 0.5) * 120;
        const y = cy + (Math.random() - 0.5) * 100;
        const r = 8 + Math.random() * 25;
        ctx.beginPath();
        ctx.arc(x, y, r, 0, Math.PI * 2);
        ctx.fillStyle = colors[i % colors.length];
        ctx.globalAlpha = 0.15 + Math.random() * 0.35;
        ctx.fill();
        ctx.globalAlpha = 1;
    }

    // Flowing lines
    for (let l = 0; l < 3; l++) {
        ctx.beginPath();
        ctx.moveTo(cx - 60, cy - 30 + l * 30);
        ctx.bezierCurveTo(
            cx - 20, cy - 50 + l * 25,
            cx + 20, cy + 10 + l * 20,
            cx + 60, cy - 20 + l * 30
        );
        ctx.strokeStyle = colors[l % colors.length];
        ctx.lineWidth = 2;
        ctx.globalAlpha = 0.7;
        ctx.stroke();
        ctx.globalAlpha = 1;
    }

    // Center accent shape
    ctx.beginPath();
    ctx.arc(cx, cy, 15, 0, Math.PI * 2);
    ctx.fillStyle = colors[0];
    ctx.globalAlpha = 0.5;
    ctx.fill();
    ctx.globalAlpha = 1;
}

// --- Streetwear Design ---
function drawStreetwearDesign(colors, w, h) {
    const cx = w / 2, cy = h / 2 - 15;

    // Bold background block
    ctx.fillStyle = colors[0] || '#1A1A1A';
    ctx.globalAlpha = 0.85;
    ctx.fillRect(cx - 55, cy - 40, 110, 70);
    ctx.globalAlpha = 1;

    // "HYPE" text
    ctx.font = 'bold 26px Impact, sans-serif';
    ctx.fillStyle = colors[1] || '#FFFFFF';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('HYPE', cx, cy - 10);

    // Accent line below
    ctx.fillStyle = colors[2] || '#FF0000';
    ctx.fillRect(cx - 40, cy + 18, 80, 4);

    // Small text
    ctx.font = '7px Arial, sans-serif';
    ctx.fillStyle = colors[1] || '#FFFFFF';
    ctx.fillText('LIMITED EDITION', cx, cy + 35);

    // Glitch effect lines
    ctx.globalAlpha = 0.3;
    ctx.fillStyle = colors[2] || '#FF0000';
    ctx.fillRect(cx - 58, cy - 15, 116, 2);
    ctx.fillStyle = colors[3] || '#00FFFF';
    ctx.fillRect(cx - 53, cy + 5, 106, 1.5);
    ctx.globalAlpha = 1;

    // Corner marks
    ctx.strokeStyle = colors[1] || '#FFFFFF';
    ctx.lineWidth = 1.5;
    // Top-left
    ctx.beginPath(); ctx.moveTo(cx - 55, cy - 32); ctx.lineTo(cx - 55, cy - 40); ctx.lineTo(cx - 47, cy - 40); ctx.stroke();
    // Bottom-right
    ctx.beginPath(); ctx.moveTo(cx + 55, cy + 22); ctx.lineTo(cx + 55, cy + 30); ctx.lineTo(cx + 47, cy + 30); ctx.stroke();
}

// --- Nature / Mountain Design ---
function drawNatureDesign(colors, w, h) {
    const cx = w / 2, cy = h / 2;

    // Circle frame
    ctx.beginPath();
    ctx.arc(cx, cy - 5, 50, 0, Math.PI * 2);
    ctx.strokeStyle = colors[0] || '#2C3E50';
    ctx.lineWidth = 1.5;
    ctx.stroke();

    // Clip to circle
    ctx.save();
    ctx.beginPath();
    ctx.arc(cx, cy - 5, 49, 0, Math.PI * 2);
    ctx.clip();

    // Sky gradient
    const grad = ctx.createLinearGradient(0, cy - 50, 0, cy + 40);
    grad.addColorStop(0, colors[3] || '#F39C12');
    grad.addColorStop(0.4, colors[2] || '#ECF0F1');
    grad.addColorStop(1, colors[0] || '#2C3E50');
    ctx.fillStyle = grad;
    ctx.fillRect(cx - 55, cy - 55, 110, 100);

    // Sun
    ctx.beginPath();
    ctx.arc(cx, cy - 30, 12, 0, Math.PI * 2);
    ctx.fillStyle = colors[3] || '#F39C12';
    ctx.globalAlpha = 0.8;
    ctx.fill();
    ctx.globalAlpha = 1;

    // Mountains
    ctx.beginPath();
    ctx.moveTo(cx - 55, cy + 20);
    ctx.lineTo(cx - 20, cy - 20);
    ctx.lineTo(cx + 5, cy + 5);
    ctx.lineTo(cx + 25, cy - 15);
    ctx.lineTo(cx + 55, cy + 20);
    ctx.closePath();
    ctx.fillStyle = colors[0] || '#2C3E50';
    ctx.fill();

    // Snow caps
    ctx.beginPath();
    ctx.moveTo(cx - 25, cy - 14);
    ctx.lineTo(cx - 20, cy - 20);
    ctx.lineTo(cx - 15, cy - 14);
    ctx.closePath();
    ctx.fillStyle = '#FFFFFF';
    ctx.globalAlpha = 0.7;
    ctx.fill();
    ctx.globalAlpha = 1;

    ctx.restore();

    // Text below
    ctx.font = '7px Arial, sans-serif';
    ctx.fillStyle = colors[0] || '#2C3E50';
    ctx.textAlign = 'center';
    ctx.fillText('ADVENTURE AWAITS', cx, cy + 55);
}

// --- Typography Design ---
function drawTypographyDesign(colors, w, h, title) {
    const cx = w / 2, cy = h / 2 - 10;
    const word = (title || 'CREATE').toUpperCase().split(' ')[0];

    // Main large text
    ctx.font = 'bold 32px Impact, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    // Shadow
    ctx.fillStyle = colors[1] || '#CCCCCC';
    ctx.globalAlpha = 0.3;
    ctx.fillText(word, cx + 2, cy + 2);
    ctx.globalAlpha = 1;

    // Main text
    ctx.fillStyle = colors[0] || '#333333';
    ctx.fillText(word, cx, cy);

    // Accent underline
    const textW = ctx.measureText(word).width;
    ctx.fillStyle = colors[2] || '#E74C3C';
    ctx.fillRect(cx - textW / 2, cy + 20, textW, 3);

    // Small decorative text
    ctx.font = '7px Arial, sans-serif';
    ctx.fillStyle = colors[0] || '#333333';
    ctx.globalAlpha = 0.5;
    ctx.fillText('— THREAD & PRESS HUB —', cx, cy + 35);
    ctx.globalAlpha = 1;
}

// --- Default Fallback Design ---
function drawDefaultDesign(colors, w, h, title) {
    const cx = w / 2, cy = h / 2 - 10;

    // Abstract logo mark
    ctx.beginPath();
    ctx.arc(cx, cy - 10, 30, 0, Math.PI * 2);
    ctx.strokeStyle = colors[0] || '#2C3E50';
    ctx.lineWidth = 2.5;
    ctx.stroke();

    // Inner cross pattern
    ctx.beginPath();
    ctx.moveTo(cx - 20, cy - 10); ctx.lineTo(cx + 20, cy - 10);
    ctx.moveTo(cx, cy - 30); ctx.lineTo(cx, cy + 10);
    ctx.strokeStyle = colors[1] || '#E74C3C';
    ctx.lineWidth = 2;
    ctx.stroke();

    // Accent dots
    for (let i = 0; i < 4; i++) {
        const a = (Math.PI / 2) * i;
        ctx.beginPath();
        ctx.arc(cx + Math.cos(a) * 20, cy - 10 + Math.sin(a) * 20, 3, 0, Math.PI * 2);
        ctx.fillStyle = colors[2] || colors[0];
        ctx.fill();
    }

    // Text
    if (title) {
        ctx.font = 'bold 10px Arial, sans-serif';
        ctx.fillStyle = colors[0] || '#2C3E50';
        ctx.textAlign = 'center';
        const shortTitle = title.length > 20 ? title.substring(0, 20) : title;
        ctx.fillText(shortTitle.toUpperCase(), cx, cy + 35);
    }
}

// Allow Enter key to trigger AI suggest
document.getElementById('aiPromptInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') getAISuggestions();
});

// ===== 3D Preview Rotation =====
let rotation3D = { y: 0, spinning: true, dragging: false, lastX: 0 };

function init3DPreview() {
    const container = document.getElementById('preview3dContainer');
    const inner = document.getElementById('preview3dInner');

    if (!container || !inner) return;

    // Start spinning
    inner.classList.add('spinning');

    // Mouse drag rotation
    container.addEventListener('mousedown', function(e) {
        rotation3D.dragging = true;
        rotation3D.lastX = e.clientX;
        inner.classList.remove('spinning');
        rotation3D.spinning = false;
        document.getElementById('spinBtn').classList.remove('active');
        e.preventDefault();
    });

    document.addEventListener('mousemove', function(e) {
        if (!rotation3D.dragging) return;
        const dx = e.clientX - rotation3D.lastX;
        rotation3D.y += dx * 0.8;
        rotation3D.lastX = e.clientX;
        inner.style.transform = `rotateY(${rotation3D.y}deg)`;
    });

    document.addEventListener('mouseup', function() {
        rotation3D.dragging = false;
    });

    // Touch drag rotation
    container.addEventListener('touchstart', function(e) {
        rotation3D.dragging = true;
        rotation3D.lastX = e.touches[0].clientX;
        inner.classList.remove('spinning');
        rotation3D.spinning = false;
        document.getElementById('spinBtn').classList.remove('active');
    }, { passive: true });

    document.addEventListener('touchmove', function(e) {
        if (!rotation3D.dragging) return;
        const dx = e.touches[0].clientX - rotation3D.lastX;
        rotation3D.y += dx * 0.8;
        rotation3D.lastX = e.touches[0].clientX;
        inner.style.transform = `rotateY(${rotation3D.y}deg)`;
    }, { passive: true });

    document.addEventListener('touchend', function() {
        rotation3D.dragging = false;
    });
}

function toggle3DSpin() {
    const inner = document.getElementById('preview3dInner');
    const btn = document.getElementById('spinBtn');
    rotation3D.spinning = !rotation3D.spinning;

    if (rotation3D.spinning) {
        inner.classList.add('spinning');
        inner.style.transform = '';
        btn.classList.add('active');
    } else {
        inner.classList.remove('spinning');
        const computedStyle = getComputedStyle(inner);
        const matrix = computedStyle.transform;
        inner.style.transform = matrix;
        btn.classList.remove('active');
    }
}

function rotate3DLeft() {
    const inner = document.getElementById('preview3dInner');
    inner.classList.remove('spinning');
    rotation3D.spinning = false;
    document.getElementById('spinBtn').classList.remove('active');
    rotation3D.y -= 45;
    inner.style.transition = 'transform 0.4s ease';
    inner.style.transform = `rotateY(${rotation3D.y}deg)`;
    setTimeout(() => { inner.style.transition = 'transform 0.1s ease-out'; }, 400);
}

function rotate3DRight() {
    const inner = document.getElementById('preview3dInner');
    inner.classList.remove('spinning');
    rotation3D.spinning = false;
    document.getElementById('spinBtn').classList.remove('active');
    rotation3D.y += 45;
    inner.style.transition = 'transform 0.4s ease';
    inner.style.transform = `rotateY(${rotation3D.y}deg)`;
    setTimeout(() => { inner.style.transition = 'transform 0.1s ease-out'; }, 400);
}

// ===== Price Auto Calculation =====
const pricing = {
    base: { tshirt: 350, hoodie: 650, polo: 450 },
    printSize: { small: 50, medium: 100, large: 180, full: 300 },
    colorCost: 25 // per color used
};

function calculatePrice() {
    const baseCost = pricing.base[state.apparelType] || 350;
    const printSize = document.getElementById('printSizeSelect')?.value || 'medium';
    const printSizeCost = pricing.printSize[printSize] || 100;
    const colorsUsed = countColorsUsed();
    const colorCost = Math.max(0, (colorsUsed - 1)) * pricing.colorCost; // first color free

    document.getElementById('priceBase').textContent = '₱' + baseCost.toLocaleString();
    document.getElementById('pricePrintSize').textContent = '₱' + printSizeCost.toLocaleString();
    document.getElementById('priceColorsCount').textContent = colorsUsed;
    document.getElementById('priceColors').textContent = colorsUsed > 1 ? '₱' + colorCost.toLocaleString() : 'Free';
    // Quantity
    const qty = parseInt(document.getElementById('quantityInput')?.value) || 1;
    let subtotal = (baseCost + printSizeCost + colorCost) * qty;

    // Discount
    const discountType = document.getElementById('discountSelect')?.value || 'regular';
    let discountPercent = 0;
    if (discountType === 'senior' || discountType === 'pwd') discountPercent = 0.20;
    const discountAmount = subtotal * discountPercent;
    const finalTotal = subtotal - discountAmount;

    if (discountPercent > 0) {
        document.getElementById('discountRow').style.display = 'block';
        document.getElementById('priceDiscount').textContent = '-₱' + discountAmount.toLocaleString();
    } else {
        document.getElementById('discountRow').style.display = 'none';
    }

    document.getElementById('priceTotal').textContent = '₱' + finalTotal.toLocaleString();
}

function adjustQty(delta) {
    const input = document.getElementById('quantityInput');
    let val = parseInt(input.value) || 1;
    val = Math.max(1, Math.min(100, val + delta));
    input.value = val;
    calculatePrice();
}

function countColorsUsed() {
    const colors = new Set();
    colors.add(state.brushColor);

    // Sample canvas pixels to count unique colors
    try {
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const data = imageData.data;
        const step = 8; // sample every 8th pixel for performance
        for (let i = 0; i < data.length; i += 4 * step) {
            const a = data[i + 3];
            if (a > 30) {
                const r = data[i];
                const g = data[i + 1];
                const b = data[i + 2];
                // Quantize to reduce noise
                const qr = Math.round(r / 32) * 32;
                const qg = Math.round(g / 32) * 32;
                const qb = Math.round(b / 32) * 32;
                colors.add(`${qr},${qg},${qb}`);
            }
        }
    } catch (e) {
        // Canvas may be tainted
    }

    // Count text/element colors
    state.elements.forEach(el => {
        const span = el.querySelector('span');
        if (span && span.style.color) {
            colors.add(span.style.color);
        }
    });

    return Math.min(colors.size, 20); // cap at 20
}

// Init on page load
document.addEventListener('DOMContentLoaded', function() {
    init();
    init3DPreview();
    calculatePrice();
});
</script>

<?php include 'includes/footer/footer.php'; ?>
