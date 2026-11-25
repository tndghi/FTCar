
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($car->name); ?> — Chi tiết xe</title>
    <style>
        :root {
            --bg: #0b0d10;
            --panel: #14171b;
            --line: #23272c;
            --text: #eceff1;
            --text-dim: #9aa3ab;
            --accent: #f5a623; /* đèn pha / spotlight showroom */
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: -apple-system, "Segoe UI", Roboto, sans-serif;
        }
        .layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            height: 100vh;
        }
        @media (max-width: 860px) {
            .layout { grid-template-columns: 1fr; grid-template-rows: 55vh auto; height: auto; }
        }
        .viewer {
            position: relative;
            background: radial-gradient(ellipse at 50% 35%, #1a1e23 0%, var(--bg) 70%);
            overflow: hidden;
        }
        #canvas-holder { width: 100%; height: 100%; display: block; }
        .viewer-hint {
            position: absolute; left: 20px; bottom: 20px;
            font-size: 13px; color: var(--text-dim);
            background: rgba(0,0,0,0.35); padding: 6px 12px; border-radius: 20px;
        }
        .rotate-toggle {
            position: absolute; right: 20px; bottom: 20px;
            background: var(--panel); border: 1px solid var(--line); color: var(--text);
            padding: 8px 14px; border-radius: 20px; font-size: 13px; cursor: pointer;
        }
        .rotate-toggle:focus-visible, button:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

        .sidebar {
            border-left: 1px solid var(--line);
            padding: 24px;
            overflow-y: auto;
            display: flex; flex-direction: column; gap: 18px;
        }
        .brand-badge {
            display: inline-block; font-size: 12px; letter-spacing: .08em; text-transform: uppercase;
            color: var(--accent); border: 1px solid var(--accent); padding: 3px 10px; border-radius: 999px;
            width: fit-content;
        }
        h1 { font-size: 22px; margin: 6px 0 0; }
        .price { font-size: 24px; font-weight: 700; color: var(--accent); }

        .part-panel {
            background: var(--panel); border: 1px solid var(--line); border-radius: 12px;
            padding: 16px; min-height: 110px;
        }
        .part-panel .label { font-weight: 600; font-size: 15px; margin-bottom: 4px; }
        .part-panel .desc { font-size: 13px; color: var(--text-dim); line-height: 1.5; }
        .part-panel .extra { margin-top: 8px; font-size: 13px; color: var(--accent); }

        .cta {
            margin-top: auto;
            background: var(--accent); color: #1a1200; border: none;
            padding: 14px; border-radius: 10px; font-weight: 700; font-size: 15px; cursor: pointer;
        }
        .cta:hover { filter: brightness(1.08); }

        dialog {
            border: 1px solid var(--line); border-radius: 12px; background: var(--panel); color: var(--text);
            padding: 20px; width: min(360px, 90vw);
        }
        dialog::backdrop { background: rgba(0,0,0,0.6); }
        dialog label { display: block; font-size: 13px; color: var(--text-dim); margin: 10px 0 4px; }
        dialog input, dialog textarea {
            width: 100%; background: #0f1215; border: 1px solid var(--line); color: var(--text);
            padding: 9px 10px; border-radius: 8px; font-size: 14px;
        }
        dialog .actions { display: flex; gap: 10px; margin-top: 16px; }
        dialog button { flex: 1; padding: 10px; border-radius: 8px; border: 1px solid var(--line); cursor: pointer; }
        dialog .submit { background: var(--accent); color: #1a1200; border: none; font-weight: 700; }
        #booking-status { font-size: 13px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="layout">
        <div class="viewer">
            <div id="canvas-holder"></div>
            <div class="viewer-hint">Kéo để xoay · cuộn để zoom · chạm vào bộ phận để xem chi tiết</div>
            <button class="rotate-toggle" id="autorotate-btn" type="button">Tự xoay: Bật</button>
        </div>

        <div class="sidebar">
            <div>
                <span class="brand-badge"><?php echo e($car->brand); ?><?php echo e($car->model ? ' · '.$car->model : ''); ?></span>
                <h1><?php echo e($car->name); ?></h1>
                <div class="price"><?php echo e(number_format($car->price, 0, ',', '.')); ?> đ</div>
            </div>

            <div class="part-panel" id="part-panel">
                <div class="label" id="part-label">Toàn bộ xe</div>
                <div class="desc" id="part-desc"><?php echo e($car->description ?? 'Chạm vào từng bộ phận trên mô hình 3D để xem chi tiết.'); ?></div>
                <div class="extra" id="part-extra"></div>
            </div>

            <button class="cta" id="open-booking">Đặt lịch xem xe</button>
        </div>
    </div>

    <dialog id="booking-dialog">
        <form method="dialog" id="booking-form">
            <strong>Đặt lịch xem xe — <?php echo e($car->name); ?></strong>
            <label for="full_name">Họ tên</label>
            <input id="full_name" required>
            <label for="phone">Số điện thoại</label>
            <input id="phone" required>
            <label for="scheduled_at">Thời gian hẹn</label>
            <input id="scheduled_at" type="datetime-local" required>
            <div class="actions">
                <button type="button" onclick="document.getElementById('booking-dialog').close()">Huỷ</button>
                <button class="submit" type="submit">Gửi</button>
            </div>
            <div id="booking-status"></div>
        </form>
    </dialog>

    <script type="importmap">
    {
        "imports": {
            "three": "https://cdnjs.cloudflare.com/ajax/libs/three.js/0.160.0/three.module.js",
            "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
        }
    }
    </script>

    <script type="module">
        import * as THREE from 'three';
        import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
        import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

        // Dữ liệu xe được server render sẵn — không cần gọi thêm API khi vào trang
        const car = <?php echo json_encode($car, 15, 512) ?>;
        const partsMap = Object.fromEntries((car.parts || []).map(p => [p.object_name, p]));

        const holder = document.getElementById('canvas-holder');
        const scene = new THREE.Scene();

        const camera = new THREE.PerspectiveCamera(40, holder.clientWidth / holder.clientHeight, 0.1, 100);
        camera.position.set(4.5, 2.2, 5.5);

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(holder.clientWidth, holder.clientHeight);
        renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
        renderer.shadowMap.enabled = true;
        holder.appendChild(renderer.domElement);

        // Showroom lighting: spotlight chính + ambient nhẹ + sàn phản chiếu mờ
        scene.add(new THREE.AmbientLight(0xffffff, 0.35));
        const spot = new THREE.SpotLight(0xfff2d9, 6, 20, Math.PI / 6, 0.4, 1);
        spot.position.set(2, 6, 3);
        spot.castShadow = true;
        scene.add(spot);
        const fill = new THREE.DirectionalLight(0x6b8cff, 0.4);
        fill.position.set(-4, 2, -3);
        scene.add(fill);

        const floor = new THREE.Mesh(
            new THREE.CircleGeometry(6, 48),
            new THREE.MeshStandardMaterial({ color: 0x111417, roughness: 0.35, metalness: 0.2 })
        );
        floor.rotation.x = -Math.PI / 2;
        floor.receiveShadow = true;
        scene.add(floor);

        const controls = new OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.minDistance = 3;
        controls.maxDistance = 10;
        controls.maxPolarAngle = Math.PI / 2 - 0.02;
        controls.target.set(0, 0.6, 0);

        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let autoRotate = !reducedMotion;
        controls.autoRotate = autoRotate;
        controls.autoRotateSpeed = 1.2;
        document.getElementById('autorotate-btn').addEventListener('click', (e) => {
            autoRotate = !autoRotate;
            controls.autoRotate = autoRotate;
            e.target.textContent = 'Tự xoay: ' + (autoRotate ? 'Bật' : 'Tắt');
        });

        let carRoot = new THREE.Group();
        scene.add(carRoot);

        // --- Nạp model 3D thật nếu xe có model_3d_url; nếu lỗi/không có thì dùng khối placeholder ---
        // Quan trọng: tên object trong file .glb (đặt trong Blender) phải khớp car_parts.object_name
        // để tra cứu partsMap[object.name] hoạt động đúng.
        if (car.model_3d_url) {
            new GLTFLoader().load(
                car.model_3d_url,
                (gltf) => {
                    carRoot.remove(...carRoot.children);
                    gltf.scene.traverse(o => { if (o.isMesh) { o.castShadow = true; o.receiveShadow = true; } });
                    carRoot.add(gltf.scene);
                },
                undefined,
                () => buildPlaceholderCar(carRoot) // load lỗi -> fallback demo
            );
        } else {
            buildPlaceholderCar(carRoot);
        }

        // Xe khối hộp đơn giản để demo tương tác khi chưa có file .glb thật
        function buildPlaceholderCar(root) {
            const body = new THREE.Mesh(
                new THREE.BoxGeometry(2.6, 0.6, 1.1),
                new THREE.MeshStandardMaterial({ color: 0xc0392b, metalness: 0.6, roughness: 0.3 })
            );
            body.name = 'body';
            body.position.y = 0.55;
            body.castShadow = true;
            root.add(body);

            const cabin = new THREE.Mesh(
                new THREE.BoxGeometry(1.3, 0.45, 1.0),
                new THREE.MeshStandardMaterial({ color: 0x1c1f22, metalness: 0.4, roughness: 0.4 })
            );
            cabin.name = 'cabin';
            cabin.position.set(-0.1, 1.0, 0);
            cabin.castShadow = true;
            root.add(cabin);

            const wheelGeo = new THREE.CylinderGeometry(0.32, 0.32, 0.28, 20);
            const wheelMat = new THREE.MeshStandardMaterial({ color: 0x111111, roughness: 0.8 });
            [
                ['wheel_front_left', 0.95, -0.6],
                ['wheel_front_right', 0.95, 0.6],
                ['wheel_rear_left', -0.95, -0.6],
                ['wheel_rear_right', -0.95, 0.6],
            ].forEach(([name, x, z]) => {
                const wheel = new THREE.Mesh(wheelGeo, wheelMat);
                wheel.name = name;
                wheel.rotation.z = Math.PI / 2;
                wheel.position.set(x, 0.32, z);
                wheel.castShadow = true;
                root.add(wheel);
            });

            const lightGeo = new THREE.SphereGeometry(0.12, 16, 16);
            const lightMat = new THREE.MeshStandardMaterial({ color: 0xfff4cc, emissive: 0xfff4cc, emissiveIntensity: 0.6 });
            [['headlight_left', -0.65], ['headlight_right', 0.65]].forEach(([name, z]) => {
                const l = new THREE.Mesh(lightGeo, lightMat);
                l.name = name;
                l.position.set(1.32, 0.6, z);
                root.add(l);
            });

            const mirrorGeo = new THREE.BoxGeometry(0.08, 0.12, 0.2);
            const mirrorMat = new THREE.MeshStandardMaterial({ color: 0x222222 });
            [['mirror_left', -0.62], ['mirror_right', 0.62]].forEach(([name, z]) => {
                const m = new THREE.Mesh(mirrorGeo, mirrorMat);
                m.name = name;
                m.position.set(0.55, 0.85, z);
                root.add(m);
            });
        }

        // --- Raycasting: chạm/click vào 1 object -> tra object.name trong partsMap ---
        const raycaster = new THREE.Raycaster();
        const pointer = new THREE.Vector2();

        function onPick(event) {
            const rect = renderer.domElement.getBoundingClientRect();
            const clientX = event.clientX ?? event.changedTouches?.[0]?.clientX;
            const clientY = event.clientY ?? event.changedTouches?.[0]?.clientY;
            pointer.x = ((clientX - rect.left) / rect.width) * 2 - 1;
            pointer.y = -((clientY - rect.top) / rect.height) * 2 + 1;

            raycaster.setFromCamera(pointer, camera);
            const hits = raycaster.intersectObjects(carRoot.children, true);
            if (!hits.length) return;

            // đi ngược lên cây object tới khi tìm thấy tên khớp part, hoặc dừng ở root
            let obj = hits[0].object;
            while (obj && obj !== carRoot && !partsMap[obj.name]) obj = obj.parent;

            const part = obj ? partsMap[obj.name] : null;
            const labelEl = document.getElementById('part-label');
            const descEl = document.getElementById('part-desc');
            const extraEl = document.getElementById('part-extra');

            if (part) {
                labelEl.textContent = part.label;
                descEl.textContent = part.description || 'Chưa có mô tả cho bộ phận này.';
                extraEl.textContent = part.extra_price ? `Phụ phí tuỳ chọn: ${Number(part.extra_price).toLocaleString('vi-VN')} đ` : '';
            } else {
                labelEl.textContent = 'Toàn bộ xe';
                descEl.textContent = car.description || '';
                extraEl.textContent = '';
            }
        }
        renderer.domElement.addEventListener('click', onPick);

        window.addEventListener('resize', () => {
            camera.aspect = holder.clientWidth / holder.clientHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(holder.clientWidth, holder.clientHeight);
        });

        (function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        })();

        // --- Form đặt lịch: POST thẳng tới REST API đã xây ở giai đoạn trước ---
        const dialog = document.getElementById('booking-dialog');
        document.getElementById('open-booking').addEventListener('click', () => dialog.showModal());
        document.getElementById('booking-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const status = document.getElementById('booking-status');
            status.textContent = 'Đang gửi...';
            try {
                const res = await fetch('/api/bookings', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        car_id: car.id,
                        full_name: document.getElementById('full_name').value,
                        phone: document.getElementById('phone').value,
                        scheduled_at: document.getElementById('scheduled_at').value.replace('T', ' '),
                    }),
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Có lỗi xảy ra');
                status.style.color = '#7cd992';
                status.textContent = data.message || 'Đặt lịch thành công!';
                setTimeout(() => dialog.close(), 1200);
            } catch (err) {
                status.style.color = '#e56b6f';
                status.textContent = err.message;
            }
        });
    </script>
</body>
</html><?php /**PATH C:\laravel-projects\my-app\Web-Ban-Xe\resources\views/cars/show.blade.php ENDPATH**/ ?>