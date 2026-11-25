<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $car->name }} — Chi tiết xe</title>
    <style>
        :root {
            --bg: #e7ece8;
            --panel: #ffffff;
            --line: #d7dfd9;
            --text: #1d2925;
            --text-dim: #65736b;
            --accent: #c56242;
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
            background: radial-gradient(ellipse at 50% 35%, #ffffff 0%, #e1e8e2 76%);
            overflow: hidden;
        }
        #canvas-holder { width: 100%; height: 100%; display: block; }
        .viewer-hint {
            position: absolute; left: 20px; bottom: 20px;
            font-size: 13px; color: var(--text-dim);
            background: rgba(255,255,255,0.88); padding: 7px 12px; border: 1px solid var(--line); border-radius: 20px;
        }
        .rotate-toggle {
            position: absolute; right: 20px; bottom: 20px;
            background: var(--panel); border: 1px solid var(--line); color: var(--text);
            padding: 8px 14px; border-radius: 6px; font-size: 13px; cursor: pointer;
        }
        .rotate-toggle:focus-visible, button:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

        .sidebar {
            border-left: 1px solid var(--line); background: #fbfcfa;
            padding: 24px;
            overflow-y: auto;
            display: flex; flex-direction: column; gap: 18px;
        }
        .brand-badge {
            display: inline-block; font-size: 12px; letter-spacing: .08em; text-transform: uppercase;
            color: var(--accent); border: 1px solid var(--accent); padding: 3px 10px; border-radius: 4px;
            width: fit-content;
        }
        h1 { font-size: 22px; margin: 6px 0 0; font-family: "Segoe UI", Arial, sans-serif; }
        .price { font-size: 24px; font-weight: 700; color: var(--accent); }

        .part-panel {
            background: var(--panel); border: 1px solid var(--line); border-radius: 6px;
            padding: 16px; min-height: 110px;
        }
        .part-panel .eyebrow { margin-bottom: 8px; color: var(--accent); font-size: 10px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; }
        .part-panel .label { font-weight: 600; font-size: 15px; margin-bottom: 4px; }
        .part-panel .desc { font-size: 13px; color: var(--text-dim); line-height: 1.5; }
        .part-panel .extra { margin-top: 8px; font-size: 13px; color: var(--accent); }

        .cta {
            margin-top: auto;
            background: var(--accent); color: white; border: none;
            padding: 14px; border-radius: 5px; font-weight: 700; font-size: 15px; cursor: pointer;
        }
        .cta:hover { filter: brightness(1.08); }

        dialog {
            border: 1px solid var(--line); border-radius: 8px; background: var(--panel); color: var(--text);
            padding: 24px; width: min(520px, calc(100vw - 32px)); max-height: calc(100dvh - 32px); overflow-y: auto;
        }
        dialog::backdrop { background: rgba(19,31,25,0.52); }
        .booking-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
        .booking-heading strong { display: block; font-size: 18px; }
        .booking-heading p { margin: 5px 0 0; color: var(--text-dim); font-size: 13px; }
        .booking-close { width: 34px; height: 34px; flex: 0 0 auto; border: 1px solid var(--line); background: #fff; color: var(--text); font-size: 20px; cursor: pointer; }
        .booking-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .booking-field.full { grid-column: 1 / -1; }
        dialog label { display: block; font-size: 12px; color: var(--text); font-weight: 650; margin: 0 0 6px; }
        dialog input, dialog textarea {
            width: 100%; min-height: 42px; background: #fff; border: 1px solid #cbd6ce; color: var(--text);
            padding: 10px 11px; border-radius: 5px; font-size: 14px;
        }
        dialog textarea { min-height: 86px; resize: vertical; }
        dialog input:focus, dialog textarea:focus { outline: 2px solid rgba(197,98,66,.22); border-color: var(--accent); }
        .booking-hint { display: block; margin-top: 5px; color: var(--text-dim); font-size: 11px; }
        dialog .actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 18px; }
        dialog .actions button { min-height: 40px; padding: 0 14px; border-radius: 5px; border: 1px solid var(--line); background: white; color: var(--text); cursor: pointer; }
        dialog .submit { background: var(--accent); color: white; border-color: var(--accent); font-weight: 700; }
        dialog .submit:disabled { opacity: .65; cursor: wait; }
        #booking-status { min-height: 18px; font-size: 13px; margin: 12px 0 0; }
        @media (max-width: 520px) {
            dialog { padding: 18px; }
            .booking-grid { grid-template-columns: 1fr; gap: 12px; }
            .booking-field.full { grid-column: auto; }
        }
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
                <span class="brand-badge">{{ $car->brand }}{{ $car->model ? ' · '.$car->model : '' }}</span>
                <h1>{{ $car->name }}</h1>
                <div class="price">{{ number_format($car->price, 0, ',', '.') }} đ</div>
            </div>

            <div class="part-panel" id="part-panel" aria-live="polite">
                <div class="eyebrow" id="part-type">Thông tin xe</div>
                <div class="label" id="part-label">Toàn bộ xe</div>
                <div class="desc" id="part-desc">{{ $car->description ?? 'Chạm hoặc click vào gương, bánh xe, động cơ để xem chi tiết.' }}</div>
                <div class="extra" id="part-extra"></div>
            </div>

            <button class="cta" id="open-booking">Đặt lịch xem xe</button>
        </div>
    </div>

    <dialog id="booking-dialog" aria-labelledby="booking-title">
        <form method="dialog" id="booking-form">
            <div class="booking-heading">
                <div><strong id="booking-title">Đặt lịch xem xe</strong><p>{{ $car->name }} · Điền thông tin để showroom liên hệ xác nhận.</p></div>
                <button class="booking-close" type="button" aria-label="Đóng form" onclick="document.getElementById('booking-dialog').close()">×</button>
            </div>
            <div class="booking-grid">
                <div class="booking-field"><label for="full_name">Họ và tên *</label><input id="full_name" autocomplete="name" placeholder="Nguyễn Văn A" required maxlength="255"></div>
                <div class="booking-field"><label for="phone">Số điện thoại *</label><input id="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="09xx xxx xxx" required maxlength="20"></div>
                <div class="booking-field full"><label for="scheduled_at">Ngày và giờ muốn xem *</label><input id="scheduled_at" type="datetime-local" step="1800" required><span class="booking-hint">Thời gian phải sau thời điểm hiện tại.</span></div>
                <div class="booking-field full"><label for="booking-note">Ghi chú <span class="booking-hint" style="display:inline">(không bắt buộc)</span></label><textarea id="booking-note" maxlength="1000" placeholder="Ví dụ: muốn xem xe màu đen hoặc cần tư vấn trả góp"></textarea></div>
            </div>
            <div class="actions">
                <button type="button" onclick="document.getElementById('booking-dialog').close()">Đóng</button>
                <button class="submit" type="submit">Gửi yêu cầu</button>
            </div>
            <p id="booking-status" role="status" aria-live="polite"></p>
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
        const car = @json($car);
        car.model_3d_url = @json($car->has_3d_model && $car->model_3d_path ? asset('storage/'.$car->model_3d_path) : null);
        const partsMap = Object.fromEntries((car.parts || []).map(p => [p.object_name, p]));
        const commonParts = [
            { match: /mirror|rear.?view|wing.?mirror|gương/i, label: 'Gương chiếu hậu', description: 'Giúp quan sát phía sau và hai bên xe, hỗ trợ chuyển làn và lùi xe an toàn.' },
            { match: /wheel|tire|tyre|rim|lốp|bánh/i, label: 'Bánh xe và lốp', description: 'Lốp tạo độ bám mặt đường; kích thước và tình trạng lốp ảnh hưởng đến độ êm và khả năng vận hành.' },
            { match: /engine|motor|powertrain|động cơ/i, label: 'Động cơ', description: 'Bộ phận tạo công suất cho xe. Thông số chi tiết phụ thuộc vào phiên bản xe.' },
        ];

        const holder = document.getElementById('canvas-holder');
        const scene = new THREE.Scene();

        const camera = new THREE.PerspectiveCamera(40, holder.clientWidth / holder.clientHeight, 0.1, 100);
        camera.position.set(4.5, 2.2, 5.5);

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(holder.clientWidth, holder.clientHeight);
        renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
        renderer.shadowMap.enabled = true;
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.4;
        holder.appendChild(renderer.domElement);

        // Showroom lighting: spotlight chính + ambient nhẹ + sàn phản chiếu mờ
        scene.background = new THREE.Color(0xe3e9e4);
        scene.add(new THREE.AmbientLight(0xffffff, 0.95));
        scene.add(new THREE.HemisphereLight(0xffffff, 0x87968b, 2));
        const spot = new THREE.SpotLight(0xfff7e8, 22, 24, Math.PI / 5, 0.45, 1);
        spot.position.set(2, 6, 3);
        spot.castShadow = true;
        scene.add(spot);
        const fill = new THREE.DirectionalLight(0xffffff, 2.4);
        fill.position.set(-4, 2, -3);
        scene.add(fill);
        const rimLight = new THREE.DirectionalLight(0xffedcf, 1.5);
        rimLight.position.set(-3, 5, 4);
        scene.add(rimLight);

        const floor = new THREE.Mesh(
            new THREE.CircleGeometry(6, 48),
            new THREE.MeshStandardMaterial({ color: 0xdce3dc, roughness: 0.62, metalness: 0.04 })
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

            let obj = hits[0].object;
            let part = null;
            let selectedName = '';
            while (obj && obj !== carRoot) {
                selectedName ||= obj.name;
                part = partsMap[obj.name] || commonParts.find(item => item.match.test(obj.name));
                if (part) break;
                obj = obj.parent;
            }
            const labelEl = document.getElementById('part-label');
            const descEl = document.getElementById('part-desc');
            const extraEl = document.getElementById('part-extra');
            const typeEl = document.getElementById('part-type');

            if (part) {
                typeEl.textContent = 'Chi tiết bộ phận';
                labelEl.textContent = part.label;
                descEl.textContent = part.description || 'Chưa có mô tả cho bộ phận này.';
                extraEl.textContent = part.extra_price ? `Phụ phí tuỳ chọn: ${Number(part.extra_price).toLocaleString('vi-VN')} đ` : '';
            } else {
                typeEl.textContent = selectedName ? 'Bộ phận trong mô hình' : 'Thông tin xe';
                labelEl.textContent = selectedName ? selectedName.replace(/[_-]+/g, ' ') : 'Toàn bộ xe';
                descEl.textContent = selectedName
                    ? 'Chưa có mô tả cho bộ phận này. Có thể khai báo chi tiết trong danh sách car_parts.'
                    : car.description || 'Chạm hoặc click vào gương, bánh xe, động cơ để xem chi tiết.';
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
        const bookingForm = document.getElementById('booking-form');
        const scheduledAtInput = document.getElementById('scheduled_at');
        const submitBookingButton = bookingForm.querySelector('.submit');
        const toLocalDateTime = (date) => {
            const localDate = new Date(date.getTime() - date.getTimezoneOffset() * 60000);
            return localDate.toISOString().slice(0, 16);
        };

        document.getElementById('open-booking').addEventListener('click', () => {
            const now = new Date();
            scheduledAtInput.min = toLocalDateTime(now);
            if (!scheduledAtInput.value || scheduledAtInput.value < scheduledAtInput.min) {
                const nextSlot = new Date(now);
                nextSlot.setMinutes(Math.ceil((now.getMinutes() + 1) / 30) * 30, 0, 0);
                scheduledAtInput.value = toLocalDateTime(nextSlot);
            }
            document.getElementById('booking-status').textContent = '';
            dialog.showModal();
        });
        bookingForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const status = document.getElementById('booking-status');
            submitBookingButton.disabled = true;
            status.style.color = 'var(--text-dim)';
            status.textContent = 'Đang gửi yêu cầu...';
            try {
                const res = await fetch('/api/bookings', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        car_id: car.id,
                        full_name: document.getElementById('full_name').value,
                        phone: document.getElementById('phone').value,
                        scheduled_at: scheduledAtInput.value.replace('T', ' '),
                        note: document.getElementById('booking-note').value.trim() || null,
                    }),
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const validationMessage = Object.values(data.errors || {}).flat()[0];
                    throw new Error(validationMessage || data.message || 'Không gửi được yêu cầu. Vui lòng thử lại.');
                }
                status.style.color = '#287448';
                status.textContent = data.message || 'Đặt lịch thành công!';
                setTimeout(() => dialog.close(), 1200);
            } catch (err) {
                status.style.color = '#b6413a';
                status.textContent = err.message;
            } finally {
                submitBookingButton.disabled = false;
            }
        });
    </script>
</body>
</html>