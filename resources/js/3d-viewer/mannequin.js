import * as THREE from 'three';

export function createMannequin() {
    const group = new THREE.Group();
    const mat = new THREE.MeshStandardMaterial({
        color: 0x1a1a1a,
        roughness: 0.6,
        metalness: 0.1,
    });

    const torso = new THREE.Mesh(new THREE.CylinderGeometry(0.45, 0.5, 0.8, 16), mat);
    torso.position.y = 0.9;
    torso.castShadow = true;
    group.add(torso);

    const head = new THREE.Mesh(new THREE.SphereGeometry(0.2, 16, 16), mat);
    head.position.y = 1.55;
    head.castShadow = true;
    group.add(head);

    const leftUpperArm = new THREE.Mesh(new THREE.CylinderGeometry(0.08, 0.1, 0.4, 8), mat);
    leftUpperArm.position.set(-0.55, 1.15, 0);
    leftUpperArm.rotation.z = 0.2;
    leftUpperArm.castShadow = true;
    group.add(leftUpperArm);

    const rightUpperArm = new THREE.Mesh(new THREE.CylinderGeometry(0.08, 0.1, 0.4, 8), mat);
    rightUpperArm.position.set(0.55, 1.15, 0);
    rightUpperArm.rotation.z = -0.2;
    rightUpperArm.castShadow = true;
    group.add(rightUpperArm);

    const leftLeg = new THREE.Mesh(new THREE.CylinderGeometry(0.1, 0.12, 0.6, 8), mat);
    leftLeg.position.set(-0.15, 0.3, 0);
    leftLeg.castShadow = true;
    group.add(leftLeg);

    const rightLeg = new THREE.Mesh(new THREE.CylinderGeometry(0.1, 0.12, 0.6, 8), mat);
    rightLeg.position.set(0.15, 0.3, 0);
    rightLeg.castShadow = true;
    group.add(rightLeg);

    group.position.y = 0;
    group.visible = false;

    return group;
}
