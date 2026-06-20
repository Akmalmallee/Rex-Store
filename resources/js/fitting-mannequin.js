import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

export class FittingMannequin {
    constructor(containerId, profile = {}) {
        this.container = document.getElementById(containerId);
        if (!this.container) return;
        this.profile = profile;
        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.controls = null;
        this.animationId = null;
        this.mannequin = null;
    }

    init() {
        const width = this.container.clientWidth;
        const height = this.container.clientHeight || 400;

        this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        this.renderer.setSize(width, height);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.2;
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        this.container.appendChild(this.renderer.domElement);

        this.camera = new THREE.PerspectiveCamera(30, width / height, 0.1, 100);
        this.camera.position.set(0, 1.2, 5);
        this.camera.lookAt(0, 0.8, 0);

        this.scene = new THREE.Scene();
        this.scene.background = new THREE.Color(0x0a0a0a);

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        this.scene.add(ambientLight);

        const mainLight = new THREE.DirectionalLight(0xffffff, 2.0);
        mainLight.position.set(5, 8, 5);
        mainLight.castShadow = true;
        this.scene.add(mainLight);

        const fillLight = new THREE.DirectionalLight(0x8888ff, 0.3);
        fillLight.position.set(-3, 1, -3);
        this.scene.add(fillLight);

        const rimLight = new THREE.DirectionalLight(0xffeedd, 0.5);
        rimLight.position.set(-2, 3, -4);
        this.scene.add(rimLight);

        const groundGeo = new THREE.CircleGeometry(3, 32);
        const groundMat = new THREE.MeshStandardMaterial({
            color: 0x111111, roughness: 0.8, metalness: 0.2,
            transparent: true, opacity: 0.5, side: THREE.DoubleSide,
        });
        const ground = new THREE.Mesh(groundGeo, groundMat);
        ground.rotation.x = -Math.PI / 2;
        ground.position.y = -0.01;
        ground.receiveShadow = true;
        this.scene.add(ground);

        this.controls = new OrbitControls(this.camera, this.renderer.domElement);
        this.controls.target.set(0, 0.8, 0);
        this.controls.enableDamping = true;
        this.controls.dampingFactor = 0.08;
        this.controls.autoRotate = true;
        this.controls.autoRotateSpeed = 2.5;
        this.controls.minDistance = 2;
        this.controls.maxDistance = 8;
        this.controls.maxPolarAngle = Math.PI / 1.6;
        this.controls.minPolarAngle = Math.PI / 6;
        this.controls.update();

        this.createMannequin();
        this.animate();

        const ro = new ResizeObserver(() => this.resize());
        ro.observe(this.container);
        this._resizeObserver = ro;
    }

    createMannequin() {
        const p = this.profile;
        const height = p?.height || 170;
        const weight = p?.weight || 65;

        const heightScale = height / 170;
        const weightFactor = weight / 65;

        const baseMat = new THREE.MeshStandardMaterial({
            color: 0x2a2a2a,
            roughness: 0.7,
            metalness: 0.05,
        });
        const accentMat = new THREE.MeshStandardMaterial({
            color: 0x1a1a1a,
            roughness: 0.8,
            metalness: 0.05,
        });

        const group = new THREE.Group();

        const torsoRadTop = 0.40 * (0.8 + weightFactor * 0.4);
        const torsoRadBot = 0.48 * (0.8 + weightFactor * 0.4);
        const torsoHeight = 0.75 * heightScale;
        const torso = new THREE.Mesh(
            new THREE.CylinderGeometry(torsoRadTop, torsoRadBot, torsoHeight, 20),
            baseMat
        );
        torso.position.y = 0.85 * heightScale;
        torso.castShadow = true;
        group.add(torso);

        const headRad = 0.18 * (0.9 + weightFactor * 0.2);
        const head = new THREE.Mesh(
            new THREE.SphereGeometry(headRad, 20, 20),
            accentMat
        );
        head.position.y = (0.85 + torsoHeight / 2 + 0.22) * heightScale;
        head.scale.y = 1.15;
        head.castShadow = true;
        group.add(head);

        const shoulderWidth = (p?.shoulder_width || 40) / 40;
        const armLen = 0.38 * heightScale;
        const armRad = 0.085 * (0.7 + weightFactor * 0.5);

        const armPos = (0.55 + (shoulderWidth - 1) * 0.1) * (0.8 + weightFactor * 0.2);

        const leftArm = new THREE.Mesh(
            new THREE.CylinderGeometry(armRad * 0.8, armRad, armLen, 10),
            accentMat
        );
        leftArm.position.set(-armPos, (0.85 + torsoHeight / 2 - 0.05) * heightScale, 0);
        leftArm.rotation.z = 0.25;
        leftArm.castShadow = true;
        group.add(leftArm);

        const rightArm = new THREE.Mesh(
            new THREE.CylinderGeometry(armRad * 0.8, armRad, armLen, 10),
            accentMat
        );
        rightArm.position.set(armPos, (0.85 + torsoHeight / 2 - 0.05) * heightScale, 0);
        rightArm.rotation.z = -0.25;
        rightArm.castShadow = true;
        group.add(rightArm);

        const legLen = 0.55 * heightScale;
        const legRad = 0.11 * (0.7 + weightFactor * 0.5);
        const legSpread = 0.15 * (0.8 + weightFactor * 0.3);

        const leftLeg = new THREE.Mesh(
            new THREE.CylinderGeometry(legRad, legRad * 1.2, legLen, 10),
            baseMat
        );
        leftLeg.position.set(-legSpread, legLen / 2 - 0.02, 0);
        leftLeg.castShadow = true;
        group.add(leftLeg);

        const rightLeg = new THREE.Mesh(
            new THREE.CylinderGeometry(legRad, legRad * 1.2, legLen, 10),
            baseMat
        );
        rightLeg.position.set(legSpread, legLen / 2 - 0.02, 0);
        rightLeg.castShadow = true;
        group.add(rightLeg);

        if (p?.body_type === 'athletic' || p?.body_type === 'hourglass') {
            torso.scale.x = 1.1;
            torso.scale.z = 0.9;
            leftArm.scale.x = 1.15;
            rightArm.scale.x = 1.15;
        } else if (p?.body_type === 'plus' || p?.body_type === 'apple') {
            torso.scale.x = 1.2;
            torso.scale.z = 1.0;
        } else if (p?.body_type === 'slim') {
            torso.scale.x = 0.85;
            torso.scale.z = 0.85;
            leftArm.scale.x = 0.85;
            rightArm.scale.x = 0.85;
        }

        this.mannequin = group;
        this.scene.add(group);
    }

    animate() {
        const loop = () => {
            this.controls?.update();
            if (this.renderer && this.scene && this.camera) {
                this.renderer.render(this.scene, this.camera);
            }
            this.animationId = requestAnimationFrame(loop);
        };
        loop();
    }

    resize() {
        if (!this.container || !this.renderer || !this.camera) return;
        const w = this.container.clientWidth;
        const h = this.container.clientHeight || 400;
        this.camera.aspect = w / h;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(w, h);
    }

    destroy() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
            this.animationId = null;
        }
        if (this.controls) {
            this.controls.dispose();
            this.controls = null;
        }
        if (this.renderer) {
            if (this.renderer.domElement?.parentNode) {
                this.renderer.domElement.parentNode.removeChild(this.renderer.domElement);
            }
            this.renderer.dispose();
            this.renderer = null;
        }
        if (this._resizeObserver) {
            this._resizeObserver.disconnect();
            this._resizeObserver = null;
        }
        this.scene = null;
        this.camera = null;
        this.mannequin = null;
        this.container = null;
    }
}
