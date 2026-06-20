import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { DRACOLoader } from 'three/examples/jsm/loaders/DRACOLoader.js';

const loader = new GLTFLoader();
const dracoLoader = new DRACOLoader();
dracoLoader.setDecoderPath('https://www.gstatic.com/draco/versioned/decoders/1.5.6/');
loader.setDRACOLoader(dracoLoader);

export function loadClothingModel(url, onProgress) {
    return new Promise((resolve, reject) => {
        loader.load(
            url,
            (gltf) => {
                const model = gltf.scene;
                model.traverse((child) => {
                    if (child.isMesh) {
                        child.castShadow = true;
                        child.receiveShadow = true;
                        if (child.material) {
                            if (Array.isArray(child.material)) {
                                child.material.forEach((mat) => {
                                    mat.roughness = 0.4;
                                    mat.metalness = 0.1;
                                });
                            } else {
                                child.material.roughness = 0.4;
                                child.material.metalness = 0.1;
                            }
                        }
                    }
                });
                resolve(model);
            },
            (xhr) => {
                if (onProgress && xhr.total > 0) {
                    onProgress(xhr.loaded / xhr.total);
                }
            },
            (error) => {
                reject(error);
            }
        );
    });
}

export function applyTextureToModel(model, textureUrl) {
    return new Promise((resolve, reject) => {
        const texLoader = new THREE.TextureLoader();
        texLoader.load(
            textureUrl,
            (texture) => {
                texture.colorSpace = THREE.SRGBColorSpace;
                model.traverse((child) => {
                    if (child.isMesh && child.material) {
                        const materials = Array.isArray(child.material)
                            ? child.material
                            : [child.material];
                        materials.forEach((mat) => {
                            if (mat.map) {
                                mat.map = texture;
                                mat.needsUpdate = true;
                            }
                        });
                    }
                });
                resolve();
            },
            undefined,
            reject
        );
    });
}

export function setModelColor(model, hexColor) {
    const color = new THREE.Color(hexColor);
    model.traverse((child) => {
        if (child.isMesh && child.material) {
            const materials = Array.isArray(child.material)
                ? child.material
                : [child.material];
            materials.forEach((mat) => {
                if (mat.color) {
                    mat.color.copy(color);
                    mat.needsUpdate = true;
                }
            });
        }
    });
}

export function setModelScale(model, scale) {
    model.scale.set(scale, scale, scale);
}
